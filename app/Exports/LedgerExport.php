<?php

namespace App\Exports;

use App\Models\ChartOfAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LedgerExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
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
        $accounts = ChartOfAccount::whereHas('journalTransactions', function ($query) {
            $query->whereHas('journal', function ($subQuery) {
                $subQuery->whereBetween('date', [$this->startDate, $this->endDate]);
            });
        })
        ->with(['journalTransactions' => function ($query) {
            $query->whereHas('journal', function ($subQuery) {
                $subQuery->whereBetween('date', [$this->startDate, $this->endDate]);
            })->with('journal')->orderBy('id', 'asc');
        }])
        ->orderBy('code', 'asc')
        ->get();

        $rows = collect();

        foreach ($accounts as $account) {
            // Add account header row
            $rows->push([
                'tanggal' => '',
                'keterangan' => "【 {$account->code} - {$account->name} 】",
                'debit' => '',
                'kredit' => '',
                'saldo' => '',
            ]);

            // Calculate running balance
            $saldo = $account->opening_balance ?? 0;
            $isDebitNormal = $account->normal_balance === 'debit';

            foreach ($account->journalTransactions as $tx) {
                // Calculate new balance
                if ($isDebitNormal) {
                    $saldo = $saldo + $tx->debit - $tx->credit;
                } else {
                    $saldo = $saldo + $tx->credit - $tx->debit;
                }

                $rows->push([
                    'tanggal' => \Carbon\Carbon::parse($tx->journal->date)->format('d/m/Y'),
                    'keterangan' => $tx->journal->description,
                    'debit' => $tx->debit > 0 ? $tx->debit : '',
                    'kredit' => $tx->credit > 0 ? $tx->credit : '',
                    'saldo' => $saldo,
                ]);
            }

            // Add totals row for this account
            $totalDebit = $account->journalTransactions->sum('debit');
            $totalCredit = $account->journalTransactions->sum('credit');
            
            $rows->push([
                'tanggal' => '',
                'keterangan' => 'TOTAL',
                'debit' => $totalDebit,
                'kredit' => $totalCredit,
                'saldo' => $saldo,
            ]);

            // Empty row separator
            $rows->push([
                'tanggal' => '',
                'keterangan' => '',
                'debit' => '',
                'kredit' => '',
                'saldo' => '',
            ]);
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Keterangan',
            'Debit (Rp)',
            'Kredit (Rp)',
            'Saldo (Rp)',
        ];
    }

    public function title(): string
    {
        return 'Buku Besar';
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2B6CB0'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Number format for currency columns
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("C2:E{$lastRow}")->getNumberFormat()
            ->setFormatCode('#,##0');

        // Right align currency columns
        $sheet->getStyle("C2:E{$lastRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        return [];
    }
}
