<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'account_id', 'category_id', 'amount', 'description', 'reference_number',
        'date', 'payment_method', 'status', 'approved_by', 'receipt', 'created_by',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function account() { return $this->belongsTo(Account::class, 'account_id'); }
    public function category() { return $this->belongsTo(Category::class, 'category_id'); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
