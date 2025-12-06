<?php

namespace App\Exports;

use App\Models\Journal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JournalExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $journals = Journal::with('transactions.chartOfAccount', 'referenceable')
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date', 'asc')
            ->get();

        $rows = collect();
        $journalNo = 1;

        foreach ($journals as $journal) {
            // Get reference number
            $ref = '-';
            if ($journal->referenceable_type === 'App\Models\Order' && $journal->referenceable) {
                $ref = $journal->referenceable->order_number ?? '-';
            } elseif ($journal->referenceable_type === 'App\Models\Expense' && $journal->referenceable) {
                $ref = 'EXP-' . $journal->referenceable->id;
            } elseif ($journal->referenceable_type === 'App\Models\Purchase' && $journal->referenceable) {
                $ref = $journal->referenceable->invoice_number ?? 'PUR-' . $journal->referenceable->id;
            }

            $isFirst = true;
            foreach ($journal->transactions as $tx) {
                $rows->push([
                    'no' => $isFirst ? $journalNo : '',
                    'tanggal' => $isFirst ? \Carbon\Carbon::parse($journal->date)->format('d/m/Y') : '',
                    'keterangan' => $isFirst ? $journal->description : '',
                    'ref' => $isFirst ? $ref : '',
                    'kode_akun' => $tx->chartOfAccount->code ?? '',
                    'nama_akun' => ($tx->credit > 0 ? '    ' : '') . ($tx->chartOfAccount->name ?? ''),
                    'debit' => $tx->debit > 0 ? $tx->debit : '',
                    'kredit' => $tx->credit > 0 ? $tx->credit : '',
                ]);
                $isFirst = false;
            }

            // Add empty row between journals
            $rows->push([
                'no' => '',
                'tanggal' => '',
                'keterangan' => '',
                'ref' => '',
                'kode_akun' => '',
                'nama_akun' => '',
                'debit' => '',
                'kredit' => '',
            ]);

            $journalNo++;
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No.',
            'Tanggal',
            'Keterangan',
            'Ref.',
            'Kode Akun',
            'Nama Akun',
            'Debit (Rp)',
            'Kredit (Rp)',
        ];
    }

    public function title(): string
    {
        return 'Jurnal Umum';
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4A5568'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Number format for currency columns
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("G2:H{$lastRow}")->getNumberFormat()
            ->setFormatCode('#,##0');

        // Right align currency columns
        $sheet->getStyle("G2:H{$lastRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        return [];
    }
}
