<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//user
use Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\kategori;
use App\Models\gambar;
use App\Models\wisata;
use App\Models\daerah;
use App\Models\hotel;
use App\Models\gambar_hotel;
use App\Models\fasilitas;

class AdminController extends Controller
{
    //
	
	public function Home(){	
	$maps = wisata::with('foreign_daerah')->with('foreign_kategori')->get();
		return view('layouts.admin.home', compact('maps'));
	}
	
	//mulai kategori
	
	public function tampilkategori(){
		$kategori_artikel = kategori::all();
		return view('layouts.admin.tampilkategori', compact('kategori_artikel'));
	}	
	
	public function tambahkategori(){
		return view('layouts.admin.tambahkategori');
	}		
	
	public function prosestambahkategori(Request $request){
			$kategori = new kategori();
			$kategori->nama_kategori = $request->input('nama_kategori');
			$kategori->hastag_kategori = $request->input('hastag_kategori');
			$kategori->save();
			return redirect()->route('kategori.home')->with('success', 'Berhasil Menambah Data');
	}
	
   public function editkategori($id)
   {
       $kategori_artikel = kategori::find($id);
       return view('layouts.admin.editkategori', compact('kategori_artikel'));		
   }  	
	
   public function prosesupdatekategori(Request $request, $id)
   {
       $ubh = kategori::findorfail($id);
           $dt = [
               'nama_kategori' => $request['nama_kategori'],
               'hastag_kategori' => $request['hastag_kategori'],
           ];	
           $ubh->update($dt);
           return redirect()->route('kategori.home')->with('success', 'Data Berhasil di ubah');	
   } 	
   
	public function hapuskategori($id){
		$kategori_artikel = kategori::find($id);
		$kategori_artikel->delete(); 		
		return redirect()->route('kategori.home')->with('success', 'Data Berhasil di hapus');
	}

	// Mulai Daerah
	
	public function tampildaerah(){
		$daerah = daerah::all();
		return view('layouts.admin.tampildaerah', compact('daerah'));
	}	

	public function tambahdaerah(){
		return view('layouts.admin.tambahdaerah');
	}		
	
	public function prosestambahdaerah(Request $request){
		
			$request->validate([
				'gambar' => 'mimes:jpeg,jpg,bmp,png,gif,svg,pdf|max:2048',
			]);		
			
			$nama_file = $request->gambar;			
			$filegambar = time().rand(100,999).".".$nama_file->getClientOriginalName();			
		
			$daerah = new daerah();
			$daerah->nama_daerah = $request->input('nama_daerah');
			$daerah->penjelasan_daerah = $request->input('penjelasan_daerah');
			$daerah->gambar = $filegambar;
			$daerah->save();
			
			$nama_file->move(public_path().'/gambardaerah/', $filegambar);
			return redirect()->route('daerah.home')->with('success', 'Berhasil Menambah Data');
	}
	
   public function editdaerah($id)
   {
       $daerah = daerah::find($id);
       return view('layouts.admin.editdaerah', compact('daerah'));		
   }  	
	
   public function prosesupdatedaerah(Request $request, $id)
   {
       $ubh = daerah::findorfail($id);
	   $data_awal = $ubh->gambar;
		if ($request->gambar == '')
		{	   
           $dt = [
               'nama_daerah' => $request['nama_daerah'],
               'penjelasan_daerah' => $request['penjelasan_daerah'],
           ];	
           $ubh->update($dt);
           return redirect()->route('daerah.home')->with('success', 'Data Berhasil di ubah');	
		}
		else {
		   
			$request->validate([
				'gambar' => 'mimes:jpeg,bmp,png,gif,svg,pdf|max:2048',
			]);				   
		   			
           $dt = [
               'nama_daerah' => $request['nama_daerah'],
               'penjelasan_daerah' => $request['penjelasan_daerah'],
               'gambar' => $data_awal,
           ];	
		   $request->gambar->move(public_path().'/gambardaerah/', $data_awal);
           $ubh->update($dt);
           return redirect()->route('daerah.home')->with('success', 'Data Berhasil di ubah');				
		}
   } 	
   
	public function hapusdaerah($id){
		$daerah = daerah::find($id);
		$daerah->delete(); 		
		return redirect()->route('daerah.home')->with('success', 'Data Berhasil di hapus');
	}	
	
	// Mulai Fasilitas
	
	public function tampilfasilitas(){
		$fasilitas = fasilitas::all();
		return view('layouts.admin.tampilfasilitas', compact('fasilitas'));
	}	
	
	public function tambahfasilitas(){
		return view('layouts.admin.tambahfasilitas');
	}		
	
	public function prosestambahfasilitas(Request $request){
			$fasilitas = new fasilitas();
			$fasilitas->fasilitas = $request->input('fasilitas');
			$fasilitas->save();
			return redirect()->route('fasilitas.home')->with('success', 'Berhasil Menambah Data');
	}
	
   public function editfasilitas($id)
   {
       $fasilitas = fasilitas::find($id);
       return view('layouts.admin.editfasilitas', compact('fasilitas'));		
   }  	
	
   public function prosesupdatefasilitas(Request $request, $id)
   {
       $ubh = fasilitas::findorfail($id);
           $dt = [
               'fasilitas' => $request['fasilitas'],
           ];	
           $ubh->update($dt);
           return redirect()->route('fasilitas.home')->with('success', 'Data Berhasil di ubah');	
   } 	
   
	public function hapusfasilitas($id){
		$fasilitas = fasilitas::find($id);
		$fasilitas->delete(); 		
		return redirect()->route('fasilitas.home')->with('success', 'Data Berhasil di hapus');
	}	
	
	//Mulai Wisata
	
	public function tampilwisata(){
		$wisata = wisata::with('foreign_daerah')->with('foreign_kategori')->get();
		return view('layouts.admin.tampilwisata', compact('wisata'));
	}	

	public function tambahwisata(){
		$fasilitas = fasilitas::all();
		$kategori = kategori::all();
		$daerah = daerah::all();
		$datawisata = wisata::latest()->first();
		$datafix = $datawisata->id + 1;
		return view('layouts.admin.tambahwisata', compact('fasilitas','kategori','daerah','datafix'));
	}		
	
	public function prosestambahwisata(Request $request){
		
			$request->validate([
				'gambar_wisata' => 'mimes:jpg,jpeg,bmp,png,gif,svg,pdf|max:2048',
			]);		
			
			$impfasilitas = implode(', ', $request->input('fasilitas'));			
			
			$nama_file = $request->gambar_wisata;			
			$filegambar = time().rand(100,999).".".$nama_file->getClientOriginalName();			
		
			$wisata = new wisata();
			$wisata->nama_wisata = $request->input('nama_wisata');
			$wisata->alamat_wisata = $request->input('alamat_wisata');
			$wisata->kecamatan_wisata = $request->input('kecamatan_wisata');
			$wisata->daerah_wisata = $request->input('daerah_wisata');
			$wisata->deskripsi_tempat = $request->input('deskripsi_tempat');
			$wisata->biaya_tiket = $request->input('biaya_tiket');
			$wisata->kategori = $request->input('kategori');
			$wisata->longitude = $request->input('longitude');
			$wisata->pengunjung = '1';
			$wisata->latitude = $request->input('latitude');
			$wisata->gambar_wisata = $filegambar;
			$wisata->fasilitas = $impfasilitas;
			$wisata->link_traveloka = $request->input('link_traveloka');			
			$wisata->save();
			
			$nama_file->move(public_path().'/gambarwisata/', $filegambar);
			return redirect()->route('wisata.home')->with('success', 'Berhasil Menambah Data');
	}
	
	public function prosestambahgambar(Request $request){
		
		$dataidterakhir = $request->input('idterakhir');
		
        $image = $request->file('file');
        $imageName = time().rand(1,99).'.'.$image->extension();
        $image->move(public_path('gambarwisata'),$imageName);
		$gambar = new gambar();
		$gambar->id_wisata = $dataidterakhir;
		$gambar->nama_file = $imageName;
		$gambar->save();		
        return response()->json(['success'=>$imageName]);
		
	}
		
   public function editwisata($id)
   {
    //   $daerah = daerah::find($id);
       $wisata = wisata::find($id);
	   $fasilitas = fasilitas::all();
	   $kategori = kategori::all();
	   $daerah = daerah::all();	   
       return view('layouts.admin.editwisata', compact('fasilitas','kategori','daerah','wisata'));		
   }  	
	
   public function prosesupdatewisata(Request $request, $id)
   {
       $ubh = wisata::findorfail($id);
	   $impfasilitas = implode(', ', $request->input('fasilitas'));	
	   $data_awal = $ubh->gambar_wisata;
		if ($request->gambar_wisata == '')
		{	   
           $dt = [
               'nama_wisata' => $request['nama_wisata'],
               'alamat_wisata' => $request['alamat_wisata'],
               'kecamatan_wisata' => $request['kecamatan_wisata'],
               'daerah_wisata' => $request['daerah_wisata'],
               'deskripsi_tempat' => $request['deskripsi_tempat'],
               'biaya_tiket' => $request['biaya_tiket'],
               'kategori' => $request['kategori'],
               'longitude' => $request['longitude'],
               'latitude' => $request['latitude'],
               'fasilitas' => $impfasilitas,
               'link_traveloka' => $request['link_traveloka'],
           ];	
           $ubh->update($dt);
           return redirect()->route('wisata.home')->with('success', 'Data Berhasil di ubah');	
		}
		else {
		   
			$request->validate([
				'gambar_wisata' => 'mimes:jpeg,bmp,png,gif,svg,pdf|max:2048',
			]);				   
		   			
           $dt = [
               'nama_wisata' => $request['nama_wisata'],
               'alamat_wisata' => $request['alamat_wisata'],
               'kecamatan_wisata' => $request['kecamatan_wisata'],
               'daerah_wisata' => $request['daerah_wisata'],
               'deskripsi_tempat' => $request['deskripsi_tempat'],
               'biaya_tiket' => $request['biaya_tiket'],
               'kategori' => $request['kategori'],
               'longitude' => $request['longitude'],
               'latitude' => $request['latitude'],
               'fasilitas' => $impfasilitas,
               'link_traveloka' => $request['link_traveloka'],
               'gambar_wisata' => $data_awal,
           ];	
		   $request->gambar_wisata->move(public_path().'/gambarwisata/', $data_awal);
           $ubh->update($dt);
           return redirect()->route('wisata.home')->with('success', 'Data Berhasil di ubah');				
		}
   } 	
   
	public function hapuswisata($id){
		$wisata = wisata::find($id);
		$wisata->delete(); 		
		return redirect()->route('wisata.home')->with('success', 'Data Berhasil di hapus');
	}		
	
		public function tampilhotel(){
			$hotel = hotel::all();
			return view('layouts.admin.tampilhotel', compact('hotel'));
		}	
	
		public function tambahhotel(){
			$datahotel = hotel::latest()->first();
			$datafix = $datahotel->id + 1;
			return view('layouts.admin.tambahhotel', compact('datafix'));
		}		
		
		public function prosestambahhotel(Request $request){
			
				$request->validate([
					'gambar_hotel' => 'mimes:jpg,jpeg,bmp,png,gif,svg,pdf|max:2048',
				]);				
				
				$nama_file = $request->gambar_hotel;			
				$filegambar = time().rand(100,999).".".$nama_file->getClientOriginalName();			
			
				$hotel = new hotel();
				$hotel->nama_hotel = $request->input('nama_hotel');
				$hotel->alamat_hotel = $request->input('alamat_hotel');
				$hotel->deskripsi = $request->input('deskripsi');
				$hotel->harga = $request->input('harga');
				$hotel->longitude = $request->input('longitude');
				$hotel->latitude = $request->input('latitude');
				$hotel->gambar_hotel = $filegambar;			
				$hotel->save();
				
				$nama_file->move(public_path().'/gambarhotel/', $filegambar);
				return redirect()->route('hotel.home')->with('success', 'Berhasil Menambah Data');
		}
		
		public function prosestambahgambarhotel(Request $request){
			
			$dataidterakhir = $request->input('idterakhir');
			
			$image = $request->file('file');
			$imageName = time().rand(1,99).'.'.$image->extension();
			$image->move(public_path('gambarhotel'),$imageName);
			$gambar_hotel = new gambar_hotel();
			$gambar_hotel->id_hotel = $dataidterakhir;
			$gambar_hotel->nama_file = $imageName;
			$gambar_hotel->save();		
			return response()->json(['success'=>$imageName]);
			
		}
			
	   public function edithotel($id)
	   {
		//   $daerah = daerah::find($id);
		   $hotel = hotel::find($id);   
		   return view('layouts.admin.edithotel', compact('hotel'));		
	   }  	
		
	   public function prosesupdatehotel(Request $request, $id)
	   {
		   $ubh = hotel::findorfail($id);
		   $data_awal = $ubh->gambar_hotel;
			if ($request->gambar_hotel == '')
			{	   
			   $dt = [
				   'nama_hotel' => $request['nama_hotel'],
				   'alamat_hotel' => $request['alamat_hotel'],
				   'deskripsi' => $request['deskripsi'],
				   'harga' => $request['harga'],
				   'longitude' => $request['longitude'],
				   'latitude' => $request['latitude'],
			   ];	
			   $ubh->update($dt);
			   return redirect()->route('hotel.home')->with('success', 'Data Berhasil di ubah');	
			}
			else {
			   
				$request->validate([
					'gambar_hotel' => 'mimes:jpeg,bmp,png,gif,svg,pdf|max:2048',
				]);				   
						   
			   $dt = [
					'nama_hotel' => $request['nama_hotel'],
					'alamat_hotel' => $request['alamat_hotel'],
					'deskripsi' => $request['deskripsi'],
					'harga' => $request['harga'],
					'longitude' => $request['longitude'],
					'latitude' => $request['latitude'],
				    'gambar_wisata' => $data_awal,
			   ];	
			   $request->gambar_wisata->move(public_path().'/gambarhotel/', $data_awal);
			   $ubh->update($dt);
			   return redirect()->route('hotel.home')->with('success', 'Data Berhasil di ubah');				
			}
	   } 	
	   
		public function hapushotel($id){
			$hotel = hotel::find($id);
			$hotel->delete(); 		
			return redirect()->route('hotel.home')->with('success', 'Data Berhasil di hapus');
		}		
	
}
