<!-- Views/partials/customerSidebar.php -->
<div class="bg-white rounded-lg shadow-md p-4 space-y-2">
    <a href="/customer/dashboard" class="block px-4 py-2 rounded hover:bg-gray-100 <?= $_SERVER['REQUEST_URI'] == '/customer/dashboard' ? 'bg-gray-200 font-semibold' : '' ?>">
        👤 Account Dashboard
    </a>
    <a href="/customer/address" class="block px-4 py-2 rounded hover:bg-gray-100 <?= $_SERVER['REQUEST_URI'] == '/customer/address' ? 'bg-gray-200 font-semibold' : '' ?>">
        📍 Address Book
    </a>
    <a href="/customer/orders" class="block px-4 py-2 rounded hover:bg-gray-100 <?= $_SERVER['REQUEST_URI'] == '/customer/orders' ? 'bg-gray-200 font-semibold' : '' ?>">
        🧾 Orders
    </a>
    <a href="/auth/logout" class="block px-4 py-2 text-red-500 rounded hover:bg-red-100 mt-4">
        🚪 Đăng xuất
    </a>
</div>
