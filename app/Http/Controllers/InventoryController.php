<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    // getAllInventory
    public function index() {
        try {

            $inventories = Inventory::with('dapur')->get(); 
            return response()->json($inventories);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // getInventoryByDapur
    public function getByDapur($id_dapur) {
        try {
            $data = Inventory::where('id_dapur', $id_dapur)->get();
            return response()->json($data);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // getInventoryById
    public function show($id) {
        try {
            $item = Inventory::find($id);
            if (!$item) return response()->json(['message' => 'Data tidak ditemukan'], 404);
            return response()->json($item);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // reduceBulkStock
    public function reduceBulkStock(Request $request) {
        try {
            $id_dapur = $request->id_dapur;
            $bahan = $request->bahan;

            // Menggunakan database transaction agar aman
            DB::transaction(function () use ($id_dapur, $bahan) {
                foreach ($bahan as $item) {
                    $inv = Inventory::where('id_inventory', $item['id_inventory'])
                                    ->where('id_dapur', $id_dapur)
                                    ->first();
                    
                    if ($inv) {
                        $inv->stok -= (float)$item['total_kurangi'];
                        $inv->save();
                    }
                }
            });

            return response()->json(['message' => "Stok berhasil diperbarui"]);
        } catch (\Exception $err) {
            return response()->json(['message' => "Gagal update stok"], 500);
        }
    }

    // createInventory
    public function store(Request $request) {
        try {
            return response()->json(Inventory::create($request->all()), 201);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 400);
        }
    }

    // updateInventory
    public function update(Request $request, $id) {
        try {
            $item = Inventory::find($id);
            if (!$item) return response()->json(['message' => 'Data tidak ditemukan'], 404);
            
            $item->update($request->all());
            return response()->json(['message' => 'Stok diupdate']);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 400);
        }
    }

    // deleteInventory
    public function destroy($id) {
        try {
            $item = Inventory::find($id);
            if (!$item) return response()->json(['message' => 'Data tidak ditemukan'], 404);
            
            $item->delete();
            return response()->json(['message' => 'Bahan baku dihapus']);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }
}
