<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    /**
     * Atribut yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'purchase_date',
        'supplier_name',
        'invoice_number',
        'total_amount',
        'status',
        'notes',
        'chart_of_account_id',
    ];

    /**
     * Tipe data bawaan.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Relasi ke detail item pembelian.
     */
    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    /**
     * Relasi ke Jurnal (Polymorphic).
     * Ini akan menyambungkan pembelian ke ChartOfAccount.
     */
    public function journal()
    {
        return $this->morphOne(Journal::class, 'referenceable');
    }

    /**
     * Relasi ke Chart of Account (kategori pembelian).
     */
    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class);
    }
}