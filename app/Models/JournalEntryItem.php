<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalEntryItem extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['entry_id', 'account_id', 'type', 'amount', 'description'];

    public function entry() { return $this->belongsTo(JournalEntry::class, 'entry_id'); }
    public function account() { return $this->belongsTo(Account::class, 'account_id'); }
}
