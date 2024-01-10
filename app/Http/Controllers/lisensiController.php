<?php

namespace App\Http\Controllers;

use App\Models\lisensi;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class lisensiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function verifikasi($data){

        $secretKey = 'pucukidea';
        // Membuat token JWT
        try {
            // Mendekode token dengan kunci rahasia
            $decoded = JWT::decode($data, new Key($secretKey, 'HS256'));

            return [
                'status' => true,
                'data'   => $decoded
            ];
        } catch (\Firebase\JWT\ExpiredException $e) {
            return [
                'status' => false,
                'data'   => 'Token tidak valid'
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'data'   => 'Token tidak valid'
            ];
        }
    }
    public function ceklisensi(Request $req){
        $getlisensi = lisensi::orderBy('id','desc')->get();
            if($req->domain === $getlisensi[0]->domain){
                return response()->json([
                    'status' => true,
                    'data'   => 'lisensi valid'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'data'   => 'lisensi tidak valid'
                ]);
            }
    }
    public function updatelisensi(Request $req){
        $lisensi = self::verifikasi($req->lisensi);
        if($lisensi['status']){
            lisensi::create([
                "domain"        => $lisensi['data']->domain,
                "register_by"   => $lisensi['data']->register_by
            ]);
            return response()->json([
                'status' => true,
                'data'   => 'lisensi valid'
            ]);
        }else{
            return response()->json([
                'status' => false,
                'data'   => 'lisensi tidak valid'
            ]);
        }
    }

    //
}
