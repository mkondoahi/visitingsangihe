<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class daerah extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'nama_daerah',
        'penjelasan_daerah',
        'gambar',
    ];		

}
