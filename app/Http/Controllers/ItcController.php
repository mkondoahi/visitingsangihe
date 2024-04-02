<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//user
use Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Mail;
use App\Mail\sendtiket;

use App\Models\kategori;
use App\Models\gambar;
use App\Models\wisata;
use App\Models\daerah;
use App\Models\fasilitas;
use App\Models\pembayaran;

class ItcController extends Controller
{
    //
	public function Home(){	
	$pembayaran = pembayaran::all();
	return view('layouts.itc.index', compact('pembayaran'));
	}	
	
	public function hapuspembayaran($id){
		$pembayaran = pembayaran::find($id);
		$pembayaran->delete(); 		
		return redirect()->route('itc.home')->with('success', 'Data Berhasil di hapus');
	}	
	
   public function detail($id)
   {
       $pembayaran = pembayaran::find($id);
       return view('layouts.itc.detailpembayaran', compact('pembayaran'));		
   }  	

   public function verifikasi(Request $request, $id)
   {
       $ubh = pembayaran::findorfail($id);
           $dt = [
               'status' => 'Terverifikasi',
           ];	
           $ubh->update($dt);
		   
			$isimail = [
				'title' => 'Tiket Paket Wisata Kepulauan Sangihe',
				'body' => 'Tunjukkan Pesan ini ke pihak wisata untuk menggunakan paket wisata yang telah dibayar',
				'nama' => $ubh->nama,
				'jumlah_orang' => $ubh->jumlah_orang,
				'email_aktif' => $ubh->email_aktif,
				'total' => $ubh->total,
				'created_at' => $ubh->created_at,
				'status' => $ubh->status,
			];		
			
			$tujuan = $ubh->email_aktif;
			Mail::to($tujuan)->send(new sendtiket($isimail));		   
		   
           return redirect()->route('itc.home')->with('success', 'Data Pembayaran Berhasil Diverifikasi');	
   } 
   
}
