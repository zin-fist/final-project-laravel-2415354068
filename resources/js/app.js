const API_URL = "http://127.0.0.1:8000/api";

// =========================================================================
// 1. Logika Klik Pindah Navigasi Sidebar (Single Page Application)
// =========================================================================
window.switchPage = function(pageId) {
    document.querySelectorAll('.page-content').forEach(page => page.classList.add('hidden'));
    document.getElementById(`page-${pageId}`).classList.remove('hidden');

    document.querySelectorAll('nav button').forEach(btn => {
        btn.classList.remove('bg-gray-100', 'text-gray-900', 'font-semibold');
        btn.classList.add('text-gray-400', 'font-medium');
    });
    
    const activeBtn = document.getElementById(`btn-${pageId}`);
    if (activeBtn) {
        activeBtn.classList.remove('text-gray-400', 'font-medium');
        activeBtn.classList.add('bg-gray-100', 'text-gray-900', 'font-semibold');
    }

    closeAllDropdowns();

    if (pageId === 'customers') fetchCustomers();
    if (pageId === 'services') fetchServices();
    if (pageId === 'subscriptions') fetchSubscriptions();
};

// =========================================================================
// 2. Logika Toggle Dropdown Aksi Melayang
// =========================================================================
window.toggleDropdown = function(event, dropdownId) {
    event.stopPropagation();
    const targetDropdown = document.getElementById(dropdownId);
    if (!targetDropdown) return;
    const isCurrentlyHidden = targetDropdown.classList.contains('hidden');
    
    closeAllDropdowns();

    if (isCurrentlyHidden) {
        targetDropdown.classList.remove('hidden');
    }
};

function closeAllDropdowns() {
    document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
}

document.addEventListener('click', () => closeAllDropdowns());

// Helper: Normalisasi Data API
function getArrayData(res) {
    if (!res) return [];
    if (Array.isArray(res)) return res;
    if (res.data && Array.isArray(res.data)) return res.data;
    return [];
}

// =========================================================================
// 3. Logika Jendela Pop-Up Modal Formulir Add Data
// =========================================================================
window.openModal = function(type) {
    document.getElementById('modal-container').classList.remove('hidden');
    document.getElementById(`modal-${type}`).classList.remove('hidden');

    if (type === 'subscription') {
        fetch(`${API_URL}/customers`).then(r => r.json()).then(res => {
            const select = document.getElementById('modal-select-customer');
            const items = getArrayData(res);
            select.innerHTML = items.map(c => `<option value="${c.id}">${c.name || 'No Name'}</option>`).join('');
        });
        fetch(`${API_URL}/services`).then(r => r.json()).then(res => {
            const select = document.getElementById('modal-select-service');
            const items = getArrayData(res);
            select.innerHTML = items.map(s => `<option value="${s.id}">${s.name || 'No Service'}</option>`).join('');
        });
    }
};

window.closeModal = function() {
    document.getElementById('modal-container').classList.add('hidden');
    document.querySelectorAll('#modal-container > div').forEach(m => m.classList.add('hidden'));
};

// =========================================================================
// 4. Ambil Data Customers (IMPROVEMENT: Pembatasan Action Sesuai Modul Part 2)
// =========================================================================
function fetchCustomers() {
    fetch(`${API_URL}/customers`)
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('table-customers');
            if (!tbody) return;

            const items = getArrayData(res);
            if (items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-6 text-gray-400 text-xs">Belum ada data customer.</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map((c, index) => {
                let rawStatus = String(c.status || 'active').trim().toLowerCase();
                // Menyesuaikan penamaan dengan controller backend milikmu ('active' / 'expired')
                const currentStatus = (rawStatus === 'active') ? 'active' : 'expired';
                const badgeColor = currentStatus === 'active' ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50';

                // Escape string aman agar tanda petik data tidak merusak trigger onclick JavaScript
                const safeName = (c.name || '').replace(/'/g, "\\'");
                const safeEmail = (c.email || '').replace(/'/g, "\\'");
                const safePhone = (c.phone || '').replace(/'/g, "\\'");
                const safeAddress = (c.address || '').replace(/'/g, "\\'");

                // MODUL PART 2: Status active & inactive dikunci di tabel (tombol ubah status ditiadakan) 
                let dropdownContent = `
                    <div class="px-4 py-1.5 text-[10px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status Locked</div>
                    
                    <button type="button" onclick="openEditCustomer(${c.id}, '${c.customer_id}', '${safeName}', '${safeEmail}', '${safePhone}', '${safeAddress}')" class="w-full px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2 text-left">
                        📝 Edit
                    </button>
                    
                    <button type="button" onclick="deleteData(${c.id}, 'customers')" class="w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 flex items-center gap-2 text-left">
                        🗑️ Delete
                    </button>
                `;

                return `
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-4 px-6 font-mono text-xs text-gray-400">${c.customer_id || '-'}</td>
                        <td class="py-4 px-6 font-medium text-gray-900">${c.name || 'N/A'}</td>
                        <td class="py-4 px-6 text-gray-500">${c.email || '-'}</td>
                        <td class="py-4 px-6 text-gray-500">${c.address || '-'}</td>
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase ${badgeColor}">${currentStatus}</span>
                        </td>
                        <td class="py-4 px-6 text-center relative overflow-visible">
                            <button type="button" onclick="toggleDropdown(event, 'drop-cust-${index}')" class="text-gray-400 hover:text-gray-900 font-bold p-1 text-base">☰</button>
                            <div id="drop-cust-${index}" class="dropdown-menu hidden absolute right-12 top-2 w-40 bg-white border border-gray-100 rounded-xl shadow-lg z-50 py-1 text-left">
                                ${dropdownContent}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }).catch(err => console.error("Error customers:", err));
}

// =========================================================================
// 5. Ambil Data Services (IMPROVEMENT: Pembatasan Action Sesuai Modul Part 2)
// =========================================================================
function fetchServices() {
    fetch(`${API_URL}/services`)
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('table-services');
            if (!tbody) return;

            const items = getArrayData(res);
            if (items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="text-center py-6 text-gray-400 text-xs">Belum ada data service.</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map((s, index) => {
                let rawStatus = String(s.status || 'active').trim().toLowerCase();
                const currentStatus = (rawStatus === '1' || rawStatus === 'true' || rawStatus === 'active') ? 'active' : 'inactive';
                const badgeColor = currentStatus === 'active' ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50';

                const safeName = (s.name || '').replace(/'/g, "\\'");
                const safeDesc = (s.description || '').replace(/'/g, "\\'");

                // MODUL PART 2: Status active & inactive dikunci di tabel (tombol ubah status ditiadakan) 
                let dropdownContent = `
                    <div class="px-4 py-1.5 text-[10px] font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">Status Locked</div>
                    
                    <button type="button" onclick="openEditService(${s.id}, '${safeName}', ${s.price || 0}, '${safeDesc}')" class="w-full px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2 text-left">
                        📝 Edit
                    </button>
                    
                    <button type="button" onclick="deleteData(${s.id}, 'services')" class="w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 flex items-center gap-2 text-left">
                        🗑️ Delete
                    </button>
                `;

                return `
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-4 px-6 font-medium text-gray-900">${s.name || 'N/A'}</td>
                        <td class="py-4 px-6 text-gray-500">Rp ${parseFloat(s.price || 0).toLocaleString('id-ID')}</td>
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase ${badgeColor}">${currentStatus}</span>
                        </td>
                        <td class="py-4 px-6 text-center relative overflow-visible">
                            <button type="button" onclick="toggleDropdown(event, 'drop-serv-${index}')" class="text-gray-400 hover:text-gray-900 font-bold p-1 text-base">☰</button>
                            <div id="drop-serv-${index}" class="dropdown-menu hidden absolute right-12 top-2 w-40 bg-white border border-gray-100 rounded-xl shadow-lg z-50 py-1 text-left">
                                ${dropdownContent}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }).catch(err => console.error("Error services:", err));
}

// =========================================================================
// 6. Ambil Data Subscriptions (IMPROVEMENT: Pembatasan Action Sesuai Modul Part 2)
// =========================================================================
function fetchSubscriptions() {
    fetch(`${API_URL}/subscriptions`)
        .then(res => res.json())
        .then(res => {
            const tbody = document.getElementById('table-subscriptions');
            if (!tbody) return;

            const items = getArrayData(res);
            if (items.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-6 text-gray-400 text-xs">Belum ada data subscription.</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map((s, index) => {
                const currentStatus = String(s.status || 'active').toLowerCase().trim();

                let badgeColor = 'text-gray-600 bg-gray-100';
                if (currentStatus === 'active') badgeColor = 'text-green-600 bg-green-50';
                else if (currentStatus === 'trial') badgeColor = 'text-blue-600 bg-blue-50';
                else if (currentStatus === 'isolir') badgeColor = 'text-yellow-600 bg-yellow-50';
                else if (currentStatus === 'inactive') badgeColor = 'text-red-600 bg-red-50';
                else if (currentStatus === 'dismantle') badgeColor = 'text-gray-500 bg-gray-200';

                let dropdownContent = '';

                // MODUL PART 2: Subscription status dismantle dikunci mati total dari semua aksi [cite: 7, 12]
                if (currentStatus === 'dismantle') {
                    dropdownContent = `<div class="px-4 py-3 text-xs text-red-500 text-center font-medium">🚫 Action Locked (Dismantled)</div>`;
                } else {
                    // MODUL PART 2: Perubahan status hanya diizinkan ke selain status saat ini [cite: 13, 14]
                    if (currentStatus !== 'active') {
                        dropdownContent += `<button type="button" onclick="updateSubStatus(${s.id}, 'active')" class="w-full px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2 text-left">🔑 Active</button> `;
                    }
                    if (currentStatus !== 'inactive') {
                        dropdownContent += `<button type="button" onclick="updateSubStatus(${s.id}, 'inactive')" class="w-full px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2 text-left">🔓 Inactive</button> `;
                    }
                    if (currentStatus !== 'trial') {
                        dropdownContent += `<button type="button" onclick="updateSubStatus(${s.id}, 'trial')" class="w-full px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2 text-left">⏳ Trial</button> `;
                    }
                    if (currentStatus !== 'isolir') {
                        dropdownContent += `<button type="button" onclick="updateSubStatus(${s.id}, 'isolir')" class="w-full px-4 py-2 text-xs text-gray-700 hover:bg-gray-50 flex items-center gap-2 text-left">🚫 Isolir</button> `;
                    }
                    dropdownContent += `<button type="button" onclick="updateSubStatus(${s.id}, 'dismantle')" class="w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 flex items-center gap-2 text-left">⚙️ Dismantle</button> `;
                }

                return `
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-4 px-6 font-medium text-gray-900">${s.customer?.name || 'N/A'}</td>
                        <td class="py-4 px-6 text-gray-500">${s.service?.name || 'N/A'}</td>
                        <td class="py-4 px-6 text-gray-400 text-xs">${s.start_date || '-'} s/d ${s.end_date || '-'}</td>
                        <td class="py-4 px-6">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold uppercase ${badgeColor}">${currentStatus}</span>
                        </td>
                        <td class="py-4 px-6 text-center relative overflow-visible">
                            <button type="button" onclick="toggleDropdown(event, 'drop-sub-${index}')" class="text-gray-400 hover:text-gray-900 font-bold p-1 text-base">☰</button>
                            <div id="drop-sub-${index}" class="dropdown-menu hidden absolute right-12 top-2 w-40 bg-white border border-gray-100 rounded-xl shadow-lg z-50 py-1 text-left">
                                ${dropdownContent}
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }).catch(err => console.error("Error subscriptions:", err));
}

// =========================================================================
// 7. Aksi Eksekusi Update Status & Delete Data (SINKRON PARAMETER LAREVEL)
// =========================================================================
window.updateSubStatus = function(id, newStatus) {
    fetch(`${API_URL}/subscriptions/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ status: newStatus.toLowerCase() })
    })
    .then(async res => {
        const result = await res.json();
        if (!res.ok) {
            alert(result.errors ? JSON.stringify(result.errors) : result.message);
            return;
        }
        fetchSubscriptions();
    });
};

window.deleteData = function(id, endpoint) {
    if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) return;

    fetch(`${API_URL}/${endpoint}/${id}`, {
        method: 'DELETE',
        headers: { 'Accept': 'application/json' }
    })
    .then(async res => {
        const result = await res.json();
        if (!res.ok) {
            // Menampilkan validasi penolakan modul part 2 jika entitas punya hubungan transaksi [cite: 5]
            alert(result.errors ? JSON.stringify(result.errors) : result.message);
            return;
        }
        if (endpoint === 'customers') fetchCustomers();
        if (endpoint === 'services') fetchServices();
    }).catch(err => alert("Terjadi error koneksi server."));
};

// =========================================================================
// 8. Logika Kirim Data Form Tambah Baru (POST)
// =========================================================================
window.submitForm = function(event, endpoint) {
    event.preventDefault();
    const formData = new FormData(event.target);
    let data = Object.fromEntries(formData.entries());

    if (data.status) {
        data.status = String(data.status).toLowerCase().trim();
    }

    fetch(`${API_URL}/${endpoint}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(async res => {
        const result = await res.json();
        if (!res.ok) {
            alert(result.errors ? JSON.stringify(result.errors) : result.message);
            return;
        }
        closeModal();
        event.target.reset();
        
        if (endpoint === 'customers') fetchCustomers();
        if (endpoint === 'services') fetchServices();
        if (endpoint === 'subscriptions') fetchSubscriptions();
    }).catch(err => alert('Terjadi error saat mengirim data'));
};

// =========================================================================
// 9. LOGIKA HANDLER EDIT DATA (MODUL PART 2 COMPLIANT)
// =========================================================================

// Pembuka Form Edit Customer & Inject Data Lama ke Form Input
window.openEditCustomer = function(id, customerId, name, email, phone, address) {
    document.getElementById('modal-edit-container').classList.remove('hidden');
    document.getElementById('modal-edit-customer').classList.remove('hidden');
    
    document.getElementById('edit-cust-id').value = id;
    document.getElementById('edit-cust-customer-id').value = customerId;
    document.getElementById('edit-cust-name').value = name;
    document.getElementById('edit-cust-email').value = email === 'null' || email === 'undefined' ? '' : email;
    document.getElementById('edit-cust-phone').value = phone === 'null' || phone === 'undefined' ? '' : phone;
    document.getElementById('edit-cust-address').value = address === 'null' || address === 'undefined' ? '' : address;
};

// Pembuka Form Edit Service & Inject Data Lama ke Form Input
window.openEditService = function(id, name, price, description) {
    document.getElementById('modal-edit-container').classList.remove('hidden');
    document.getElementById('modal-edit-service').classList.remove('hidden');
    
    document.getElementById('edit-serv-id').value = id;
    document.getElementById('edit-serv-name').value = name;
    document.getElementById('edit-serv-price').value = price;
    document.getElementById('edit-serv-description').value = description === 'null' || description === 'undefined' ? '' : description;
};

// Penutup Modal Edit Global
window.closeEditModal = function() {
    document.getElementById('modal-edit-container').classList.add('hidden');
    document.getElementById('modal-edit-customer').classList.add('hidden');
    document.getElementById('modal-edit-service').classList.add('hidden');
};

// Eksekutor Submit Perubahan Data ke API Laravel (Method PUT)
window.submitEditForm = function(event, endpoint) {
    event.preventDefault();
    const formData = new FormData(event.target);
    let data = Object.fromEntries(formData.entries());
    
    const id = data.id; // Ambil ID entitas dari hidden input form

    fetch(`${API_URL}/${endpoint}/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(async res => {
        const result = await res.json();
        if (!res.ok) {
            alert(result.errors ? JSON.stringify(result.errors) : result.message);
            return;
        }
        
        closeEditModal();
        event.target.reset();
        
        if (endpoint === 'customers') fetchCustomers();
        if (endpoint === 'services') fetchServices();
    })
    .catch(err => {
        console.error(err);
        alert('Terjadi error saat memperbarui data');
    });
};

document.addEventListener('DOMContentLoaded', () => {
    fetchCustomers();
});