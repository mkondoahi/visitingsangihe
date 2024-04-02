<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\kategori;
use App\Models\wisata;
use App\Models\daerah;
use App\Models\fasilitas;
use App\Models\gambar;
use App\Models\hotel;
use App\Models\gambar_hotel;
use App\Models\pembayaran;

class HomepageController extends Controller
{
    //
	public function index(){
		$fasilitas = fasilitas::all();
		$daerah = daerah::all();
		$kategori = kategori::all();		
		$countdaerah = daerah::count();
		$countwisata = wisata::count();
		$countkategori = kategori::count();
		$sumpengunjung = wisata::sum('pengunjung');
		$menudaerah = daerah::all();
		$menukategori = kategori::all();
		$datadaerah = daerah::all();
		$wisataterbaru = wisata::with('foreign_daerah')->with('foreign_kategori')->orderBy('id','DESC')->limit(8)->get();
		$slide = wisata::with('foreign_daerah')->with('foreign_kategori')->orderBy('id','DESC')->limit(5)->get();
		$wisatadilihat = wisata::with('foreign_daerah')->with('foreign_kategori')->orderBy('pengunjung','DESC')->limit(3)->get();
		$maps = wisata::with('foreign_daerah')->with('foreign_kategori')->get();
		return view('layouts.homepage.index', compact('menukategori','fasilitas','daerah','kategori','sumpengunjung','countdaerah','countwisata','countkategori','menudaerah','slide','wisatadilihat','datadaerah','wisataterbaru','maps'));
		
	}
	
	public function wisata(){
		$menukategori = kategori::all();
		$menudaerah = daerah::all();
		$wisatadilihat = wisata::with('foreign_daerah')->with('foreign_kategori')->get();
		return view('layouts.homepage.wisata', compact('menukategori','menudaerah','wisatadilihat'));	
	}	
	
	public function cariwisata2(Request $request){
		$datacari = $request->input('cari');
		$menukategori = kategori::all();
		$wisatadilihat = wisata::with('foreign_daerah')->with('foreign_kategori')->where('nama_wisata','like',"%".$datacari."%")->get();		
		$menudaerah = daerah::all();
		//$wisatadilihat = wisata::with('foreign_daerah')->with('foreign_kategori')->get();
		return view('layouts.homepage.wisata', compact('menukategori','menudaerah','wisatadilihat'));	
	}
	
	public function maps(){
		$fasilitas = fasilitas::all();
		$menukategori = kategori::all();
		$menudaerah = daerah::all();
		$datadaerah = daerah::all();
		$kategori = kategori::all();
		$maps = wisata::with('foreign_daerah')->with('foreign_kategori')->get();		
		$wisataterbaru = wisata::with('foreign_daerah')->with('foreign_kategori')->orderBy('id','DESC')->limit(8)->get();		
		return view('layouts.homepage.maps', compact('menukategori','menudaerah','maps','wisataterbaru','datadaerah','kategori','fasilitas'));
		
	}
	
	public function paketwisata(){
		$menukategori = kategori::all();
		$menudaerah = daerah::all();		
		return view('layouts.homepage.paketwisata', compact('menukategori','menudaerah'));
		
	}	
	
	public function pembayaran(Request $request){
		$menukategori = kategori::all();
		$menudaerah = daerah::all();	
		$biayaperorang = 50000;
		$datajumlahorang = $request->input('jumlah_orang');
		$nama = $request->input('nama');
		$dataemail = $request->input('email');
		$total = $biayaperorang * $datajumlahorang;
		return view('layouts.homepage.pembayaran', compact('nama','total','menukategori','menudaerah','datajumlahorang','dataemail'));
		
	}	

	public function uploadpembayaran(Request $request){
		
			$request->validate([
				'buktipembayaran' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf|max:2048',
			]);		
			
			$nama_file = $request->buktipembayaran;			
			$filegambar = time().rand(100,999).".".$nama_file->getClientOriginalName();				
		
			$pembayaran = new pembayaran();
			$pembayaran->nama = $request->input('nama');
			$pembayaran->jumlah_orang = $request->input('jumlah_orang');
			$pembayaran->email_aktif = $request->input('email');
			$pembayaran->total = $request->input('total');
			$pembayaran->bukti_pembayaran = $filegambar;
			$pembayaran->status = 'Pending';
			$pembayaran->save();
			
			$nama_file->move(public_path().'/buktipembayaran/', $filegambar);
			
			return redirect()->route('homepage.home')->with('success', 'Berhasil Mengupload Bukti Pembayaran');
	}	
	
	public function detaildaerahmenu($id){
		$menudaerah = daerah::all();
		$daerah = daerah::find($id);	
		$menukategori = kategori::all();
		$wisatadaerah = wisata::with('foreign_daerah')->with('foreign_kategori')->where('daerah_wisata',$id)->get();		
		return view('layouts.homepage.detaildaerah', compact('menukategori','menudaerah','wisatadaerah','daerah'));
	}	
	
	public function detailkategorimenu($id){
		$menudaerah = daerah::all();
		$daerah = daerah::find($id);	
		$menukategori = kategori::all();
		$datakategori = wisata::with('foreign_daerah')->with('foreign_kategori')->where('kategori',$id)->get();		
		$datanamakategori = kategori::find($id);
		return view('layouts.homepage.detailkategori', compact('datanamakategori','menukategori','menudaerah','datakategori','daerah'));
	}	
			
	public function cariwisata(Request $request){
//        $modul = modul::paginate(2);
		$menudaerah = daerah::all();
		$menukategori = kategori::all();
		$datanama_wisata = $request->input('nama_wisata');
		$datadaerah = $request->input('daerah');
		$datakategori = $request->input('kategori');
		$datafasilitas = $request->input('fasilitas');
		$wisata = wisata::with('foreign_daerah')->with('foreign_kategori')->orderBy('id','DESC')->where('nama_wisata','like',"%".$datanama_wisata."%")->where('daerah_wisata',$datadaerah)->where('kategori',$datakategori)->where('fasilitas','like',"%".$datafasilitas."%")->get();
		$datajumlah = wisata::where('nama_wisata','like',"%".$datanama_wisata."%")->where('daerah_wisata',$datadaerah)->where('kategori',$datakategori)->where('fasilitas','like',"%".$datafasilitas."%")->count();		
		$fasilitas = fasilitas::all();
		$daerah = daerah::all();
		$kategori = kategori::all();
		$maps_hasil = wisata::with('foreign_daerah')->with('foreign_kategori')->orderBy('id','DESC')->where('nama_wisata','like',"%".$datanama_wisata."%")->where('daerah_wisata',$datadaerah)->where('kategori',$datakategori)->where('fasilitas','like',"%".$datafasilitas."%")->get();
		$wisataterbaru = wisata::with('foreign_daerah')->with('foreign_kategori')->orderBy('id','DESC')->limit(8)->get();		
		return view('layouts.homepage.searchmaps', compact('menukategori','menudaerah','maps_hasil','wisataterbaru','maps_hasil','daerah','kategori','fasilitas','wisata','datajumlah'));
	}  		
	
	public function detailwisata($id){
		$menudaerah = daerah::all();		
		$menukategori = kategori::all();
		$datagallery = gambar::where('id_wisata', $id)->get();
		$datawisata = wisata::with('foreign_daerah')->with('foreign_kategori')->find($id);
		$datakategori = $datawisata->kategori;
		$wisataterkait = wisata::with('foreign_daerah')->with('foreign_kategori')->where('kategori', $datakategori)->get();
		
		$wisatapengunjung = $datawisata->pengunjung;
		$tambah = 1;
		$hasil = $wisatapengunjung + $tambah;

			$ubh = wisata::findorfail($id);
				$dt = [
					'pengunjung' => $hasil,
				];	
			$ubh->update($dt);			
		
		return view('layouts.homepage.detailwisata', compact('datagallery','menukategori','menudaerah','datawisata','wisataterkait'));
	}

	public function hotel(){
		$menukategori = kategori::all();
		$menudaerah = daerah::all();
		$hotel = hotel::all();
		return view('layouts.homepage.hotel', compact('menukategori','menudaerah','hotel'));	
	}		
	
	public function detailhotel($id){
		
		$menudaerah = daerah::all();		
		$menukategori = kategori::all();
		$datagallery = gambar_hotel::where('id_hotel', $id)->get();
		$datahotel = hotel::find($id);	
		
		return view('layouts.homepage.detailhotel', compact('datagallery','menukategori','menudaerah','datahotel'));
	}
	
}
