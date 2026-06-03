<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'type', 'parent_id', 'description', 'is_active', 'balance',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function parent() { return $this->belongsTo(Account::class, 'parent_id'); }
    public function children() { return $this->hasMany(Account::class, 'parent_id'); }
    public function journalEntryItems() { return $this->hasMany(JournalEntryItem::class, 'account_id'); }
    public function expenses() { return $this->hasMany(Expense::class, 'account_id'); }
}
