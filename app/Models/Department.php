<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends BaseModel
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'manager_id', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function manager() { return $this->belongsTo(Employee::class, 'manager_id'); }
    public function employees() { return $this->hasMany(Employee::class); }
}
