<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 antialiased font-sans m-0 p-0">

    <div class="flex min-h-screen">

        <div class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between p-6 shrink-0">
            <div>
                <div class="flex items-center justify-between mb-8 px-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center text-white font-bold text-sm">D</div>
                        <span class="text-lg font-bold text-gray-900 tracking-tight">ERP</span>
                    </div>
                    <button type="button" class="text-gray-400 hover:text-gray-900 text-sm">📋</button>
                </div>

                <nav class="space-y-1">
                    <button type="button" onclick="switchPage('users')" id="btn-users"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-medium transition text-left text-gray-400 hover:bg-gray-50 hover:text-gray-900">
                        <span>👤</span> Users
                    </button>
                    <button type="button" onclick="switchPage('customers')" id="btn-customers"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-semibold transition text-left bg-gray-100 text-gray-900">
                        <span>👥</span> Customers
                    </button>
                    <button type="button" onclick="switchPage('services')" id="btn-services"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-medium transition text-left text-gray-400 hover:bg-gray-50 hover:text-gray-900">
                        <span>🌐</span> Services
                    </button>
                    <button type="button" onclick="switchPage('subscriptions')" id="btn-subscriptions"
                        class="w-full flex items-center gap-4 px-4 py-3 rounded-xl text-sm font-medium transition text-left text-gray-400 hover:bg-gray-50 hover:text-gray-900">
                        <span>📄</span> Subscription
                    </button>
                </nav>
            </div>

            <button type="button" class="flex items-center gap-4 px-4 py-3 text-sm font-medium text-gray-400 hover:text-red-600 transition text-left w-full">
                <span>🚪</span> Sign Out
            </button>
        </div>

        <div class="flex-1 p-10 overflow-y-auto relative">

            <div id="page-customers" class="page-content">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Customers</h1>
                    <button type="button" onclick="openModal('customer')"
                        class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl text-xs font-medium transition flex items-center gap-1.5 shadow-sm">
                        <span>+</span> Add Data
                    </button>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-visible">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 text-xs font-semibold uppercase bg-gray-50/50 border-b border-gray-100">
                                <th class="py-3.5 px-6">Customer ID</th>
                                <th class="py-3.5 px-6">Customer Name</th>
                                <th class="py-3.5 px-6">Email</th>
                                <th class="py-3.5 px-6">Address</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-customers" class="text-sm text-gray-600 divide-y divide-gray-100">
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="page-services" class="page-content hidden">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Services</h1>
                    <button type="button" onclick="openModal('service')"
                        class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl text-xs font-medium transition flex items-center gap-1.5 shadow-sm">
                        <span>+</span> Add Data
                    </button>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-visible">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 text-xs font-semibold uppercase bg-gray-50/50 border-b border-gray-100">
                                <th class="py-3.5 px-6">Service Name</th>
                                <th class="py-3.5 px-6">Price</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-services" class="text-sm text-gray-600 divide-y divide-gray-100">
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="page-subscriptions" class="page-content hidden">
                <div class="flex justify-between items-center mb-8">
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Subscriptions</h1>
                    <button type="button" onclick="openModal('subscription')"
                        class="bg-gray-900 hover:bg-black text-white px-4 py-2 rounded-xl text-xs font-medium transition flex items-center gap-1.5 shadow-sm">
                        <span>+</span> Add Data
                    </button>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-visible">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-gray-400 text-xs font-semibold uppercase bg-gray-50/50 border-b border-gray-100">
                                <th class="py-3.5 px-6">Customer Name</th>
                                <th class="py-3.5 px-6">Services</th>
                                <th class="py-3.5 px-6">Services Period</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="table-subscriptions" class="text-sm text-gray-600 divide-y divide-gray-100">
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="page-users" class="page-content hidden">
                <h1 class="text-xl font-bold text-gray-900 mb-2">Users</h1>
                <p class="text-sm text-gray-400">Halaman manajemen user ERP internal.</p>
            </div>

        </div>
    </div>

    <div id="modal-container" class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center hidden p-4 z-50">

        <div id="modal-customer" class="bg-white text-gray-900 w-full max-w-md rounded-2xl p-6 shadow-xl hidden">
            <h3 class="text-base font-bold text-center text-gray-900 mb-6">Add Customer</h3>
            <form id="form-customer" onsubmit="submitForm(event, 'customers')" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Customer ID</label>
                    <input type="text" name="customer_id" placeholder="Enter customer ID" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Customer Name</label>
                    <input type="text" name="name" placeholder="Enter customer name" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                    <input type="email" name="email" placeholder="Enter email" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Phone</label>
                    <input type="text" name="phone" placeholder="Enter phone" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Address</label>
                    <input type="text" name="address" placeholder="Enter address" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-xs font-medium text-gray-400 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-medium transition">Submit</button>
                </div>
            </form>
        </div>

        <div id="modal-service" class="bg-white text-gray-900 w-full max-w-md rounded-2xl p-6 shadow-xl hidden">
            <h3 class="text-base font-bold text-center text-gray-900 mb-6">Add Service</h3>
            <form id="form-service" onsubmit="submitForm(event, 'services')" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Service Name</label>
                    <input type="text" name="name" placeholder="Enter service name" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Price</label>
                    <input type="number" name="price" placeholder="Enter price" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                    <textarea name="description" rows="3" placeholder="Enter description" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-xs font-medium text-gray-400 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-medium transition">Submit</button>
                </div>
            </form>
        </div>

        <div id="modal-subscription" class="bg-white text-gray-900 w-full max-w-md rounded-2xl p-6 shadow-xl hidden">
            <h3 class="text-base font-bold text-center text-gray-900 mb-6">Add Subscription</h3>
            <form id="form-subscription" onsubmit="submitForm(event, 'subscriptions')" class="space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Customer</label>
                    <select name="customer_id" id="modal-select-customer" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required></select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Service</label>
                    <select name="service_id" id="modal-select-service" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required></select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                        <input type="date" name="start_date" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                        <input type="date" name="end_date" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                    <select name="status" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="trial">Trial</option>
                        <option value="isolir">Isolir</option>
                        <option value="dismantle">Dismantle</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-xs font-medium text-gray-400 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-medium transition">Submit</button>
                </div>
            </form>
        </div>

    </div>


    <div id="modal-edit-container" class="fixed inset-0 bg-black/40 backdrop-blur-xs flex items-center justify-center hidden p-4 z-50">
        
        <div id="modal-edit-customer" class="bg-white text-gray-900 w-full max-w-md rounded-2xl p-6 shadow-xl hidden">
            <h3 class="text-base font-bold text-center text-gray-900 mb-6">Edit Customer</h3>
            <form id="form-edit-customer" onsubmit="submitEditForm(event, 'customers')" class="space-y-4">
                <input type="hidden" name="id" id="edit-cust-id">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Customer ID (Locked)</label>
                    <input type="text" id="edit-cust-customer-id" class="w-full px-3.5 py-2 bg-gray-100 border border-gray-200 rounded-xl text-sm focus:outline-none text-gray-400" readonly>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Customer Name</label>
                    <input type="text" name="name" id="edit-cust-name" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Email</label>
                    <input type="email" name="email" id="edit-cust-email" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Phone</label>
                    <input type="text" name="phone" id="edit-cust-phone" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Address</label>
                    <input type="text" name="address" id="edit-cust-address" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900">
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-medium text-gray-400 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-medium transition">Save Changes</button>
                </div>
            </form>
        </div>

        <div id="modal-edit-service" class="bg-white text-gray-900 w-full max-w-md rounded-2xl p-6 shadow-xl hidden">
            <h3 class="text-base font-bold text-center text-gray-900 mb-6">Edit Service</h3>
            <form id="form-edit-service" onsubmit="submitEditForm(event, 'services')" class="space-y-4">
                <input type="hidden" name="id" id="edit-serv-id">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Service Name</label>
                    <input type="text" name="name" id="edit-serv-name" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Price (Rp)</label>
                    <input type="number" name="price" id="edit-serv-price" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                    <textarea name="description" id="edit-serv-description" rows="3" class="w-full px-3.5 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-900"></textarea>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-xs font-medium text-gray-400 hover:text-gray-900">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-gray-900 hover:bg-black text-white rounded-xl text-xs font-medium transition">Save Changes</button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>