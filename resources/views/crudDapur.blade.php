<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Unit Dapur | MBG - Barokah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-[#F8FAFC]">

    <nav class="bg-white shadow-sm py-4 px-10 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-fire-burner text-[#113F67] text-2xl"></i>
            <span class="font-bold text-[#113F67] tracking-tight uppercase">Program Nasional MBG</span>
        </div>
        <div class="flex gap-8 items-center font-medium text-gray-600">
            <a href="{{ url('/') }}" class="hover:text-[#33A1E0]">Beranda</a>
            <a href="{{ url('/dapurs') }}" class="text-[#113F67] font-bold">Kembali ke Monitoring</a>
        </div>
    </nav>

    <main class="max-w-[1200px] mx-auto p-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-1">
                <div class="bg-white p-8 rounded-[35px] shadow-sm border border-gray-100">
                    <h3 id="form-title" class="text-2xl font-bold text-[#113F67] mb-6">Input Unit Dapur</h3>
                    <form id="form-dapur" class="flex flex-col gap-5">
                        @csrf 
                        <input type="hidden" id="id_dapur" name="id_dapur">

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Nama Unit Dapur</label>
                            <input type="text" id="nama_dapur" name="nama_dapur" placeholder="Contoh: Dapur Umum Pusat" required
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Kapasitas Produksi</label>
                            <div class="relative">
                                <input type="number" id="kapasitas_porsi" name="kapasitas_porsi" placeholder="0" required
                                    class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none">
                                <span class="absolute right-4 top-5 text-gray-400 text-sm">Porsi/Hari</span>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Alamat / Lokasi</label>
                            <textarea id="lokasi" name="lokasi" placeholder="Alamat lengkap unit dapur..." rows="3" required
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none"></textarea>
                        </div>

                        <button type="submit" id="btn-submit" class="w-full mt-2 bg-[#113F67] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#33A1E0] transition-all">
                            Simpan Unit Dapur
                        </button>

                        <button type="button" id="btn-batal" onclick="resetFormDapur()"
                            class="w-full bg-gray-200 text-gray-600 font-bold py-3 rounded-2xl hover:bg-gray-300 transition-all hidden">
                            Batal Edit
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-[35px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-[#113F67]">Daftar Dapur Terdaftar</h3>
                        <span id="count-dapur" class="bg-[#FFF9AF] text-[#113F67] text-xs font-bold px-4 py-1 rounded-full italic">0 Terdaftar</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                <tr>
                                    <th class="px-8 py-4">Informasi Dapur</th>
                                    <th class="px-8 py-4 text-center">Kapasitas</th>
                                    <th class="px-8 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-dapur" class="text-gray-600 divide-y divide-gray-50">
                                <tr>
                                    <td colspan="3" class="px-8 py-10 text-center text-gray-400 italic">
                                        Memuat data dapur...
                                    </td>
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
        // 1. ENGINE READ: MENAMPILKAN DATA KE TABEL
        async function loadTableDapur() {
            try {
                const response = await axios.get('/api/dapurs');
                const dapurs = response.data;
                
                document.getElementById('count-dapur').innerText = `${dapurs.length} Terdaftar`;
                
                const tableBody = document.getElementById('table-body-dapur');
                tableBody.innerHTML = ''; 

                if (dapurs.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="3" class="px-8 py-10 text-center text-gray-400 italic">Belum ada data unit dapur.</td></tr>`;
                    return;
                }

                dapurs.forEach(item => {
                    tableBody.innerHTML += `
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="px-8 py-4">
                                <div class="font-bold text-[#113F67]">${item.nama_dapur}</div>
                                <div class="text-xs text-gray-400 mt-0.5"><i class="fa-solid fa-location-dot"></i> ${item.lokasi}</div>
                            </td>
                            <td class="px-8 py-4 text-center font-semibold text-gray-700">
                                ${Number(item.kapasitas_porsi).toLocaleString('id-ID')} <span class="text-xs font-normal text-gray-400">porsi</span>
                            </td>
                            <td class="px-8 py-4 text-center flex justify-center gap-3 mt-2">
                                <button onclick="prepareEditDapur(${item.id_dapur})" class="text-[#33A1E0] hover:text-[#113F67] transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button onclick="deleteItem('/api/dapurs/${item.id_dapur}', 'table-body-dapur')" class="text-red-500 hover:text-red-700 transition-all">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error("Gagal memuat data dapur:", error);
                document.getElementById('table-body-dapur').innerHTML = `<tr><td colspan="3" class="px-8 py-10 text-center text-red-500 italic">Gagal mengambil data dari server.</td></tr>`;
            }
        }

        // Panggil fungsi tampil data saat halaman selesai dimuat
        document.addEventListener("DOMContentLoaded", loadTableDapur);

        // 2. ENGINE AUTOFILL: MENYIAPKAN FORM UNTUK EDIT MODE
        async function prepareEditDapur(id) {
            try {
                const response = await axios.get(`/api/dapurs/${id}`);
                const dapur = response.data;

                // Set value input form dari data lama
                document.getElementById('id_dapur').value = dapur.id_dapur;
                document.getElementById('nama_dapur').value = dapur.nama_dapur;
                document.getElementById('kapasitas_porsi').value = dapur.kapasitas_porsi;
                document.getElementById('lokasi').value = dapur.lokasi;

                // Ubah Tampilan Form ke Mode Edit
                document.getElementById('form-title').innerText = "Edit Unit Dapur";
                document.getElementById('btn-submit').innerText = "Update Unit Dapur";
                document.getElementById('btn-submit').className = "w-full mt-2 bg-[#33A1E0] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#113F67] transition-all";
                document.getElementById('btn-batal').classList.remove('hidden');
            } catch (error) {
                console.error("Gagal mengambil detail data dapur:", error);
                alert("Gagal memuat data detail dapur.");
            }
        }

        // 3. ENGINE RESET: MENGEMBALIKAN FORM KE MODE TAMBAH
        function resetFormDapur() {
            document.getElementById('form-dapur').reset();
            document.getElementById('id_dapur').value = '';
            
            document.getElementById('form-title').innerText = "Input Unit Dapur";
            document.getElementById('btn-submit').innerText = "Simpan Unit Dapur";
            document.getElementById('btn-submit').className = "w-full mt-2 bg-[#113F67] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#33A1E0] transition-all";
            document.getElementById('btn-batal').classList.add('hidden');
        }

        // 4. ENGINE SAVE & UPDATE
        document.getElementById('form-dapur').addEventListener('submit', async (event) => {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const idDapur = document.getElementById('id_dapur').value;

            try {
                if (idDapur) {
                    // JALUR UPDATE (PUT)
                    formData.append('_method', 'PUT');
                    await axios.post(`/api/dapurs/${idDapur}`, formData);
                    alert('Data unit dapur berhasil diperbarui!');
                } else {
                    // JALUR CREATE (POST)
                    await axios.post('/api/dapurs', formData);
                    alert('Data unit dapur berhasil disimpan!');
                }
                window.location.reload(); 
            } catch (error) {
                console.error(error);
                alert('Gagal memproses data. Periksa kembali inputan atau koneksi server Anda.');
            }
        });
    </script>
</body>
</html>