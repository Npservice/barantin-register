<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterNegara extends Model
{
    use HasFactory;
    protected $fillable = ['kode', 'nama', 'nama_en'];

}
