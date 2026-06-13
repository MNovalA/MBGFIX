<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MBG - Monitoring Distribusi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-[#F8FAFC]">

    <nav class="bg-white shadow-sm py-4 px-10 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-truck-ramp-box text-[#113F67] text-2xl"></i>
            <span class="font-bold text-[#113F67] tracking-tight uppercase">Program Nasional MBG</span>
        </div>
        <div class="flex gap-8 items-center font-medium text-gray-600">
            <a href="{{ url('/') }}" class="hover:text-[#33A1E0]">Beranda</a>
            <a href="{{ url('/sekolahs') }}" class="hover:text-[#33A1E0]">Sekolah</a>
            <a href="{{ url('/dapurs') }}" class="hover:text-[#33A1E0]">Dapur</a>
            <a href="{{ url('/menus') }}" class="hover:text-[#33A1E0]">Menu</a>
            <a href="{{ url('/inventories') }}" class="hover:text-[#33A1E0]">Bahan Baku</a>
            <a href="{{ url('/shipments') }}" class="bg-[#113F67] text-white px-4 py-2 rounded-full text-sm">Distribusi</a>
        </div>
    </nav>

    <header class="max-w-[1400px] mx-auto mt-10 px-10">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-4xl font-black text-[#113F67]">Status Pengiriman</h2>
                <p class="text-gray-500 mt-2">Pelacakan distribusi makanan bergizi dari dapur ke sekolah tujuan.</p>
            </div>
            <a href="{{ url('/kelola/distribusi') }}" class="bg-[#FFF9AF] text-[#113F67] px-6 py-3 rounded-2xl font-bold flex items-center gap-2 hover:bg-[#113F67] hover:text-white transition-all shadow-md">
                <i class="fa-solid fa-paper-plane"></i> Kirim Paket Baru
            </a>
        </div>
    </header>

    <main class="max-w-[1400px] mx-auto p-10">
        <div id="distribusi-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="col-span-full py-20 text-center">
                <i class="fa-solid fa-truck-fast animate-bounce text-[#33A1E0] text-4xl mb-4"></i>
                <p class="text-gray-400 italic">Melacak armada distribusi...</p>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/main.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetchLiveTracking();
        });

        async function fetchLiveTracking() {
            const container = document.getElementById('distribusi-container');
            
            try {
                const response = await axios.get('/api/shipments');
                const shipments = response.data;

                // Jika data kosong
                if (shipments.length === 0) {
                    container.innerHTML = `
                        <div class="col-span-full bg-white rounded-[35px] border border-gray-100 p-16 text-center shadow-sm">
                            <i class="fa-solid fa-route text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-400 font-medium">Tidak ada paket distribusi aktif hari ini.</p>
                        </div>`;
                    return;
                }

                // Bersihkan kontainer utama
                container.innerHTML = '';

            // Loop data paket menjadi card tracking
                shipments.forEach(item => {
                    // 💡 PERBAIKAN 1: Gunakan item.status_kirim (Sesuai kolom di databasemu)
                    let statusColor = "";
                    let iconStatus = "";
                    let stepLine = "";

                    if (item.status_kirim === "Persiapan") {
                        statusColor = "bg-amber-50 text-amber-600 border-amber-200";
                        iconStatus = "fa-box-open text-amber-500 animate-pulse";
                        stepLine = "border-amber-300";
                    } else if (item.status_kirim === "Perjalanan") {
                        statusColor = "bg-blue-50 text-blue-600 border-blue-200";
                        iconStatus = "fa-truck-fast text-blue-500 animate-infinite";
                        stepLine = "border-blue-300";
                    } else { // Diterima
                        statusColor = "bg-emerald-50 text-emerald-600 border-emerald-200";
                        iconStatus = "fa-circle-check text-emerald-500";
                        stepLine = "border-emerald-300";
                    }

                    container.innerHTML += `
                        <div class="bg-white rounded-[35px] border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all relative overflow-hidden flex flex-col justify-between">
                            
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <span class="text-[10px] font-black uppercase tracking-wider px-3 py-1.5 rounded-full border ${statusColor} flex items-center gap-1.5">
                                        <i class="fa-solid ${iconStatus}"></i> ${item.status_kirim}
                                    </span>
                                    <div class="text-right">
                                        <span class="text-xs text-gray-400 block">Estimasi Sampai</span>
                                        <span class="text-xs font-bold text-gray-700">${item.waktu_sampai || '-'}</span>
                                    </div>
                                </div>

                                <h3 class="text-xl font-bold text-[#113F67] line-clamp-1">${item.nama_sekolah}</h3>
                                <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                    <i class="fa-solid fa-house-chimney text-gray-300"></i> Dari: ${item.nama_dapur}
                                </p>
                                
                                <hr class="my-4 border-gray-100">
                            </div>

                            <div class="bg-gray-50/70 rounded-2xl p-4 flex justify-between items-center">
                                <div>
                                    <span class="text-[10px] text-gray-400 block uppercase font-bold tracking-wider">Paket Menu</span>
                                    <span class="font-bold text-gray-700 text-sm line-clamp-1">${item.nama_menu}</span>
                                </div>
                                <div class="text-right bg-[#113F67] text-white py-2 px-4 rounded-xl shadow-sm">
                                    <span class="block text-[9px] uppercase font-bold tracking-widest text-gray-300">Total</span>
                                    <span class="text-sm font-black">${item.jumlah_porsi} <span class="text-[10px] font-normal">Prs</span></span>
                                </div>
                            </div>

                        </div>
                    `;
                });

            } catch (error) {
                console.error("Gagal melacak jalur distribusi armada:", error);
                container.innerHTML = `
                    <div class="col-span-full bg-red-50 text-red-600 rounded-[35px] p-8 text-center border border-red-100 font-medium italic">
                        Gagal terhubung dengan layanan tracking server API.
                    </div>`;
            }
        }
    </script>
</body>
</html>