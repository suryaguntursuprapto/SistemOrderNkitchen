<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\ChartOfAccount;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    protected $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    /**
     * Menampilkan daftar pembelian.
     */
    public function index()
    {
        $purchases = Purchase::latest()->paginate(10);
        return view('admin.purchases.index', compact('purchases'));
    }

    /**
     * Menampilkan form untuk membuat pembelian baru.
     */
    public function create()
    {
        // Ambil akun beban yang merupakan sub-kategori dari "Beban Bahan Baku"
        // untuk pemilihan jenis pembelian (Pembelian Tepung, Pembelian Ikan, dll)
        $purchaseAccounts = ChartOfAccount::where('type', 'Expense')
            ->whereHas('parent', function ($query) {
                $query->where('name', 'like', '%Bahan Baku%');
            })
            ->orderBy('code')
            ->get();
        
        return view('admin.purchases.create', compact('purchaseAccounts'));
    }

    /**
     * Menyimpan pembelian baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'supplier_name' => 'nullable|string|max:255',
            'invoice_number' => 'nullable|string|max:255',
            'chart_of_account_id' => 'nullable|exists:chart_of_accounts,id',
            'status' => 'required|in:paid,unpaid',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit' => 'required|string|max:50',
            'items.*.price_per_unit' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                
                // 2. Hitung Total dan Subtotal
                $totalAmount = 0;
                $itemDetails = [];
                
                foreach ($validated['items'] as $item) {
                    $subtotal = $item['quantity'] * $item['price_per_unit'];
                    $totalAmount += $subtotal;
                    $itemDetails[] = [
                        'item_name' => $item['item_name'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'],
                        'price_per_unit' => $item['price_per_unit'],
                        'subtotal' => $subtotal,
                    ];
                }

                // 3. Buat Kepala Nota (Purchase)
                $purchase = Purchase::create([
                    'purchase_date' => $validated['purchase_date'],
                    'supplier_name' => $validated['supplier_name'],
                    'invoice_number' => $validated['invoice_number'],
                    'chart_of_account_id' => $validated['chart_of_account_id'] ?? null,
                    'status' => $validated['status'],
                    'notes' => $validated['notes'],
                    'total_amount' => $totalAmount, // Total yang sudah dihitung
                ]);

                // 4. Buat Detail Nota (PurchaseDetail)
                $purchase->purchaseDetails()->createMany($itemDetails);

                // 5. Buat Jurnal Akuntansi
                $this->accountingService->recordPurchase($purchase);
            });
        } catch (\Exception $e) {
            // Jika terjadi error (termasuk error dari AccountingService)
            return back()->with('error', 'Gagal menyimpan pembelian: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('admin.purchases.index')->with('success', 'Pembelian berhasil dicatat.');
    }

    /**
     * Menampilkan detail satu pembelian.
     */
    public function show(Purchase $purchase)
    {
        $purchase->load('purchaseDetails');
        return view('admin.purchases.show', compact('purchase'));
    }

    /**
     * Menampilkan form edit (Belum diimplementasikan penuh).
     */
    public function edit(Purchase $purchase)
    {
        // Note: Mengedit pembelian yang sudah dijurnal itu rumit.
        // Sebaiknya dihapus dan dibuat baru.
        return redirect()->route('admin.purchases.show', $purchase)->with('info', 'Fitur edit belum tersedia. Hapus dan buat baru jika ada kesalahan.');
    }

    /**
     * Update (Belum diimplementasikan).
     */
    public function update(Request $request, Purchase $purchase)
    {
        return redirect()->route('admin.purchases.show', $purchase)->with('info', 'Fitur update belum tersedia.');
    }

    /**
     * Menghapus pembelian.
     */
    public function destroy(Purchase $purchase)
    {
        try {
            DB::transaction(function () use ($purchase) {
                // 1. Hapus Jurnal terkait
                // Kita perlu trigger Jurnal Balik, tapi untuk sekarang hapus langsung
                $purchase->journal()->delete();
                
                // 2. Hapus Pembelian (Detail akan terhapus otomatis by cascade)
                $purchase->delete();
            });
        } catch (\Exception $e) {
             return back()->with('error', 'Gagal menghapus pembelian: ' . $e->getMessage());
        }

        return redirect()->route('admin.purchases.index')->with('success', 'Pembelian berhasil dihapus.');
    }
}