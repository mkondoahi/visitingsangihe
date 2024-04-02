<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class gambar_hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_hotel',
        'nama_file',
    ];	

}
