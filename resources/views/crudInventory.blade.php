<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Bahan Baku | MBG - Barokah</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-[#F8FAFC]">

    <nav class="bg-white shadow-sm py-4 px-10 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-box text-[#113F67] text-2xl"></i>
            <span class="font-bold text-[#113F67] tracking-tight uppercase">Program Nasional MBG</span>
        </div>
        <div class="flex gap-8 items-center font-medium text-gray-600">
            <a href="{{ url('/') }}" class="hover:text-[#33A1E0]">Beranda</a>
            <a href="{{ url('/inventories') }}" class="text-[#113F67] font-bold">Kembali ke Stok</a>
        </div>
    </nav>

    <main class="max-w-[1400px] mx-auto p-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <div class="lg:col-span-4">
                <div class="bg-white p-8 rounded-[35px] shadow-sm border border-gray-100 sticky top-24">
                    <div class="mb-6">
                        <h3 id="form-title" class="text-2xl font-bold text-[#113F67]">Input Bahan Baku</h3>
                        <p class="text-xs text-gray-400 mt-1">Daftarkan logistik ke unit dapur spesifik.</p>
                    </div>

                    <form id="form-inventory" class="flex flex-col gap-4">
                        @csrf
                        <input type="hidden" id="id_inventory" name="id_inventory">

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Pilih Unit Dapur</label>
                            <select id="id_dapur" name="id_dapur" required
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none appearance-none transition-all cursor-pointer text-sm">
                                <option value="">Memuat daftar dapur...</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Nama Bahan Baku</label>
                            <input type="text" id="nama_bahan" name="nama_bahan" placeholder="Contoh: Daging Sapi, Beras" required
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none transition-all text-sm">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase ml-2">Jumlah Stok</label>
                                <input type="number" id="stok" name="stok" placeholder="0" min="0" required
                                    class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none transition-all text-sm">
                            </div>
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase ml-2">Satuan</label>
                                <select id="satuan" name="satuan" required
                                    class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none cursor-pointer text-sm">
                                    <option value="kg">kg</option>
                                    <option value="gr">gr</option>
                                    <option value="butir">butir</option>
                                    <option value="liter">liter</option>
                                    <option value="ikat">ikat</option>
                                    <option value="bungkus">bungkus</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" id="btn-submit" class="w-full mt-4 bg-[#113F67] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#33A1E0] transition-all">
                            Simpan ke Inventori
                        </button>

                        <button type="button" id="btn-batal" onclick="resetFormInventory()"
                            class="w-full bg-gray-200 text-gray-600 font-bold py-3 rounded-2xl hover:bg-gray-300 transition-all hidden">
                            Batal Edit
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white rounded-[35px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <div>
                            <h3 class="text-2xl font-bold text-[#113F67]">Logistik Terdaftar</h3>
                            <p id="count-inventory" class="text-sm text-gray-400">Total bahan baku yang tersimpan di seluruh dapur.</p>
                        </div>
                        <span class="bg-[#FFF9AF] text-[#113F67] text-[10px] font-black px-4 py-2 rounded-full shadow-sm uppercase tracking-widest">
                            <i class="fa-solid fa-warehouse mr-2"></i> Master Inventory
                        </span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                <tr>
                                    <th class="px-8 py-5">Nama Bahan</th>
                                    <th class="px-8 py-5">Lokasi Dapur</th>
                                    <th class="px-8 py-5 text-center">Stok Saat Ini</th>
                                    <th class="px-8 py-5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-inventory" class="text-gray-600 divide-y divide-gray-50">
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center text-gray-400 italic">Memuat data inventori...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        // Jalankan semua engine saat struktur dokumen web selesai dimuat
        document.addEventListener("DOMContentLoaded", () => {
            loadDropdownDapur();
            loadTableInventory();
        });

        // 1. ENGINE DROPDOWN: MENGAMBIL UNIT DAPUR DARI API
        async function loadDropdownDapur() {
            try {
                const response = await axios.get('/api/dapurs');
                const selectDapur = document.getElementById('id_dapur');
                selectDapur.innerHTML = '<option value="">-- Pilih Unit Dapur --</option>';

                response.data.forEach(dapur => {
                    selectDapur.innerHTML += `<option value="${dapur.id_dapur}">${dapur.nama_dapur}</option>`;
                });
            } catch (error) {
                console.error("Gagal memuat dropdown dapur:", error);
                document.getElementById('id_dapur').innerHTML = '<option value="">Gagal memuat unit dapur</option>';
            }
        }

        // 2. ENGINE READ: MENAMPILKAN DATA LOGISTIK KE TABEL
        async function loadTableInventory() {
            try {
                const response = await axios.get('/api/inventories');
                const inventories = response.data;

                document.getElementById('count-inventory').innerText = `Total ${inventories.length} item logistik tersimpan di seluruh dapur.`;
                
                const tableBody = document.getElementById('table-body-inventory');
                tableBody.innerHTML = '';

                if (inventories.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="4" class="px-8 py-10 text-center text-gray-400 italic">Belum ada bahan baku terdaftar.</td></tr>`;
                    return;
                }

                inventories.forEach(item => {
                    tableBody.innerHTML += `
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="px-8 py-4 font-bold text-[#113F67]">
                                ${item.nama_bahan}
                            </td>
                            <td class="px-8 py-4 text-sm font-medium text-gray-500">
                                <i class="fa-solid fa-fire-burner text-[#33A1E0] mr-1"></i> 
                                ${item.dapur?.nama_dapur || 'Unit Dapur Tidak Ditemukan'}
                            </td>
                            <td class="px-8 py-4 text-center font-bold text-gray-700">
                                ${Number(item.stok).toLocaleString('id-ID')} <span class="text-xs font-normal text-gray-400">${item.satuan}</span>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    <button onclick="prepareEditInventory(${item.id_inventory})" class="text-[#33A1E0] hover:text-[#113F67] transition-all">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button onclick="deleteItem('/api/inventories/${item.id_inventory}', 'table-body-inventory')" class="text-red-500 hover:text-red-700 transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error("Gagal mengambil data inventory:", error);
                document.getElementById('table-body-inventory').innerHTML = `<tr><td colspan="4" class="px-8 py-10 text-center text-red-500 italic">Gagal memuat logistik dari server.</td></tr>`;
            }
        }

        // 3. ENGINE AUTOFILL: MEMINDAHKAN DATA KE FORM (MODE UPDATE)
        async function prepareEditInventory(id) {
            try {
                const response = await axios.get(`/api/inventories/${id}`);
                const inventory = response.data;

                // Memasukkan value lama ke dalam field form
                document.getElementById('id_inventory').value = inventory.id_inventory;
                document.getElementById('id_dapur').value = inventory.id_dapur;
                document.getElementById('nama_bahan').value = inventory.nama_bahan;
                document.getElementById('stok').value = inventory.stok;
                document.getElementById('satuan').value = inventory.satuan;

                // Mengubah UI Form ke Mode Perubahan (Edit Mode)
                document.getElementById('form-title').innerText = "Edit Bahan Baku";
                document.getElementById('btn-submit').innerText = "Update Inventori";
                document.getElementById('btn-submit').className = "w-full mt-4 bg-[#33A1E0] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#113F67] transition-all";
                document.getElementById('btn-batal').classList.remove('hidden');
            } catch (error) {
                console.error("Gagal memuat detail logistik:", error);
                alert("Gagal memuat detail data bahan baku.");
            }
        }

        // 4. ENGINE RESET: MENGEMBALIKAN FORM KE MODE SIMPAN AWAL
        function resetFormInventory() {
            document.getElementById('form-inventory').reset();
            document.getElementById('id_inventory').value = '';

            document.getElementById('form-title').innerText = "Input Bahan Baku";
            document.getElementById('btn-submit').innerText = "Simpan ke Inventori";
            document.getElementById('btn-submit').className = "w-full mt-4 bg-[#113F67] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#33A1E0] transition-all";
            document.getElementById('btn-batal').classList.add('hidden');
        }

        // 5. ENGINE SAVE & UPDATE (PROSES SUBMIT)
        document.getElementById('form-inventory').addEventListener('submit', async (event) => {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const idInventory = document.getElementById('id_inventory').value;

            try {
                if (idInventory) {
                    // JALUR UPDATE (PUT)
                    formData.append('_method', 'PUT');
                    await axios.post(`/api/inventories/${idInventory}`, formData);
                    alert('Data stok bahan baku berhasil diperbarui!');
                } else {
                    // JALUR CREATE (POST)
                    await axios.post('/api/inventories', formData);
                    alert('Bahan baku baru berhasil didaftarkan ke dapur!');
                }
                window.location.reload();
            } catch (error) {
                console.error(error);
                alert('Gagal memproses data inventory. Periksa koneksi API database Anda.');
            }
        });
    </script>
</body>
</html>