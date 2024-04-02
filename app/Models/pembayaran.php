<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'nama',
        'jumlah_orang',
        'email_aktif',
        'total',
        'bukti_pembayaran',
        'status',
    ];		
	
}
