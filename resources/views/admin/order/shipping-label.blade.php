@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Print Controls - Hidden on Print -->
    <div class="mb-6 flex justify-between items-center print:hidden">
        <a href="{{ url()->previous() }}" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
        <button onclick="window.print()" 
                class="flex items-center text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105"
                style="background: linear-gradient(to right, #3b82f6, #4f46e5);">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Cetak Label
        </button>
    </div>

    <!-- Shipping Label -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border-2 border-gray-200" id="shipping-label">
        <!-- Header with Logo -->
        <div class="p-4 print-gradient-header" style="background: linear-gradient(to right, #0d9488, #0891b2);">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="N-Kitchen" class="w-12 h-12 rounded-lg shadow-md object-cover">
                    <div class="text-white">
                        <h1 class="text-xl font-bold">N-Kitchen</h1>
                        <p class="text-teal-100 text-sm">Cita Rasa Authentic</p>
                    </div>
                </div>
                <div class="text-right text-white">
                    <p class="text-sm font-medium">No. Pesanan:</p>
                    <p class="text-lg font-bold">{{ $order->order_number }}</p>
                </div>
            </div>
        </div>

        <!-- Courier Info -->
        <div class="px-6 py-3 border-b border-gray-200 print-bg-gray" style="background-color: #f3f4f6;">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        <span class="text-gray-700 font-semibold uppercase">{{ $order->courier ?? 'JNE' }}</span>
                    </div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium" style="background-color: #dbeafe; color: #1e40af;">
                        {{ $order->shipping_service ?? 'Regular' }}
                    </span>
                </div>
                <div class="text-gray-600 text-sm">
                    <span class="font-medium">Berat:</span> {{ number_format($order->total_weight ?? 1000, 0) }} gram
                </div>
            </div>
        </div>

        <!-- Tracking Number / No Resi Section with Barcode -->
        @if($order->biteship_waybill_id || $order->tracking_number)
        <div class="px-6 py-4 border-b-2 border-gray-300" style="background-color: #fef3c7;">
            <div class="text-center mb-3">
                <p class="text-xs font-semibold text-yellow-800 uppercase tracking-wider mb-1">No. Resi / AWB</p>
                <p class="text-2xl font-bold text-gray-900 font-mono tracking-wide">{{ $order->biteship_waybill_id ?? $order->tracking_number }}</p>
            </div>
            <!-- Barcode -->
            <div class="flex justify-center">
                <svg id="barcode-resi"></svg>
            </div>
        </div>
        @endif


        <!-- Addresses Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-200">
            <!-- Sender Info -->
            <div class="p-6">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: #ffedd5;">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pengirim</h3>
                </div>
                <div class="space-y-2">
                    <p class="text-lg font-bold text-gray-900">N-Kitchen</p>
                    <p class="text-gray-700">
                        Cluster Primrose, Jalan Galuh Mas, Puseurjaya,<br>
                        Telukjambe Timur Blok C16, Telukjambe Timur,<br>
                        Kab. Karawang, Jawa Barat<br>
                        41361
                    </p>
                    <div class="flex items-center space-x-2 text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>08123456789</span>
                    </div>
                </div>
            </div>

            <!-- Receiver Info -->
            <div class="p-6 print-bg-yellow" style="background-color: #fefce8;">
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: #dcfce7;">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Penerima</h3>
                </div>
                <div class="space-y-2">
                    <p class="text-xl font-bold text-gray-900">{{ $order->recipient_name ?? $order->user->name }}</p>
                    <p class="text-gray-700">
                        {{ $order->delivery_address }}<br>
                        @if($order->destination_district)
                            {{ $order->destination_district }},
                        @endif
                        {{ $order->destination_city }}<br>
                        @if($order->destination_province)
                            {{ $order->destination_province }}
                        @endif
                        @if($order->destination_postal_code)
                            {{ $order->destination_postal_code }}
                        @endif
                    </p>
                    <div class="flex items-center space-x-2 text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="font-medium">{{ $order->phone }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Summary -->
        <div class="border-t border-gray-200 p-6">
            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Isi Paket</h4>
            <div class="rounded-xl p-4 print-bg-gray" style="background-color: #f9fafb;">
                <div class="space-y-2">
                    @foreach($order->orderItems as $index => $item)
                        <div class="flex justify-between text-gray-700">
                            <span>{{ $index + 1 }}. {{ $item->menu->name }}</span>
                            <span class="font-medium">{{ $item->quantity }} pcs</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between text-gray-900 font-bold">
                    <span>Total Item:</span>
                    <span>{{ $order->orderItems->sum('quantity') }} pcs</span>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        @if($order->notes)
        <div class="border-t border-gray-200 px-6 py-4 print-bg-yellow" style="background-color: #fefce8;">
            <div class="flex items-start space-x-2">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-yellow-800">Catatan:</p>
                    <p class="text-yellow-700">{{ $order->notes }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Footer with Barcode Area -->
        <div class="border-t-2 border-dashed border-gray-300 p-6">
            <div class="flex justify-between items-center">
                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-2">Tanggal Pesanan</p>
                    <p class="font-bold text-gray-900">{{ $order->created_at->format('d M Y') }}</p>
                    <p class="text-sm text-gray-600">{{ $order->created_at->format('H:i') }} WIB</p>
                </div>
                
                <!-- QR Code Placeholder -->
                <div class="text-center">
                    <div class="w-24 h-24 border-2 border-gray-300 rounded-lg flex items-center justify-center print-bg-gray" style="background-color: #f3f4f6;">
                        <svg class="w-16 h-16 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3 3h7v7H3V3zm2 2v3h3V5H5zm9-2h7v7h-7V3zm2 2v3h3V5h-3zM3 14h7v7H3v-7zm2 2v3h3v-3H5zm11-2h1v1h-1v-1zm2 0h3v1h-3v-1zm-2 2h1v1h-1v-1zm2 0h1v1h-1v-1zm2 0h1v1h-1v-1zm-4 2h1v1h-1v-1zm2 0h1v1h-1v-1zm2 0h1v1h-1v-1zm-4 2h1v1h-1v-1zm2 0h3v1h-3v-1z"/>
                        </svg>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">{{ $order->order_number }}</p>
                </div>

                <div class="text-center">
                    <p class="text-xs text-gray-500 mb-2">Total Pembayaran</p>
                    <p class="text-xl font-bold" style="color: #16a34a;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    <span class="inline-block px-2 py-1 text-xs font-medium rounded-full" style="background-color: #dcfce7; color: #166534;">
                        LUNAS
                    </span>
                </div>
            </div>
        </div>

        <!-- Cut Line -->
        <div class="border-t-2 border-dashed border-gray-400 p-2 text-center" style="background-color: #f9fafb;">
            <p class="text-xs text-gray-500">✂️ Potong di garis ini - Simpan sebagai bukti pengiriman ✂️</p>
        </div>
    </div>
</div>

@push('scripts')
<!-- JsBarcode Library for generating barcodes -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate barcode if element exists
    const barcodeElement = document.getElementById('barcode-resi');
    if (barcodeElement) {
        const trackingNumber = "{{ $order->biteship_waybill_id ?? $order->tracking_number ?? '' }}";
        if (trackingNumber) {
            JsBarcode("#barcode-resi", trackingNumber, {
                format: "CODE128",
                width: 2,
                height: 50,
                displayValue: false,
                margin: 5,
                background: "#fef3c7"
            });
        }
    }
});
</script>

<style>
    @media print {
        /* Hide everything except the label */
        body * {
            visibility: hidden;
        }
        #shipping-label, #shipping-label * {
            visibility: visible;
        }
        #shipping-label {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 100%;
            border: 2px solid #000 !important;
            box-shadow: none !important;
        }
        
        /* Enforce background colors for print */
        .print-gradient-header {
            background-color: #0d9488 !important; /* Solid teal fallback */
            background-image: linear-gradient(to right, #0d9488, #0891b2) !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .print-bg-yellow {
            background-color: #fefce8 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .print-bg-gray {
            background-color: #f3f4f6 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        /* Ensure all colors print */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        /* Hide navigation elements */
        nav, .print\\:hidden {
            display: none !important;
        }
        
        /* Page settings */
        @page {
            size: A5 portrait;
            margin: 10mm;
        }
    }
</style>
@endpush
@endsection
