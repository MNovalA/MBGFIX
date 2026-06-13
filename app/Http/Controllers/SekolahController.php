<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    // getAllSekolah
    public function index() {
        try {
            return response()->json(Sekolah::all());
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // getSekolahById
    public function show($id) {
        try {
            $sekolah = Sekolah::find($id);
            if (!$sekolah) return response()->json(['message' => "Sekolah tidak ditemukan"], 404);
            return response()->json($sekolah);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // createSekolah
    public function store(Request $request) {
        try {
            // Kita tetap menggunakan create()
            $sekolah = Sekolah::create($request->all());
            return response()->json($sekolah, 201);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 400);
        }
    }

    // updateSekolah
    public function update(Request $request, $id) {
        try {
            $sekolah = Sekolah::find($id);
            if (!$sekolah) return response()->json(['message' => "Sekolah tidak ditemukan"], 404);
            
            $sekolah->update($request->all());
            return response()->json(['message' => "Data sekolah diupdate"]);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // deleteSekolah
    public function destroy($id) {
        try {
            $sekolah = Sekolah::find($id);
            if (!$sekolah) return response()->json(['message' => "Sekolah tidak ditemukan"], 404);
            
            $sekolah->delete();
            return response()->json(['message' => "Sekolah dihapus"]);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }
}