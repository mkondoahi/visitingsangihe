<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wisata extends Model
{
    use HasFactory;
	
    protected $fillable = [
        'nama_wisata',
        'alamat_wisata',
        'kecamatan_wisata',
        'daerah_wisata',
        'biaya_tiket',
        'kategori',
        'longitude',
        'latitude',
        'gambar_wisata',
        'fasilitas',
        'link_traveloka',
        'pengunjung',
    ];		
	
    public function foreign_kategori()
    {
        return $this->belongsTo(kategori::class, 'kategori');
    }
    public function foreign_daerah()
    {
        return $this->belongsTo(daerah::class, 'daerah_wisata');
    } 	
	
}
