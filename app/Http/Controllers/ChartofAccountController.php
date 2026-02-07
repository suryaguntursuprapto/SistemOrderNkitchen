<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Kita perlukan ini untuk validasi

class ChartOfAccountController extends Controller
{
    /**
     * 🏁 Menampilkan daftar semua akun.
     */
    public function index()
    {
        // Kita gunakan 'withSum' untuk mendapatkan total debit/kredit
        // langsung dari database. Ini sangat efisien.
        $accounts = ChartOfAccount::with('parent')
                            ->withSum('journalTransactions', 'debit')
                            ->withSum('journalTransactions', 'credit')
                            ->orderBy('code')
                            ->paginate(20); // Anda bisa ganti jumlah paginasi
        
        // Kirim data ke view
        return view('admin.chart_of_accounts.index', compact('accounts'));
    }

    /**
     * 🏁 Menampilkan form untuk membuat akun baru.
     */
    public function create()
    {
        // Ambil semua akun untuk opsi dropdown 'parent'
        $parents = ChartOfAccount::all();
        
        return view('admin.chart_of_accounts.create', compact('parents'));
    }

    /**
     * 💾 Menyimpan akun baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi data
        $validatedData = $request->validate([
            'code' => 'required|string|max:20|unique:chart_of_accounts',
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(['Asset', 'Liability', 'Equity', 'Revenue', 'Expense'])],
            'normal_balance' => ['required', Rule::in(['Debit', 'Credit'])],
            'opening_balance' => 'nullable|numeric|min:0',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
        ]);

        // 2. Buat akun baru
        ChartOfAccount::create($validatedData);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.chart_of_accounts.index')
                         ->with('success', 'Akun baru berhasil ditambahkan.');
    }

    /**
     * 👁️ Menampilkan detail satu akun.
     */
    public function show(ChartOfAccount $chartOfAccount)
    {
        // $chartOfAccount sudah otomatis diambil oleh Laravel (Route-Model Binding)
        return view('admin.chart_of_accounts.show', compact('chartOfAccount'));
    }

    /**
     * ✏️ Menampilkan form untuk mengedit akun.
     */
    public function edit(ChartOfAccount $chartOfAccount)
    {
        // Ambil semua akun untuk opsi dropdown 'parent'
        $parents = ChartOfAccount::all();

        return view('admin.chart_of_accounts.edit', compact('chartOfAccount', 'parents'));
    }

    /**
     * 🔄 Memperbarui data akun di database.
     */
    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        // 1. Validasi data
        $validatedData = $request->validate([
            'code' => [
                'required',
                'string',
                'max:20',
                // Pastikan 'code' unik, KECUALI untuk ID akun ini sendiri
                Rule::unique('chart_of_accounts')->ignore($chartOfAccount->id),
            ],
            'name' => 'required|string|max:255',
            'type' => ['required', Rule::in(['Asset', 'Liability', 'Equity', 'Revenue', 'Expense'])],
            'normal_balance' => ['required', Rule::in(['Debit', 'Credit'])],
            'opening_balance' => 'nullable|numeric|min:0',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'description' => 'nullable|string',
        ]);

        // 2. Update data akun
        $chartOfAccount->update($validatedData);

        // 3. Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.chart_of_accounts.index')
                         ->with('success', 'Data akun berhasil diperbarui.');
    }

    /**
     * 🗑️ Menghapus akun dari database.
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {
        // Hapus akun
        $chartOfAccount->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('admin.chart_of_accounts.index')
                         ->with('success', 'Data akun berhasil dihapus.');
    }
}