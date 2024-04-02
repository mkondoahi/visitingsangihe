<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_hotel',
        'alamat_hotel',
        'deskripsi',
        'harga',
        'longitude',
        'latitude',
        'gambar_hotel',
    ];	

}
