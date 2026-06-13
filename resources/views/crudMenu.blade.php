<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Menu & Resep | MBG - Barokah</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-[#F8FAFC]">

    <nav class="bg-white shadow-sm py-4 px-10 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-utensils text-[#113F67] text-2xl"></i>
            <span class="font-bold text-[#113F67] tracking-tight uppercase">Program Nasional MBG</span>
        </div>
        <div class="flex gap-8 items-center font-medium text-gray-600">
            <a href="{{ url('/') }}" class="hover:text-[#33A1E0]">Beranda</a>
            <a href="{{ url('/menus') }}" class="text-[#113F67] font-bold">Kembali ke Monitoring</a>
        </div>
    </nav>

    <main class="max-w-[1200px] mx-auto p-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <div class="lg:col-span-1">
                <div class="bg-white p-8 rounded-[35px] shadow-sm border border-gray-100 sticky top-24">
                    <h3 id="form-title" class="text-2xl font-bold text-[#113F67] mb-6">Input Menu Baru</h3>
                    <form id="form-menu" class="flex flex-col gap-5">
                        @csrf
                        <input type="hidden" id="id_menu" name="id_menu">

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Nama Paket Menu</label>
                            <input type="text" id="nama_paket" name="nama_paket" placeholder="Contoh: Paket A (Nasi + Ayam)" required
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none text-sm">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Pilih Unit Dapur</label>
                            <select id="id_dapur" name="id_dapur" required
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none text-sm">
                                <option value="">Memuat unit dapur...</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Deskripsi Menu</label>
                            <textarea id="deskripsi" name="deskripsi" placeholder="Keterangan singkat menu..." rows="2"
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none text-sm"></textarea>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <div class="flex justify-between items-center mb-3">
                                <label class="text-xs font-bold text-[#113F67] uppercase ml-2">Komposisi Bahan (Resep)</label>
                                <button type="button" onclick="addRecipeRow()" class="text-[#33A1E0] text-xs font-bold hover:underline">
                                    + Tambah Bahan
                                </button>
                            </div>
                            <div id="recipe-container" class="flex flex-col gap-2 max-h-[220px] overflow-y-auto pr-1">
                            </div>
                        </div>

                        <button type="submit" id="btn-submit" class="w-full mt-2 bg-[#113F67] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#33A1E0] transition-all">
                            Simpan Menu & Resep
                        </button>

                        <button type="button" id="btn-batal" onclick="resetFormMenu()"
                            class="w-full bg-gray-200 text-gray-600 font-bold py-3 rounded-2xl hover:bg-gray-300 transition-all hidden">
                            Batal Edit
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-[35px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-[#113F67]">Daftar Menu Tersedia</h3>
                            <p id="count-menu" class="text-xs text-gray-400 mt-1">0 Menu Terdaftar</p>
                        </div>
                        <i class="fa-solid fa-utensils text-gray-200 text-2xl"></i>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                <tr>
                                    <th class="px-8 py-4">Nama Paket & Dapur</th>
                                    <th class="px-8 py-4">Komposisi Resep</th>
                                    <th class="px-8 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-menu" class="text-gray-600 divide-y divide-gray-50">
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center text-gray-400 italic">Memuat data menu...</td>
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
        // Global Variable untuk menyimpan data inventory agar tidak memanggil API berulang kali
        let inventoriesData = [];

        // Global initialization
        document.addEventListener("DOMContentLoaded", async () => {
            await loadDropdownDapur();
            await loadDataInventory(); // Wajib dipanggil sebelum addRecipeRow
            loadTableMenu();
            addRecipeRow(); 
        });

        // 1. ENGINE DROPDOWN: ISI PILIHAN UNIT DAPUR
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
                document.getElementById('id_dapur').innerHTML = '<option value="">Gagal memuat data dapur</option>';
            }
        }

        // 2. ENGINE FETCH INVENTORY (BARU)
        // Mengambil daftar bahan baku untuk dropdown resep
        async function loadDataInventory() {
            try {
                // Pastikan kamu punya endpoint ini di route Laravel
                const response = await axios.get('/api/inventories'); 
                inventoriesData = response.data;
            } catch (error) {
                console.error("Gagal memuat data inventory:", error);
            }
        }

        // 3. ENGINE RESEP DYNAMIC ROW MANAGEMENTS (DIPERBARUI)
        // Mengubah parameter agar sesuai database: id_inventory dan jumlah_kebutuhan
        function addRecipeRow(idInventory = '', jumlahKebutuhan = '') {
            const container = document.getElementById('recipe-container');
            const rowId = 'row-' + Date.now() + Math.random().toString(36).substr(2, 4);

            // Generate pilihan dropdown HTML berdasarkan data inventory
            let optionsHTML = '<option value="">-- Pilih Bahan Baku --</option>';
            inventoriesData.forEach(inv => {
                const isSelected = (inv.id_inventory == idInventory) ? 'selected' : '';
                // Menampilkan nama bahan beserta satuannya di dropdown (misal: "Daging Sapi (kg)")
                optionsHTML += `<option value="${inv.id_inventory}" ${isSelected}>
                                    ${inv.nama_bahan} (${inv.satuan})
                                </option>`;
            });

            // Kolom 'satuan' dihapus karena sudah diwakilkan oleh dropdown, menyisakan Bahan & Jumlah
            const rowHTML = `
                <div id="${rowId}" class="recipe-row flex gap-2 items-center bg-gray-50 p-2 rounded-xl border border-gray-100">
                    <select required class="recipe-bahan w-3/5 p-2 bg-white rounded-lg border-none focus:ring-1 focus:ring-[#33A1E0] outline-none text-xs">
                        ${optionsHTML}
                    </select>
                    <input type="number" value="${jumlahKebutuhan}" placeholder="Jumlah" required min="1" step="any"
                        class="recipe-jumlah w-1/5 p-2 bg-white rounded-lg border-none focus:ring-1 focus:ring-[#33A1E0] outline-none text-xs">
                    <button type="button" onclick="document.getElementById('${rowId}').remove()" 
                        class="w-1/5 text-red-400 hover:text-red-600 transition-all text-sm flex justify-center">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', rowHTML);
        }

        // 4. ENGINE READ: MENAMPILKAN MENU KE TABEL (DIPERBARUI)
        async function loadTableMenu() {
            try {
                const response = await axios.get('/api/menus');
                const menus = response.data;
                
                document.getElementById('count-menu').innerText = `${menus.length} Menu Terdaftar`;
                const tableBody = document.getElementById('table-body-menu');
                tableBody.innerHTML = '';

                if(menus.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="3" class="px-8 py-10 text-center text-gray-400 italic">Belum ada data menu.</td></tr>`;
                    return;
                }

                menus.forEach(item => {
                    let resepBadges = '';
                    if (item.recipes && item.recipes.length > 0) {
                        // Menyesuaikan tampilan badge dengan relasi inventory
                        resepBadges = item.recipes.map(r => {
                            // Asumsi backend melampirkan object inventory (r.inventory.nama_bahan)
                            const namaBahan = r.inventory ? r.inventory.nama_bahan : 'Bahan Unknown';
                            const satuanBahan = r.inventory ? r.inventory.satuan : '';
                            return `<span class="inline-block bg-gray-100 text-gray-600 text-[11px] px-2 py-0.5 rounded-md font-medium mr-1 mb-1">
                                        ${namaBahan} (${r.jumlah_kebutuhan} ${satuanBahan})
                                    </span>`;
                        }).join('');
                    } else {
                        resepBadges = '<span class="text-xs text-gray-400 italic">Resep belum diinput</span>';
                    }

                    tableBody.innerHTML += `
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="px-8 py-4">
                                <div class="font-bold text-[#113F67]">${item.nama_paket}</div>
                                <div class="text-xs font-semibold text-[#33A1E0] mt-0.5">
                                    <i class="fa-solid fa-fire-burner"></i> ${item.dapur?.nama_dapur || 'Unit Dapur Tidak Ditemukan'}
                                </div>
                            </td>
                            <td class="px-8 py-4 max-w-[300px]">
                                <div class="text-xs text-gray-500 mb-2 italic line-clamp-1">${item.deskripsi || '-'}</div>
                                <div class="flex flex-wrap">${resepBadges}</div>
                            </td>
                            <td class="px-8 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    <button onclick="prepareEditMenu(${item.id_menu})" class="text-[#33A1E0] hover:text-[#113F67] transition-all">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button onclick="deleteMenuItem(${item.id_menu})" class="text-red-500 hover:text-red-700 transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error("Gagal mengambil data menu:", error);
                document.getElementById('table-body-menu').innerHTML = `<tr><td colspan="3" class="px-8 py-10 text-center text-red-500 italic">Gagal mengambil data menu dari server.</td></tr>`;
            }
        }

        // 5. ENGINE AUTOFILL: UPDATE MODE (DIPERBARUI)
        async function prepareEditMenu(id) {
            try {
                const response = await axios.get(`/api/menus/${id}`);
                const menu = response.data;

                document.getElementById('id_menu').value = menu.id_menu;
                document.getElementById('nama_paket').value = menu.nama_paket;
                document.getElementById('id_dapur').value = menu.id_dapur;
                document.getElementById('deskripsi').value = menu.deskripsi || '';

                const container = document.getElementById('recipe-container');
                container.innerHTML = '';
                
                if(menu.recipes && menu.recipes.length > 0) {
                    // Mapping sesuai key dari database menu_recipes
                    menu.recipes.forEach(r => {
                        addRecipeRow(r.id_inventory, r.jumlah_kebutuhan);
                    });
                } else {
                    addRecipeRow();
                }

                document.getElementById('form-title').innerText = "Edit Menu & Resep";
                document.getElementById('btn-submit').innerText = "Update Menu & Resep";
                document.getElementById('btn-submit').className = "w-full mt-2 bg-[#33A1E0] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#113F67] transition-all";
                document.getElementById('btn-batal').classList.remove('hidden');
            } catch (error) {
                console.error("Gagal memuat detail resep menu:", error);
                alert("Gagal memuat detail data menu.");
            }
        }

        // 6. ENGINE RESET FORM
        function resetFormMenu() {
            document.getElementById('form-menu').reset();
            document.getElementById('id_menu').value = '';
            document.getElementById('recipe-container').innerHTML = '';
            addRecipeRow();

            document.getElementById('form-title').innerText = "Input Menu Baru";
            document.getElementById('btn-submit').innerText = "Simpan Menu & Resep";
            document.getElementById('btn-submit').className = "w-full mt-2 bg-[#113F67] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#33A1E0] transition-all";
            document.getElementById('btn-batal').classList.add('hidden');
        }

        // 7. ENGINE SUBMIT (CREATE & UPDATE) (DIPERBARUI)
        document.getElementById('form-menu').addEventListener('submit', async (event) => {
            event.preventDefault();

            const idMenu = document.getElementById('id_menu').value;
            const resepItems = [];
            const rows = document.querySelectorAll('.recipe-row');
            
            rows.forEach(row => {
                const id_inventory = row.querySelector('.recipe-bahan').value;
                const jumlah = row.querySelector('.recipe-jumlah').value;
                
                if(id_inventory !== "") {
                    // Menyesuaikan penamaan atribut agar sinkron dengan kolom DB phpMyAdmin kamu
                    resepItems.push({
                        id_inventory: id_inventory,
                        jumlah_kebutuhan: jumlah
                    });
                }
            });

            const payload = {
                nama_paket: document.getElementById('nama_paket').value,
                id_dapur: document.getElementById('id_dapur').value,
                deskripsi: document.getElementById('deskripsi').value,
                recipes: resepItems 
            };

            try {
                if (idMenu) {
                    await axios.put(`/api/menus/${idMenu}`, payload);
                    alert('Data menu berhasil diperbarui!');
                } else {
                    await axios.post('/api/menus', payload);
                    alert('Data menu baru berhasil disimpan!');
                }
                resetFormMenu();
                loadTableMenu();
            } catch (error) {
                console.error(error);
                alert('Gagal memproses data menu. Pastikan struktur API/Controller Anda sudah sesuai.');
            }
        });

        // 8. ENGINE DELETE
        async function deleteMenuItem(id) {
            if(confirm('Apakah Anda yakin ingin menghapus paket menu ini beserta seluruh resepnya?')) {
                try {
                    await axios.delete(`/api/menus/${id}`);
                    alert('Menu berhasil dihapus!');
                    loadTableMenu();
                } catch (error) {
                    console.error("Gagal menghapus menu:", error);
                    alert('Gagal menghapus data menu.');
                }
            }
        }
    </script>
</body>
</html>