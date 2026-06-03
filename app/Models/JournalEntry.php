<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalEntry extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'entry_number', 'description', 'date', 'type', 'reference_type',
        'reference_id', 'created_by', 'approved_by', 'status', 'posted_at',
    ];

    protected function casts(): array
    {
        return ['date' => 'date', 'posted_at' => 'datetime'];
    }

    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function items() { return $this->hasMany(JournalEntryItem::class, 'entry_id'); }
}
