<?php

namespace App\Http\Controllers;

use App\Models\Dapur;
use Illuminate\Http\Request;

class DapurController extends Controller
{
    // getAllDapur
    public function index() {
        try {
            $data = Dapur::all();
            return response()->json($data);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // getDapurById
    public function show($id) {
        try {
            $data = Dapur::find($id);
            if ($data) {
                return response()->json($data);
            }
            return response()->json(['message' => 'Dapur tidak ditemukan'], 404);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // createDapur
    public function store(Request $request) {
        try {
            $data = Dapur::create($request->all());
            return response()->json($data, 201);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 400);
        }
    }

    // updateDapur
    public function update(Request $request, $id) {
        try {
            $dapur = Dapur::find($id);
            if ($dapur) {
                $dapur->update($request->all());
                return response()->json(['message' => 'Dapur berhasil diupdate']);
            }
            return response()->json(['message' => 'Dapur tidak ditemukan'], 404);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 400);
        }
    }

    // deleteDapur
    public function destroy($id) {
        try {
            $dapur = Dapur::find($id);
            if ($dapur) {
                $dapur->delete();
                return response()->json(['message' => 'Dapur berhasil dihapus']);
            }
            return response()->json(['message' => 'Dapur tidak ditemukan'], 404);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }
}