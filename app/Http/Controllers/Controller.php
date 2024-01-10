<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function store(Request $req){

        $folderPath = storage_path('app/scene'); // Path ke folder di dalam direktori penyimpanan (storage) aplikasi Lumen
        // Atur aturan validasi
        $rules = [
            'content' => 'required|string',
            'scene' => 'required|string',
        ];
        // Validasi data
        $validator = app('validator')->make($req->all(), $rules);
        // Cek validasi
        if ($validator->fails()) {
            return response()->json(['status'  => false,'errors' => $validator->errors()]); // Jika validasi gagal, kembalikan pesan error
        }
        // Pastikan folder ada atau buat jika belum ada
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true); // Buat folder jika belum ada
        }
        $content = $req->content;
        $filePath = $folderPath . '/'.$req->scene.'.json';

        // Tulis konten ke dalam file
        file_put_contents($filePath, $content);

        return response()->json([
            'status'  => true,
            'message' => 'Scene berhasil di tambahkan'
        ]);
    }
    public function upload(Request $req){
        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(storage_path('images'), $imageName);
            return response()->json(['status'  => true,'message' => 'Gambar berhasil diunggah!', 'image_name' => $imageName, 'url' => url('/storage/images/' . $imageName)], 200);
        } else {
            return response()->json(['status'  => false,'message' => 'Gambar tidak ditemukan!']);
        }
    }
    public function  getfiles(){
        $directory = storage_path('images'); // Path menuju direktori file di dalam storage

        if (file_exists($directory)) {
            $files = scandir($directory);
            $fileList = [];
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $fileList[] = [
                        "name" => $file,
                        "url"  => url('/storage/images/' . $file)
                    ];
                }
            }
            return response()->json(['status'  => true,'files' => $fileList], 200);
        } else {
            return response()->json(['status'  => false,'message' => 'Direktori tidak ditemukan']);
        }
    }
    public function get($scene){
        $filePath = storage_path('app/scene/'.$scene.'.json'); // Path lengkap ke file yang ingin Anda baca
        // Cek apakah file ada sebelum membacanya
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            return response()->json([
                'status'  => true,
                'content' => json_decode($content)
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'scene tidak ditemukan.'
            ]);
        }
    }
    public function delete($scene){
        $filePath = storage_path('app/scene/'.$scene.'.json'); // Path lengkap ke file yang ingin Anda hapus

        // Cek apakah file ada sebelum menghapusnya
        if (file_exists($filePath)) {
            // Menghapus file
            unlink($filePath);

            return response()->json(['status'  => true,'message' => 'scene berhasil dihapus']);
        } else {
            return response()->json(['status'  => false,'message' => 'scene tidak ditemukan']);
        }
    }

    public function login(Request $req){
        $user = User::where('email', $req->email)->get();
        if(count($user) > 0){
            if(Hash::check($req->password, $user[0]->password)){
                return response()->json([
                    'status' => true,
                    'data'   => 'berhasil login'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data'   => 'password salah'
                ]);
            }
        }else{
            return response()->json([
                'status' => false,
                'data'   => 'username tidak ditemukan'
            ]);
        }
    }
}
