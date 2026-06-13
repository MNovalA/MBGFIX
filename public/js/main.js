// public/js/main.js

document.addEventListener("DOMContentLoaded", () => {
    // Jalankan loader hanya jika elemen container ada di halaman tersebut
    if (document.getElementById('dapur-container')) loadData('/api/dapurs', 'dapur-container', renderDapur);
    if (document.getElementById('sekolah-container')) loadData('/api/sekolahs', 'sekolah-container', renderSekolah);
    if (document.getElementById('inventory-container')) loadData('/api/inventories', 'inventory-container', renderInventory);
    if (document.getElementById('menu-container')) loadData('/api/menus', 'menu-container', renderMenu);
    if (document.getElementById('distribusi-container')) loadData('/api/shipments', 'distribusi-container', renderShipment);
});

// --- ENGINE: PENGAMBIL DATA ---
async function loadData(url, containerId, renderFunction) {
    try {
        const response = await axios.get(url);
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        renderFunction(container, response.data);
    } catch (error) {
        console.error("Error fetching data:", error);
    }
}

// --- RENDERER: Dapur ---
function renderDapur(container, data) {
    data.forEach(item => {
        container.innerHTML += `
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-[#113F67] text-xl">${item.nama_dapur}</h3>
                    <p class="text-gray-500 mt-1"><i class="fa-solid fa-location-dot text-sm mr-1"></i> ${item.lokasi}</p>
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t border-gray-50 pt-4">
                    <a href="/kelola/dapur?id=${item.id_dapur}" class="text-gray-400 hover:text-[#33A1E0] transition-all text-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <button onclick="deleteItem('/api/dapurs/${item.id_dapur}')" class="text-gray-400 hover:text-red-500 transition-all text-sm">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </div>
            </div>
        `;
    });
}

// --- RENDERER: Sekolah ---
function renderSekolah(container, data) {
    data.forEach(item => {
        container.innerHTML += `
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-1 rounded-md uppercase">${item.jenjang || 'SD'}</span>
                    <h3 class="font-bold text-[#113F67] text-xl mt-2">${item.nama_sekolah}</h3>
                    <p class="text-gray-500 text-sm mt-1"><i class="fa-solid fa-users text-sm mr-1"></i> Siswa: ${item.jumlah_siswa}</p>
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t border-gray-50 pt-4">
                    <a href="/kelola/sekolah?id=${item.id}" class="text-gray-400 hover:text-[#33A1E0] transition-all text-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <button onclick="deleteItem('/api/sekolahs/${item.id}')" class="text-gray-400 hover:text-red-500 transition-all text-sm">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </div>
            </div>
        `;
    });
}

// --- RENDERER: Inventory ---
function renderInventory(container, data) {
    data.forEach(item => {
        container.innerHTML += `
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-[#113F67] text-xl">${item.nama_bahan}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fa-solid fa-box text-sm mr-1"></i> Stok: <span class="font-bold text-[#113F67]">${item.stok}</span> ${item.satuan}
                    </p>
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t border-gray-50 pt-4">
                    <a href="/kelola/inventory?id=${item.id_inventory}" class="text-gray-400 hover:text-[#33A1E0] transition-all text-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <button onclick="deleteItem('/api/inventories/${item.id_inventory}')" class="text-gray-400 hover:text-red-500 transition-all text-sm">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </div>
            </div>
        `;
    });
}

// --- RENDERER: Menu ---
function renderMenu(container, data) {
    data.forEach(item => {
        container.innerHTML += `
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-[#113F67] text-xl">${item.nama_paket}</h3>
                    <p class="text-sm text-gray-500 mt-1"><i class="fa-solid fa-utensils text-sm mr-1"></i> Menu Gizi Seimbang</p>
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t border-gray-50 pt-4">
                    <a href="/kelola/menu?id=${item.id}" class="text-gray-400 hover:text-[#33A1E0] transition-all text-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <button onclick="deleteItem('/api/menus/${item.id}')" class="text-gray-400 hover:text-red-500 transition-all text-sm">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </div>
            </div>
        `;
    });
}

// --- RENDERER: Distribusi ---
function renderShipment(container, data) {
    data.forEach(item => {
        // Logika pewarnaan badge status kirim
        let statusColor = "bg-yellow-50 text-yellow-600";
        if(item.status_kirim === "Selesai" || item.status_kirim === "Diterima") {
            statusColor = "bg-green-50 text-green-600";
        } else if(item.status_kirim === "Gagal") {
            statusColor = "bg-red-50 text-red-600";
        }

        container.innerHTML += `
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start">
                        <h3 class="font-bold text-[#113F67] text-lg">Ke: ${item.nama_sekolah || 'Sekolah'}</h3>
                        <span class="${statusColor} text-[10px] font-bold px-2 py-1 rounded-md">${item.status_kirim}</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-2"><i class="fa-solid fa-truck text-sm mr-1"></i> Total: ${item.jumlah_porsi} Porsi</p>
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t border-gray-50 pt-4">
                    <a href="/kelola/shipment?id=${item.id}" class="text-gray-400 hover:text-[#33A1E0] transition-all text-sm">
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </a>
                    <button onclick="deleteItem('/api/shipments/${item.id}')" class="text-gray-400 hover:text-red-500 transition-all text-sm">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </div>
            </div>
        `;
    });
}

// --- GLOBAL ACTION: Delete ---
window.deleteItem = async (url) => {
    if (confirm('Yakin ingin menghapus data ini?')) {
        try {
            await axios.delete(url);
            alert('Data berhasil dihapus');
            location.reload(); // Refresh halaman untuk memperbarui komponen visual
        } catch (e) {
            console.error(e);
            alert('Gagal menghapus data. Periksa relasi tabel database Anda.');
        }
    }
};

// --- GLOBAL ACTION: Create / Update ---
window.saveData = async (url, formData, redirectUrl = null) => {
    try {
        await axios.post(url, formData);
        alert('Data berhasil disimpan');
        if (redirectUrl) {
            window.location.href = redirectUrl; // Redirect ke halaman kustom jika disertakan
        } else {
            window.location.reload();
        }
    } catch (e) {
        console.error(e);
        alert('Gagal menyimpan data, periksa kembali inputan kamu.');
    }
};