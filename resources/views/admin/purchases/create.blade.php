@extends('layouts.report')

@section('report_content')

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Catat Pembelian Baru</h1>
        <a href="{{ route('admin.purchases.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-xl font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition ease-in-out duration-150">
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
            <p class="font-bold">Oops! Ada kesalahan:</p>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.purchases.store') }}" method="POST">
        @csrf
        
        <div x-data="purchaseForm()" x-init="init()">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200 mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Nota</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="purchase_date" class="block text-sm font-medium text-gray-700">Tanggal Pembelian</label>
                            <input type="date" name="purchase_date" id="purchase_date" 
                                   value="{{ old('purchase_date', date('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                        </div>
                        <div>
                            <label for="chart_of_account_id" class="block text-sm font-medium text-gray-700">Kategori Pembelian</label>
                            <select name="chart_of_account_id" id="chart_of_account_id"
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($purchaseAccounts as $account)
                                <option value="{{ $account->id }}" {{ old('chart_of_account_id') == $account->id ? 'selected' : '' }}>
                                    [{{ $account->code }}] {{ $account->name }}
                                </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Contoh: Pembelian Ikan, Pembelian Tepung, dll. Tambahkan di menu Bagan Akun.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="supplier_name" class="block text-sm font-medium text-gray-700">Nama Pemasok (Opsional)</label>
                            <input type="text" name="supplier_name" id="supplier_name" 
                                   value="{{ old('supplier_name') }}"
                                   placeholder="Contoh: PT. Sinar Ikan"
                                   class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                        </div>
                        <div>
                            <label for="invoice_number" class="block text-sm font-medium text-gray-700">No. Faktur (Opsional)</label>
                            <input type="text" name="invoice_number" id="invoice_number" 
                                   value="{{ old('invoice_number') }}"
                                   placeholder="Contoh: INV/2025/XI/123"
                                   class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Item yang Dibeli</h3>
                    
                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="grid grid-cols-12 gap-x-4 gap-y-2 p-4 border rounded-lg">
                                <div class="col-span-12 md:col-span-4">
                                    <label class="block text-sm font-medium text-gray-700">Nama Item</label>
                                    <input type="text" :name="`items[${index}][item_name]`" x-model="item.item_name"
                                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                           placeholder="Ikan Tenggiri Giling">
                                </div>
                                <div class="col-span-4 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Qty</label>
                                    <input type="number" :name="`items[${index}][quantity]`" x-model.number="item.quantity" @input="calculateTotal()"
                                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                           placeholder="10" step="0.01">
                                </div>
                                <div class="col-span-4 md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Satuan</label>
                                    <input type="text" :name="`items[${index}][unit]`" x-model="item.unit"
                                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                           placeholder="kg">
                                </div>
                                <div class="col-span-4 md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700">Harga Satuan (Rp)</label>
                                    <input type="number" :name="`items[${index}][price_per_unit]`" x-model.number="item.price_per_unit" @input="calculateTotal()"
                                           class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                           placeholder="60000" step="any">
                                </div>
                                <div class="col-span-12 md:col-span-1 flex items-end">
                                    <button type="button" @click="removeItem(index)" 
                                            class="mt-1 text-red-600 hover:text-red-800 transition-colors p-2"
                                            title="Hapus item" x-show="items.length > 1">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button type="button" @click="addItem()"
                            class="mt-4 inline-flex items-center px-3 py-2 bg-blue-100 border border-transparent rounded-xl font-semibold text-xs text-blue-700 uppercase tracking-widest hover:bg-blue-200 active:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Item
                    </button>
                </div>

                <div class="bg-gray-50 px-6 py-4">
                    <div class="flex justify-end items-center">
                        <span class="text-lg font-semibold text-gray-700">Total Pembelian:</span>
                        <span class="text-xl font-bold text-gray-900 ml-4" x-text="formatCurrency(totalAmount)">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-200 mt-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status Pembayaran</label>
                            <select name="status" id="status" 
                                    class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Hutang (Unpaid)</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Lunas (Paid)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Pilih 'Hutang' jika belum dibayar, 'Lunas' jika dibayar tunai/transfer langsung.</p>
                        </div>
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                      placeholder="Contoh: Jatuh tempo 30 hari...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 text-right">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 custom-gradient text-white rounded-xl text-sm font-medium transition-all duration-300 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                            style="background: linear-gradient(to right, #f97316, #ea580c);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Pembelian
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        function purchaseForm() {
            return {
                items: [],
                totalAmount: 0,
                init() {
                    this.items.push({ item_name: '', quantity: 1, unit: 'kg', price_per_unit: 0 });
                    this.calculateTotal();
                },
                addItem() {
                    this.items.push({ item_name: '', quantity: 1, unit: 'kg', price_per_unit: 0 });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                        this.calculateTotal();
                    }
                },
                calculateTotal() {
                    let total = 0;
                    this.items.forEach(item => {
                        total += (parseFloat(item.quantity) || 0) * (parseFloat(item.price_per_unit) || 0);
                    });
                    this.totalAmount = total;
                },
                formatCurrency(amount) {
                    return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
                }
            }
        }
    </script>
@endpush