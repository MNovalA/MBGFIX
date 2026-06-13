<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    // Menggantikan getAllShipments (tanpa perlu axios!)
    public function index() {
        try {
            // Kita gunakan 'with' untuk memanggil relasi ke Sekolah, Dapur, dan Menu
            $shipments = Shipment::with(['sekolah', 'dapur', 'menu'])->get();

            // Kita format respon agar mirip dengan struktur data Anda sebelumnya
            $detailedShipments = $shipments->map(function ($s) {
                return [
                    'id_shipment'   => $s->id_shipment,
                    'id_sekolah'    => $s->id_sekolah,
                    'id_dapur'      => $s->id_dapur,
                    'id_menu'       => $s->id_menu,
                    'jumlah_porsi'  => $s->jumlah_porsi,
                    'status_kirim'  => $s->status_kirim,
                    'waktu_sampai'  => $s->waktu_sampai,
                    'nama_sekolah'  => $s->sekolah->nama_sekolah ?? "N/A",
                    'nama_dapur'    => $s->dapur->nama_dapur ?? "N/A",
                    'nama_menu'     => $s->menu->nama_paket ?? "N/A",
                ];
            });

            return response()->json($detailedShipments);
        } catch (\Exception $error) {
            return response()->json(['message' => $error->getMessage()], 500);
        }
    }

    // createShipment
    public function store(Request $request) {
        try {
            $shipment = Shipment::create([
                'id_sekolah'   => $request->id_sekolah,
                'id_menu'      => $request->id_menu,
                'id_dapur'     => $request->id_dapur,
                'jumlah_porsi' => $request->jumlah_porsi,
                'status_kirim' => $request->status ?? 'Persiapan',
                'waktu_sampai' => $request->waktu_sampai ?? null,
            ]);
            return response()->json($shipment, 201);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // updateShipment
    public function update(Request $request, $id) {
        try {
            $shipment = Shipment::find($id);
            if (!$shipment) return response()->json(['message' => 'Data tidak ditemukan'], 404);

            $updateData = [
                'id_sekolah'   => $request->id_sekolah,
                'id_menu'      => $request->id_menu,
                'id_dapur'     => $request->id_dapur,
                'jumlah_porsi' => $request->jumlah_porsi,
                'status_kirim' => $request->status,
                'waktu_sampai' => ($request->status === 'Diterima') ? now() : ($request->waktu_sampai ?? null),
            ];

            $shipment->update($updateData);
            return response()->json(['message' => "Pengiriman berhasil diperbarui"]);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // getShipmentById
    public function show($id) {
        $shipment = Shipment::find($id);
        if (!$shipment) return response()->json(['message' => 'Data tidak ditemukan'], 404);
        return response()->json($shipment);
    }

    // deleteShipment
    public function destroy($id) {
        $shipment = Shipment::find($id);
        if (!$shipment) return response()->json(['message' => 'Data tidak ditemukan'], 404);
        
        $shipment->delete();
        return response()->json(['message' => "Data pengiriman dihapus"]);
    }
}