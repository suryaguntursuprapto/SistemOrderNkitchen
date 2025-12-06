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
use PhpOffice\PhpSpreadsheet\Style\Border;

class TrialBalanceExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
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
        $accounts = ChartOfAccount::with(['journalTransactions' => function ($query) {
            $query->whereHas('journal', function ($subQuery) {
                $subQuery->whereBetween('date', [$this->startDate, $this->endDate]);
            });
        }])
        ->orderBy('code', 'asc')
        ->get();

        $rows = collect();
        $totalDebit = 0;
        $totalCredit = 0;

        foreach ($accounts as $account) {
            $sumDebit = $account->journalTransactions->sum('debit');
            $sumCredit = $account->journalTransactions->sum('credit');

            // Skip accounts with no transactions
            if ($sumDebit == 0 && $sumCredit == 0) {
                continue;
            }

            // Calculate balance based on normal_balance
            $isDebitNormal = $account->normal_balance === 'debit';
            $openingBalance = $account->opening_balance ?? 0;
            
            if ($isDebitNormal) {
                $balance = $openingBalance + $sumDebit - $sumCredit;
                $debitBalance = $balance > 0 ? $balance : 0;
                $creditBalance = $balance < 0 ? abs($balance) : 0;
            } else {
                $balance = $openingBalance + $sumCredit - $sumDebit;
                $creditBalance = $balance > 0 ? $balance : 0;
                $debitBalance = $balance < 0 ? abs($balance) : 0;
            }

            $totalDebit += $debitBalance;
            $totalCredit += $creditBalance;

            $rows->push([
                'kode' => $account->code,
                'nama_akun' => $account->name,
                'tipe' => ucfirst($account->type),
                'debit' => $debitBalance > 0 ? $debitBalance : '',
                'kredit' => $creditBalance > 0 ? $creditBalance : '',
            ]);
        }

        // Add total row
        $rows->push([
            'kode' => '',
            'nama_akun' => 'TOTAL',
            'tipe' => '',
            'debit' => $totalDebit,
            'kredit' => $totalCredit,
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Kode Akun',
            'Nama Akun',
            'Tipe',
            'Debit (Rp)',
            'Kredit (Rp)',
        ];
    }

    public function title(): string
    {
        return 'Neraca Saldo';
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
                'startColor' => ['rgb' => '38A169'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Number format for currency columns
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("D2:E{$lastRow}")->getNumberFormat()
            ->setFormatCode('#,##0');

        // Right align currency columns
        $sheet->getStyle("D2:E{$lastRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Bold total row
        $sheet->getStyle("A{$lastRow}:E{$lastRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$lastRow}:E{$lastRow}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0'],
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_DOUBLE,
                ],
            ],
        ]);

        return [];
    }
}
