<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Paket MBG | MBG - Barokah</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-[#F8FAFC]">

    <nav class="bg-white shadow-sm py-4 px-10 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-paper-plane text-[#113F67] text-2xl"></i>
            <span class="font-bold text-[#113F67] tracking-tight uppercase">Program Nasional MBG</span>
        </div>
        <div class="flex gap-8 items-center font-medium text-gray-600">
            <a href="{{ url('/') }}" class="hover:text-[#33A1E0]">Beranda</a>
            <a href="{{ url('/shipments') }}" class="text-[#113F67] font-bold">Kembali ke Monitoring</a>
        </div>
    </nav>

    <main class="max-w-[1400px] mx-auto p-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            <div class="lg:col-span-4">
                <div class="bg-white p-8 rounded-[35px] shadow-sm border border-gray-100 sticky top-24">
                    <div class="text-center mb-6">
                        <h3 id="form-title" class="text-2xl font-bold text-[#113F67]">Form Pengiriman</h3>
                        <p class="text-gray-400 text-xs mt-1">Pastikan semua data sinkron agar stok terpotong otomatis.</p>
                    </div>

                    <form id="form-distribusi" class="flex flex-col gap-4">
                        @csrf 
                        <input type="hidden" id="id_shipment" name="id_shipment">

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Pilih Sekolah Tujuan</label>
                            <select id="id_sekolah" name="id_sekolah" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-[#33A1E0] text-sm appearance-none cursor-pointer">
                                <option value="">Memuat data sekolah...</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Menu Makanan</label>
                            <select id="id_menu" name="id_menu" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-[#33A1E0] text-sm appearance-none cursor-pointer">
                                <option value="">Memuat daftar menu...</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Dapur Pengirim</label>
                            <select id="id_dapur" name="id_dapur" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-[#33A1E0] text-sm appearance-none cursor-pointer">
                                <option value="">Memuat unit dapur...</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase ml-2">Jumlah Porsi</label>
                                <input type="number" id="jumlah_porsi" name="jumlah_porsi" required placeholder="0" min="1" class="w-full mt-1 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-[#33A1E0] text-sm">
                            </div>

                            <div>
                                <label class="text-xs font-bold text-gray-400 uppercase ml-2">Status Pengiriman</label>
                                <select id="status" name="status" class="w-full mt-1 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-[#33A1E0] text-sm cursor-pointer">
                                    <option value="Persiapan">Persiapan</option>
                                    <option value="Perjalanan">Perjalanan</option>
                                    <option value="Diterima">Diterima</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase ml-2">Estimasi Waktu Sampai</label>
                            <input type="date" id="waktu_sampai" name="waktu_sampai" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl outline-none focus:ring-2 focus:ring-[#33A1E0] text-sm">
                        </div>

                        <div class="mt-2 flex flex-col gap-2">
                            <button type="submit" id="btn-submit" class="w-full bg-[#113F67] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#33A1E0] transition-all flex items-center justify-center gap-3 text-sm">
                                <i class="fa-solid fa-truck-fast"></i> KONFIRMASI & KIRIM
                            </button>

                            <button type="button" id="btn-batal" onclick="resetFormDistribusi()" class="w-full bg-gray-200 text-gray-600 font-bold py-3 rounded-2xl hover:bg-gray-300 transition-all hidden text-sm">
                                Batal Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white rounded-[35px] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <div>
                            <h3 class="text-2xl font-bold text-[#113F67]">Manajemen Distribusi</h3>
                            <p id="count-distribusi" class="text-sm text-gray-400">0 Paket terpantau dalam sistem pengantaran.</p>
                        </div>
                        <span class="bg-[#E3F2FD] text-[#113F67] text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest">
                            <i class="fa-solid fa-route mr-1"></i> Live Tracking
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-bold tracking-widest">
                                <tr>
                                    <th class="px-6 py-5">Destinasi & Dapur</th>
                                    <th class="px-6 py-5">Menu / Vol</th>
                                    <th class="px-6 py-5 text-center">Status</th>
                                    <th class="px-6 py-5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-body-distribusi" class="text-gray-600 divide-y divide-gray-50 text-sm">
                                <tr>
                                    <td colspan="4" class="px-8 py-10 text-center text-gray-400 italic">Memuat data pengiriman...</td>
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
        document.addEventListener("DOMContentLoaded", () => {
            loadDropdowns();
            loadTableDistribusi();
        });

        // 1. ENGINE DROPDOWNS: POPULASI DATA SEKOLAH, MENU, & DAPUR
        async function loadDropdowns() {
            try {
                const [resSekolah, resMenu, resDapur] = await Promise.all([
                    axios.get('/api/sekolahs'),
                    axios.get('/api/menus'),
                    axios.get('/api/dapurs')
                ]);

                const selectSekolah = document.getElementById('id_sekolah');
                selectSekolah.innerHTML = '<option value="">-- Pilih Sekolah Tujuan --</option>';
                resSekolah.data.forEach(s => selectSekolah.innerHTML += `<option value="${s.id_sekolah}">${s.nama_sekolah}</option>`);

                const selectMenu = document.getElementById('id_menu');
                selectMenu.innerHTML = '<option value="">-- Pilih Menu Paket --</option>';
                resMenu.data.forEach(m => selectMenu.innerHTML += `<option value="${m.id_menu}">${m.nama_paket}</option>`);

                const selectDapur = document.getElementById('id_dapur');
                selectDapur.innerHTML = '<option value="">-- Pilih Dapur Pengirim --</option>';
                resDapur.data.forEach(d => selectDapur.innerHTML += `<option value="${d.id_dapur}">${d.nama_dapur}</option>`);

            } catch (error) {
                console.error("Gagal menyinkronkan data drop-down:", error);
            }
        }

        // 2. ENGINE READ: MENAMPILKAN LOG DISTRIBUSI KE TABEL
        async function loadTableDistribusi() {
            try {
                const response = await axios.get('/api/shipments'); 
                const shipments = response.data;

                document.getElementById('count-distribusi').innerText = `${shipments.length} Paket terpantau dalam sistem pengantaran.`;
                const tableBody = document.getElementById('table-body-distribusi');
                tableBody.innerHTML = '';

                if (shipments.length === 0) {
                    tableBody.innerHTML = `<tr><td colspan="4" class="px-8 py-10 text-center text-gray-400 italic">Belum ada riwayat pengiriman hari ini.</td></tr>`;
                    return;
                }

                shipments.forEach(item => {
                    let statusBadge = "";
                    if(item.status === "Persiapan") statusBadge = "bg-amber-100 text-amber-700";
                    else if(item.status === "Perjalanan") statusBadge = "bg-blue-100 text-blue-700";
                    else statusBadge = "bg-emerald-100 text-emerald-700";

                    tableBody.innerHTML += `
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="px-6 py-4">
                                <div class="font-bold text-[#113F67]">${item.nama_sekolah || 'Sekolah Terhapus'}</div>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    <i class="fa-solid fa-fire-burner"></i> Dari: ${item.nama_dapur || 'Unit Dapur Unknown'}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-700">${item.nama_paket || 'Menu Alternatif'}</div>
                                <div class="text-xs font-bold text-[#33A1E0] mt-0.5">${item.jumlah_porsi} Porsi</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block text-[11px] font-bold px-3 py-1 rounded-full ${statusBadge}">
                                    ${item.status_kirim}
                                </span>
                                <div class="text-[10px] text-gray-400 mt-1">${item.waktu_sampai || '-'}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-3">
                                    <button onclick="prepareEditDistribusi(${item.id_shipment})" class="text-[#33A1E0] hover:text-[#113F67] transition-all">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button onclick="deleteItem('/api/shipments/${item.id_shipment}')" class="text-red-500 hover:text-red-700 transition-all">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error("Gagal memuat log distribusi:", error);
                document.getElementById('table-body-distribusi').innerHTML = `<tr><td colspan="4" class="px-8 py-10 text-center text-red-500 italic">Gagal memuat data dari server API.</td></tr>`;
            }
        }

        // 3. ENGINE AUTOFILL: AMBIL DATA LAMA KE FORM (UPDATE MODE)
        async function prepareEditDistribusi(id) {
            try {
                const response = await axios.get(`/api/shipments/${id}`);
                const data = response.data;

                document.getElementById('id_shipment').value = data.id_shipment;
                document.getElementById('id_sekolah').value = data.id_sekolah;
                document.getElementById('id_menu').value = data.id_menu;
                document.getElementById('id_dapur').value = data.id_dapur;
                document.getElementById('jumlah_porsi').value = data.jumlah_porsi;
                document.getElementById('status').value = data.status_kirim;
                document.getElementById('waktu_sampai').value = data.waktu_sampai ? data.waktu_sampai.split(' ')[0].split('T')[0] : '';

                document.getElementById('form-title').innerText = "Edit Distribusi";
                document.getElementById('btn-submit').innerHTML = "<i class='fa-solid fa-pen-to-square'></i> UPDATE PENGIRIMAN";
                document.getElementById('btn-submit').className = "w-full bg-[#33A1E0] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#113F67] transition-all flex items-center justify-center gap-3 text-sm";
                document.getElementById('btn-batal').classList.remove('hidden');
            } catch (error) {
                console.error("Gagal prefill form distribusi:", error);
                alert("Gagal memuat detail riwayat pengiriman.");
            }
        }

        // 4. ENGINE RESET FORM
        function resetFormDistribusi() {
            document.getElementById('form-distribusi').reset();
            document.getElementById('id_shipment').value = '';

            document.getElementById('form-title').innerText = "Form Pengiriman";
            document.getElementById('btn-submit').innerHTML = "<i class='fa-solid fa-truck-fast'></i> KONFIRMASI & KIRIM";
            document.getElementById('btn-submit').className = "w-full bg-[#113F67] text-white font-bold py-4 rounded-2xl shadow-lg hover:bg-[#33A1E0] transition-all flex items-center justify-center gap-3 text-sm";
            document.getElementById('btn-batal').classList.add('hidden');
        }

        // 5. ENGINE SUBMIT (HANDLE CREATE & UPDATE)
        document.getElementById('form-distribusi').addEventListener('submit', async (event) => {
            event.preventDefault();

            const idDistribusi = document.getElementById('id_shipment').value;
            
            // Bungkus data langsung ke format JSON Object agar sinkron dengan API Controller
            const payload = {
                id_sekolah: document.getElementById('id_sekolah').value,
                id_menu: document.getElementById('id_menu').value,
                id_dapur: document.getElementById('id_dapur').value,
                jumlah_porsi: document.getElementById('jumlah_porsi').value,
                status: document.getElementById('status').value,
                waktu_sampai: document.getElementById('waktu_sampai').value
            };

            try {
                if (idDistribusi) {
                    // MODE UPDATE (PUT)
                    await axios.put(`/api/shipments/${idDistribusi}`, payload);
                    alert('Data pelacakan pengiriman berhasil diperbarui!');
                } else {
                    // MODE CREATE (POST)
                    await axios.post('/api/shipments', payload);
                    alert('Paket distribusi baru berhasil dikonfirmasi dan dikirim!');
                }
                
                resetFormDistribusi(); // Kosongkan form kembali ke mode awal
                loadTableDistribusi(); // Refresh data tabel secara real-time
            } catch (error) {
                console.error(error);
                alert('Gagal mengeksekusi log distribusi. Periksa relasi stok atau route controller Anda.');
            }
        });

        // 6. ENGINE DELETE: SELESAI & LENGKAP!
        async function deleteItem(url) {
            if (confirm("Apakah Anda yakin ingin menghapus data pengiriman ini? Tindakan ini tidak dapat dibatalkan.")) {
                try {
                    await axios.delete(url);
                    alert("Data riwayat pengiriman berhasil dihapus!");
                    loadTableDistribusi(); // Refresh data tabel secara otomatis tanpa reload page
                } catch (error) {
                    console.error("Gagal menghapus data distribusi:", error);
                    alert("Gagal menghapus data dari server.");
                }
            }
        }
    </script>
</body>
</html>