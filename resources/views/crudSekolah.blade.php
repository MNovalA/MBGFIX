<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Sekolah | MBG - Barokah</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-[#F8FAFC]">

    <nav class="bg-white shadow-sm py-4 px-10 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-shop text-[#113F67] text-2xl"></i>
            <span class="font-bold text-[#113F67] tracking-tight uppercase">Program Nasional MBG</span>
        </div>
        <div class="flex gap-8 items-center font-medium text-gray-600">
            <a href="{{ url('/') }}" class="hover:text-[#33A1E0]">Beranda</a>
            <a href="{{ url('/sekolahs') }}" class="text-[#113F67] font-bold">Kembali ke Monitoring</a>
        </div>
    </nav>

    <main class="max-w-[1200px] mx-auto p-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <div class="lg:col-span-1">
                <div class="bg-white p-8 rounded-[35px] shadow-sm border border-gray-100">
                    <h3 id="form-title" class="text-2xl font-bold text-[#113F67] mb-6">Input Sekolah</h3>
                    <form id="form-sekolah" class="flex flex-col gap-4">
                        @csrf <input type="hidden" id="id_sekolah" name="id"> 
                        
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">NPSN</label>
                            <input type="text" id="npsn" name="npsn" placeholder="Contoh: 20210045" required
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Nama Sekolah</label>
                            <input type="text" id="nama_sekolah" name="nama_sekolah" placeholder="Nama Lengkap Sekolah" required
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Jenjang</label>
                            <select id="jenjang" name="jenjang" required
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none transition-all appearance-none">
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Jumlah Siswa</label>
                            <input type="number" id="jumlah_siswa" name="jumlah_siswa" placeholder="0" required
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none transition-all">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Alamat</label>
                            <textarea id="alamat_sekolah" name="alamat_sekolah" placeholder="Alamat lengkap..." rows="3"
                                class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border-none focus:ring-2 focus:ring-[#33A1E0] outline-none transition-all"></textarea>
                        </div>

                        <button type="submit" id="btn-submit"
                            class="w-full mt-4 bg-[#113F67] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#33A1E0] transition-all">
                            Simpan Data Sekolah
                        </button>
                        
                        <button type="button" id="btn-batal" onclick="resetFormSekolah()"
                            class="w-full bg-gray-200 text-gray-600 font-bold py-3 rounded-2xl hover:bg-gray-300 transition-all hidden">
                            Batal Edit
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-[35px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-[#113F67]">Database Sekolah</h3>
                        <span id="count-sekolah" class="bg-[#FFF9AF] text-[#113F67] text-xs font-bold px-4 py-1 rounded-full italic">0 Terdaftar</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                <tr>
                                    <th class="px-8 py-4">NPSN</th>
                                    <th class="px-8 py-4">Nama Sekolah</th>
                                    <th class="px-8 py-4 text-center">Jenjang</th>
                                    <th class="px-8 py-4 text-center">Siswa</th>
                                    <th class="px-8 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-sekolah" class="text-gray-600 divide-y divide-gray-50">
                                <tr>
                                    <td colspan="5" class="px-8 py-10 text-center text-gray-400 italic">
                                        Memuat data...
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
        // 1. ENGINE UNTUK MENAMPILKAN DATA KE TABEL
        async function loadTableSekolah() {
            try {
                const response = await axios.get('/api/sekolahs');
                const sekolahs = response.data;
                
                document.getElementById('count-sekolah').innerText = `${sekolahs.length} Terdaftar`;
                
                const tableBody = document.getElementById('table-body-sekolah');
                tableBody.innerHTML = ''; 

                if (sekolahs.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="5" class="px-8 py-10 text-center text-gray-400 italic">Belum ada data sekolah.</td></tr>`;
                    return;
                }

                sekolahs.forEach(item => {
                    tableBody.innerHTML += `
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="px-8 py-4 font-mono text-sm text-gray-500">${item.npsn || '-'}</td>
                            <td class="px-8 py-4 font-bold text-[#113F67]">${item.nama_sekolah}</td>
                            <td class="px-8 py-4 text-center"><span class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-md font-semibold">${item.jenjang || 'SD'}</span></td>
                            <td class="px-8 py-4 text-center font-medium">${item.jumlah_siswa}</td>
                            <td class="px-8 py-4 text-center flex justify-center gap-3">
                                <button onclick="prepareEdit(${item.id_sekolah})" class="text-[#33A1E0] hover:text-[#113F67] transition-all">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button onclick="deleteItem('/api/sekolahs/${item.id_sekolah}', 'table-body-sekolah')" class="text-red-500 hover:text-red-700 transition-all">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error("Gagal memuat data tabel:", error);
                document.getElementById('table-body-sekolah').innerHTML = `<tr><td colspan="5" class="px-8 py-10 text-center text-red-500 italic">Gagal mengambil data dari server.</td></tr>`;
            }
        }

        document.addEventListener("DOMContentLoaded", loadTableSekolah);

        // 2. 💡 TAMBAHAN ENGINE: MENYIAPKAN FORM UNTUK UPDATE (AUTOFILL)
        async function prepareEdit(id) {
            try {
                const response = await axios.get(`/api/sekolahs/${id}`);
                const sekolah = response.data;

                // Isi nilai input form dengan data lama dari database
                document.getElementById('id_sekolah').value = sekolah.id_sekolah;
                document.getElementById('npsn').value = sekolah.npsn;
                document.getElementById('nama_sekolah').value = sekolah.nama_sekolah;
                document.getElementById('jenjang').value = sekolah.jenjang;
                document.getElementById('jumlah_siswa').value = sekolah.jumlah_siswa;
                document.getElementById('alamat_sekolah').value = sekolah.alamat_sekolah;

                // Ubah tampilan UI Form ke Mode Edit
                document.getElementById('form-title').innerText = "Edit Sekolah";
                document.getElementById('btn-submit').innerText = "Update Data Sekolah";
                document.getElementById('btn-submit').className = "w-full mt-4 bg-[#33A1E0] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#113F67] transition-all";
                document.getElementById('btn-batal').classList.remove('hidden');
            } catch (error) {
                console.error("Gagal mengambil data detail:", error);
                alert("Gagal mengambil data sekolah.");
            }
        }

        // 3. 💡 TAMBAHAN ENGINE: MERESET FORM KE SEMULA (MODE SIMPAN BARU)
        function resetFormSekolah() {
            document.getElementById('form-sekolah').reset();
            document.getElementById('id_sekolah').value = '';
            
            document.getElementById('form-title').innerText = "Input Sekolah";
            document.getElementById('btn-submit').innerText = "Simpan Data Sekolah";
            document.getElementById('btn-submit').className = "w-full mt-4 bg-[#113F67] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#33A1E0] transition-all";
            document.getElementById('btn-batal').classList.add('hidden');
        }

        // 4. ENGINE SAVE (BISA MENDETEKSI ANTARA CREATE ATAU UPDATE)
        document.getElementById('form-sekolah').addEventListener('submit', async (event) => {
            event.preventDefault();

            const form = event.target;
            const formData = new FormData(form);
            const idSekolah = document.getElementById('id_sekolah').value;

            try {
                if (idSekolah) {
                    // 💡 JALUR UPDATE: Kirim ke /api/sekolahs/{id} dengan Form Spoofing PUT khas Laravel
                    formData.append('_method', 'PUT');
                    await axios.post(`/api/sekolahs/${idSekolah}`, formData);
                    alert('Data sekolah berhasil diperbarui!');
                } else {
                    // JALUR CREATE: Kirim POST data baru
                    await axios.post('/api/sekolahs', formData);
                    alert('Data sekolah berhasil disimpan!');
                }
                window.location.reload(); 
            } catch (error) {
                console.error(error);
                alert('Gagal memproses data. Periksa kembali inputan atau koneksi database Anda.');
            }
        });
    </script>
</body>
</html>