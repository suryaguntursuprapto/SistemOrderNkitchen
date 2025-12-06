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

class IncomeStatementExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
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
        $rows = collect();

        // Get Revenue accounts (type = revenue or pendapatan)
        $revenueAccounts = ChartOfAccount::where('type', 'pendapatan')
            ->orWhere('type', 'revenue')
            ->with(['journalTransactions' => function ($query) {
                $query->whereHas('journal', function ($subQuery) {
                    $subQuery->whereBetween('date', [$this->startDate, $this->endDate]);
                });
            }])
            ->orderBy('code', 'asc')
            ->get();

        // Get Expense accounts (type = beban or expense)
        $expenseAccounts = ChartOfAccount::where('type', 'beban')
            ->orWhere('type', 'expense')
            ->with(['journalTransactions' => function ($query) {
                $query->whereHas('journal', function ($subQuery) {
                    $subQuery->whereBetween('date', [$this->startDate, $this->endDate]);
                });
            }])
            ->orderBy('code', 'asc')
            ->get();

        // PENDAPATAN Section
        $rows->push([
            'kode' => '',
            'nama_akun' => 'PENDAPATAN',
            'jumlah' => '',
        ]);

        $totalRevenue = 0;
        foreach ($revenueAccounts as $account) {
            $balance = $account->journalTransactions->sum('credit') - $account->journalTransactions->sum('debit');
            if ($balance != 0) {
                $totalRevenue += $balance;
                $rows->push([
                    'kode' => $account->code,
                    'nama_akun' => '    ' . $account->name,
                    'jumlah' => $balance,
                ]);
            }
        }

        $rows->push([
            'kode' => '',
            'nama_akun' => 'Total Pendapatan',
            'jumlah' => $totalRevenue,
        ]);

        // Empty row
        $rows->push([
            'kode' => '',
            'nama_akun' => '',
            'jumlah' => '',
        ]);

        // BEBAN Section
        $rows->push([
            'kode' => '',
            'nama_akun' => 'BEBAN-BEBAN',
            'jumlah' => '',
        ]);

        $totalExpenses = 0;
        foreach ($expenseAccounts as $account) {
            $balance = $account->journalTransactions->sum('debit') - $account->journalTransactions->sum('credit');
            if ($balance != 0) {
                $totalExpenses += $balance;
                $rows->push([
                    'kode' => $account->code,
                    'nama_akun' => '    ' . $account->name,
                    'jumlah' => $balance,
                ]);
            }
        }

        $rows->push([
            'kode' => '',
            'nama_akun' => 'Total Beban',
            'jumlah' => $totalExpenses,
        ]);

        // Empty row
        $rows->push([
            'kode' => '',
            'nama_akun' => '',
            'jumlah' => '',
        ]);

        // LABA/RUGI BERSIH
        $netIncome = $totalRevenue - $totalExpenses;
        $rows->push([
            'kode' => '',
            'nama_akun' => $netIncome >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH',
            'jumlah' => $netIncome,
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Kode Akun',
            'Nama Akun',
            'Jumlah (Rp)',
        ];
    }

    public function title(): string
    {
        return 'Laporan Laba Rugi';
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DD6B20'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Number format for currency column
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("C2:C{$lastRow}")->getNumberFormat()
            ->setFormatCode('#,##0');

        // Right align currency column
        $sheet->getStyle("C2:C{$lastRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        // Style section headers (PENDAPATAN, BEBAN-BEBAN)
        for ($i = 2; $i <= $lastRow; $i++) {
            $cellValue = $sheet->getCell("B{$i}")->getValue();
            if (in_array($cellValue, ['PENDAPATAN', 'BEBAN-BEBAN', 'LABA BERSIH', 'RUGI BERSIH'])) {
                $sheet->getStyle("A{$i}:C{$i}")->getFont()->setBold(true);
                if (in_array($cellValue, ['LABA BERSIH', 'RUGI BERSIH'])) {
                    $sheet->getStyle("A{$i}:C{$i}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $cellValue === 'LABA BERSIH' ? 'C6F6D5' : 'FED7D7'],
                        ],
                        'borders' => [
                            'top' => ['borderStyle' => Border::BORDER_DOUBLE],
                            'bottom' => ['borderStyle' => Border::BORDER_DOUBLE],
                        ],
                    ]);
                }
            }
            if (strpos($cellValue, 'Total') !== false) {
                $sheet->getStyle("A{$i}:C{$i}")->getFont()->setBold(true);
                $sheet->getStyle("A{$i}:C{$i}")->applyFromArray([
                    'borders' => [
                        'top' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);
            }
        }

        return [];
    }
}
