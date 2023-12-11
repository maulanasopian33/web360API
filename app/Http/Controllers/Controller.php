<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
            return response()->json(['errors' => $validator->errors()], 400); // Jika validasi gagal, kembalikan pesan error
        }
        // Pastikan folder ada atau buat jika belum ada
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true); // Buat folder jika belum ada
        }
        $content = $req->content;
        $filePath = $folderPath . '/'.$req->scene.'.json';

        // Tulis konten ke dalam file
        file_put_contents($filePath, $content);

        return response()->json(['message' => 'Scene berhasil di tambahkan']);
    }
    public function upload(Request $req){
        if ($req->hasFile('image')) {
            $image = $req->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(storage_path('images'), $imageName);
            return response()->json(['message' => 'Gambar berhasil diunggah!', 'image_name' => $imageName, 'url' => url('/storage/images/' . $imageName)], 200);
        } else {
            return response()->json(['message' => 'Gambar tidak ditemukan!'], 400);
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
            return response()->json(['files' => $fileList], 200);
        } else {
            return response()->json(['message' => 'Direktori tidak ditemukan'], 404);
        }
    }
    public function get($scene){
        $filePath = storage_path('app/scene/'.$scene.'.json'); // Path lengkap ke file yang ingin Anda baca
        // Cek apakah file ada sebelum membacanya
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            return response()->json([
                'content' => json_decode($content)
            ]);
        } else {
            return response()->json(['message' => 'scene tidak ditemukan.'],404);
        }
    }
    public function delete($scene){
        $filePath = storage_path('app/scene/'.$scene.'.json'); // Path lengkap ke file yang ingin Anda hapus

        // Cek apakah file ada sebelum menghapusnya
        if (file_exists($filePath)) {
            // Menghapus file
            unlink($filePath);

            return response()->json(['message' => 'scene berhasil dihapus'], 200);
        } else {
            return response()->json(['message' => 'scene tidak ditemukan'], 404);
        }
    }
}
