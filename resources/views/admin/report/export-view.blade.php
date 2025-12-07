<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan - N-Kitchen</title>
    <style>
        /* Reset and Base Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            font-size: 11pt;
            color: #333;
            line-height: 1.4;
        }
        
        /* Header Section */
        .company-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #0891b2;
        }
        .company-name {
            font-size: 22pt;
            font-weight: bold;
            color: #0891b2;
            margin-bottom: 3px;
        }
        .company-tagline {
            font-size: 10pt;
            color: #666;
        }
        .report-title {
            font-size: 16pt;
            font-weight: bold;
            color: #333;
            margin-top: 15px;
        }
        .report-period {
            font-size: 10pt;
            color: #666;
            margin-top: 5px;
        }
        
        /* Summary Section */
        .summary-section {
            margin-bottom: 25px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .summary-table td {
            padding: 10px 15px;
            border: 1px solid #ddd;
        }
        .summary-table .label {
            width: 70%;
            font-weight: 500;
        }
        .summary-table .value {
            width: 30%;
            text-align: right;
            font-weight: bold;
        }
        .summary-table .sales-row td { background-color: #ecfdf5; color: #059669; }
        .summary-table .expense-row td { background-color: #fef2f2; color: #dc2626; }
        .summary-table .profit-row td { background-color: #059669; color: #fff; font-size: 12pt; }
        .summary-table .loss-row td { background-color: #dc2626; color: #fff; font-size: 12pt; }
        
        /* Section Headers */
        .section-header {
            background: linear-gradient(135deg, #0891b2, #06b6d4);
            color: #fff;
            padding: 10px 15px;
            font-size: 12pt;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 0;
        }
        .section-header.green { background: linear-gradient(135deg, #059669, #10b981); }
        .section-header.red { background: linear-gradient(135deg, #dc2626, #ef4444); }
        
        /* Data Tables */
        table.data-table { 
            width: 100%; 
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, 
        table.data-table td { 
            border: 1px solid #ddd; 
            padding: 8px 12px; 
        }
        table.data-table th { 
            background-color: #f8fafc;
            font-weight: 600;
            text-align: left;
            color: #374151;
            font-size: 10pt;
        }
        table.data-table td { 
            font-size: 10pt;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #fafafa;
        }
        table.data-table tr:hover td {
            background-color: #f0f9ff;
        }
        
        /* Alignment */
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Total Row */
        .total-row td {
            background-color: #f1f5f9 !important;
            font-weight: bold;
            border-top: 2px solid #0891b2;
        }
        .total-row.green td { border-top-color: #059669; }
        .total-row.red td { border-top-color: #dc2626; }
        
        /* Status Badges */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 9pt;
            font-weight: 500;
        }
        .badge-green { background-color: #d1fae5; color: #059669; }
        .badge-red { background-color: #fee2e2; color: #dc2626; }
        .badge-blue { background-color: #dbeafe; color: #2563eb; }
        .badge-yellow { background-color: #fef3c7; color: #d97706; }
        
        /* Footer */
        .report-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
        
        /* Print Styles */
        @media print {
            body { font-size: 10pt; }
            .no-print { display: none; }
            .section-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .summary-table td { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>
    <!-- Company Header -->
    <div class="company-header">
        <div class="company-name">N-Kitchen</div>
        <div class="company-tagline">Cita Rasa Authentic</div>
        <div class="report-title">LAPORAN RINGKASAN KEUANGAN</div>
        <div class="report-period">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <table class="summary-table">
            <tbody>
                <tr class="sales-row">
                    <td class="label">💰 Total Penjualan (Revenue)</td>
                    <td class="value">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</td>
                </tr>
                <tr class="expense-row">
                    <td class="label">💸 Total Biaya & Pembelian (Costs)</td>
                    <td class="value">Rp {{ number_format($summary['total_expenses'], 0, ',', '.') }}</td>
                </tr>
                <tr class="{{ $summary['profit'] >= 0 ? 'profit-row' : 'loss-row' }}">
                    <td class="label">{{ $summary['profit'] >= 0 ? '📈 LABA BERSIH' : '📉 RUGI BERSIH' }}</td>
                    <td class="value">Rp {{ number_format(abs($summary['profit']), 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Sales Detail Section -->
    <div class="section-header green">📊 DETAIL PENJUALAN</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Order ID</th>
                <th style="width: 20%;">Customer</th>
                <th style="width: 15%;" class="text-center">Status</th>
                <th style="width: 25%;" class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salesData as $order)
                <tr>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td><strong>{{ $order->order_number }}</strong></td>
                    <td>{{ $order->recipient_name ?? $order->user->name ?? '-' }}</td>
                    <td class="text-center">
                        <span class="badge badge-green">{{ $order->status_label }}</span>
                    </td>
                    <td class="text-right"><strong>{{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #666;">Tidak ada data penjualan pada periode ini.</td>
                </tr>
            @endforelse
            @if(count($salesData) > 0)
            <tr class="total-row green">
                <td colspan="4" class="text-right">TOTAL PENJUALAN</td>
                <td class="text-right">{{ number_format($summary['total_sales'], 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Costs Detail Section -->
    <div class="section-header red">📋 DETAIL BIAYA & PEMBELIAN</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 35%;">Deskripsi</th>
                <th style="width: 20%;" class="text-center">Kategori</th>
                <th style="width: 10%;" class="text-center">Tipe</th>
                <th style="width: 20%;" class="text-right">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($combinedCosts as $item)
                <tr>
                    <td>{{ $item->date->format('d M Y') }}</td>
                    <td>{{ $item->description }}</td>
                    <td class="text-center">
                        <span class="badge {{ $item->type == 'expense' ? 'badge-red' : 'badge-blue' }}">
                            {{ $item->category }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $item->type == 'expense' ? 'badge-yellow' : 'badge-blue' }}">
                            {{ $item->type == 'expense' ? 'Biaya' : 'Pembelian' }}
                        </span>
                    </td>
                    <td class="text-right"><strong>{{ number_format($item->amount, 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #666;">Tidak ada data biaya atau pembelian pada periode ini.</td>
                </tr>
            @endforelse
            @if(count($combinedCosts) > 0)
            <tr class="total-row red">
                <td colspan="4" class="text-right">TOTAL BIAYA & PEMBELIAN</td>
                <td class="text-right">{{ number_format($summary['total_expenses'], 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <!-- Footer -->
    <div class="report-footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem N-Kitchen</p>
        <p>Dicetak pada: {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>
</body>
</html>