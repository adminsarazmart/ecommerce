<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionAnswer extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'customer_id', 'question', 'answer', 'answered_by',
        'is_answered', 'is_approved',
    ];

    protected function casts(): array
    {
        return ['is_answered' => 'boolean', 'is_approved' => 'boolean'];
    }

    public function product() { return $this->belongsTo(Product::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function answeredBy() { return $this->belongsTo(User::class, 'answered_by'); }
}
