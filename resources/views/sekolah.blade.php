<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MBG - Daftar Sekolah Binaaan</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  </head>
  <body class="bg-[#F8FAFC]">
    
    <nav class="bg-white shadow-sm py-4 px-10 flex justify-between items-center sticky top-0 z-50">
      <div class="flex items-center gap-2">
        <i class="fa-solid fa-shop text-[#113F67] text-2xl"></i>
        <span class="font-bold text-[#113F67] tracking-tight uppercase">Program Nasional MBG</span>
      </div>
      <div class="flex gap-8 items-center font-medium text-gray-600">
        <a href="{{ url('/') }}" class="hover:text-[#33A1E0]">Beranda</a>
        <a href="{{ url('/sekolahs') }}" class="bg-[#113F67] text-white px-4 py-2 rounded-full text-sm">School</a>
        <a href="{{ url('/dapurs') }}" class="hover:text-[#33A1E0]">Dapur</a>
        <a href="{{ url('/menus') }}" class="hover:text-[#33A1E0]">Menu</a>
        <a href="{{ url('/inventories') }}" class="hover:text-[#33A1E0]">Bahan Baku</a>
        <a href="{{ url('/shipments') }}" class="hover:text-[#33A1E0]">Distribusi</a>
      </div>
    </nav>

    <header class="max-w-[1400px] mx-auto mt-10 px-10">
      <div class="flex justify-between items-end">
        <div>
          <h2 class="text-4xl font-black text-[#113F67]">Daftar Sekolah</h2>
          <p class="text-gray-500 mt-2">Memantau data sekolah penerima manfaat program Makan Bergizi Gratis.</p>
        </div>
        <a href="{{ url('/kelola/sekolah') }}" class="bg-[#33A1E0] text-white px-6 py-3 rounded-2xl font-bold flex items-center gap-2 hover:bg-[#113F67] transition-all">
          <i class="fa-solid fa-plus"></i> Kelola Data Sekolah
        </a>
      </div>
    </header>

    <main class="max-w-[1400px] mx-auto p-10">
      <div id="sekolah-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="col-span-full text-center py-20 opacity-50">
            <i class="fa-solid fa-circle-notch animate-spin text-[#33A1E0] text-4xl mb-4"></i>
            <p class="text-[#113F67] font-bold italic">Memuat data sekolah...</p>
        </div>
      </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <script>
        // Pengecekan aman agar tidak memicu Uncaught TypeError jika tag meta belum ke-load
        const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
        if (csrfTokenElement) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfTokenElement.getAttribute('content');
        } else {
            console.warn("Peringatan: Meta tag csrf-token tidak ditemukan.");
        }
    </script>

    <script src="{{ asset('js/main.js') }}"></script>

  </body>
</html>