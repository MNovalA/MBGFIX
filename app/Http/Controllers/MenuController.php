<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuRecipe;
use App\Models\Inventory;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{

    public function index() {
        $menus = Menu::with(['dapur', 'recipes.inventory'])->get();
        return response()->json($menus);
    }

    public function show($id) {
        try {
            // 💡 PERBAIKAN: Ubah 'resep' menjadi 'recipes'
            $menu = Menu::with(['dapur', 'recipes'])->find($id);
            if (!$menu) return response()->json(['message' => 'Menu tidak ditemukan'], 404);
            return response()->json($menu);
        } catch (\Exception $err) {
            return response()->json(['message' => $err->getMessage()], 500);
        }
    }

    // createMenu (Laravel bisa menyimpan relasi dalam satu langkah)
    public function store(Request $request)
    {
        // 1. Validasi data utama menu dan array resepnya
        $request->validate([
            'id_dapur' => 'required',
            'nama_paket' => 'required|string',
            'deskripsi' => 'nullable|string',
            'recipes' => 'required|array', // Pastikan resep dikirim sebagai array
        ]);

        // 2. Simpan data menu ke tabel 'menus'
        $menu = Menu::create([
            'id_dapur' => $request->id_dapur,
            'nama_paket' => $request->nama_paket,
            'deskripsi' => $request->deskripsi,
        ]);

        // 3. SELESAIKAN DISINI: Looping untuk simpan bahan ke tabel 'menu_recipes'
        if ($request->has('recipes') && is_array($request->recipes)) {
            foreach ($request->recipes as $item) {
                // Memanggil relasi 'recipes' yang ada di model Menu
                $menu->recipes()->create([
                    'id_inventory'     => $item['id_inventory'],     // ID bahan baku
                    'jumlah_kebutuhan' => $item['jumlah_kebutuhan'], // Jumlah (Jml)
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Menu dan resep berhasil disimpan!',
            'data' => $menu->load('recipes')
        ], 201);
    }

    // getMenuKebutuhan
    public function getMenuKebutuhan($id, $id_sekolah) {
        try {
            $menu = Menu::find($id);
            $sekolah = Sekolah::find($id_sekolah);
            
            if (!$menu || !$sekolah) return response()->json(['message' => "Data tidak ditemukan"], 404);

            return response()->json([
                'id_menu' => $menu->id_menu,
                'nama_menu' => $menu->nama_paket,
                'sekolah' => $sekolah->nama_sekolah,
                'total_porsi_target' => $sekolah->jumlah_siswa,
                'status' => "Data sinkron dengan Database Lokal"
            ]);
        } catch (\Exception $err) {
            return response()->json(['message' => "Gagal sinkronisasi data", 'error' => $err->getMessage()], 500);
        }
    }

    // processProduction (Logika stok langsung diakses via Database lokal)
    public function processProduction(Request $request) {
        try {
            $menu = Menu::with('recipes')->find($request->id_menu);
            if (!$menu) return response()->json(['message' => "Menu tidak ditemukan"], 404);

            DB::transaction(function () use ($menu, $request) {
                foreach ($menu->recipes as $recipe) {
                    $inventory = Inventory::where('id_inventory', $recipe->id_inventory)
                                          ->where('id_dapur', $request->id_dapur)
                                          ->first();
                    if ($inventory) {
                        $inventory->stok -= ($recipe->jumlah_kebutuhan * $request->jumlah_porsi);
                        $inventory->save();
                    }
                }
            });

            return response()->json(['message' => "Produksi berhasil! Stok di Inventory telah terupdate."]);
        } catch (\Exception $err) {
            return response()->json(['message' => "Proses produksi gagal.", 'error' => $err->getMessage()], 500);
        }
    }

    public function update(Request $request, $id) 
    {
        // 1. Validasi input terlebih dahulu
        $request->validate([
            'id_dapur' => 'required',
            'nama_paket' => 'required|string',
            'deskripsi' => 'nullable|string',
            'recipes' => 'required|array', // Harus membawa array resep baru
        ]);

        try {
            // Gunakan DB Transaction agar jika resep gagal disimpan, update menu otomatis dibatalkan
            $result = DB::transaction(function () use ($request, $id) {
                
                // Cari data menu, jika tidak ada langsung lempar error 404 otomatis
                $menu = Menu::findOrFail($id);

                // 2. Update data utama pada tabel 'menus'
                $menu->update([
                    'id_dapur'   => $request->id_dapur,
                    'nama_paket' => $request->nama_paket,
                    'deskripsi'  => $request->deskripsi,
                ]);

                // 3. Update data resep pada tabel 'menu_recipes'
                if ($request->has('recipes') && is_array($request->recipes)) {
                    
                    // Hapus semua resep lama untuk menu ini
                    $menu->recipes()->delete();

                    // Tulis ulang dengan daftar resep yang baru dari form edit
                    foreach ($request->recipes as $item) {
                        $menu->recipes()->create([
                            'id_inventory'     => $item['id_inventory'],
                            'jumlah_kebutuhan' => $item['jumlah_kebutuhan'],
                        ]);
                    }
                }

                return $menu;
            });

            return response()->json([
                'success' => true,
                'message' => "Update menu dan komposisi resep berhasil!",
                'data'    => $result->load('recipes')
            ], 200);

        } catch (\Exception $err) {
            // Mengembalikan pesan error jika ada yang salah (misal: foreign key constraint)
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui menu.',
                'error'   => $err->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            \App\Models\MenuRecipe::where('id_menu', $id)->delete();

            $menu = \App\Models\Menu::findOrFail($id);
            $menu->delete();

            return response()->json(['message' => 'Menu dan resep berhasil dihapus'], 200);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json(['error' => 'Gagal menghapus data: ' . $e->getMessage()], 500);
        }
    }
}