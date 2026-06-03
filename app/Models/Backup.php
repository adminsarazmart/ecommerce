<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Backup extends BaseModel
{
    use HasFactory;

    protected $table = 'backup_tables';

    public $timestamps = false;

    protected $fillable = ['name', 'file_path', 'size', 'type', 'status'];
}
