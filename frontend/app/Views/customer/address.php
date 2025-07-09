<?php if (!empty($_SESSION['flash_message'])): ?>
    <div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-800 border border-green-300">
        <?= htmlspecialchars($_SESSION['flash_message']) ?>
        <?php unset($_SESSION['flash_message']); ?>
    </div>
<?php endif; ?>

<div class="max-w-6xl mx-auto px-4 py-8 flex gap-6">
    <!-- Sidebar -->
    <aside class="w-64 shrink-0">
        <?php include __DIR__ . '/../partials/customerSidebar.php'; ?>
    </aside>

    <!-- Main content -->
    <main class="flex-1">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">📍 Address Book</h1>

        <?php if (!empty($addresses)): ?>
            <div class="grid gap-4">
                <?php foreach ($addresses as $address): ?>
                    <div x-data="{ edit: false }" class="bg-white p-4 rounded shadow-md hover:shadow-lg transition">
                        <template x-if="!edit">
                            <div class="flex justify-between items-center">
                                <div class="text-gray-700">
                                    <?= htmlspecialchars($address['street']) ?>,
                                    <?= htmlspecialchars($address['city']) ?>,
                                    <?= htmlspecialchars($address['country_code']) ?>
                                </div>
                                <div class="flex gap-4">
                                    <button type="button" @click="edit = true" class="text-blue-600 hover:underline">✏️ Sửa</button>
                                    <form method="POST" action="/customer/deleteAddress" onsubmit="return confirm('Xác nhận xoá địa chỉ?');">
                                        <input type="hidden" name="address_id" value="<?= $address['id'] ?>">
                                        <button type="submit" class="text-red-500 hover:text-red-700 font-medium">🗑 Xoá</button>
                                    </form>
                                </div>
                            </div>
                        </template>

                        <template x-if="edit">
                            <form method="POST" action="/customer/editAddress" class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-4">
                                <input type="hidden" name="id" value="<?= $address['id'] ?>">
                                <input type="text" name="street" value="<?= htmlspecialchars($address['street']) ?>" placeholder="Số nhà, đường" class="border p-2 rounded" required>
                                <input type="text" name="city" value="<?= htmlspecialchars($address['city']) ?>" placeholder="Thành phố" class="border p-2 rounded" required>
                                <input type="text" name="country_code" value="<?= htmlspecialchars($address['country_code']) ?>" placeholder="Quốc gia" class="border p-2 rounded" required>
                                <div class="col-span-full flex gap-2 mt-2">
                                    <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700">Lưu</button>
                                    <button type="button" @click="edit = false" class="text-gray-500 hover:text-gray-700">Huỷ</button>
                                </div>
                            </form>
                        </template>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-600">Bạn chưa có địa chỉ nào.</p>
        <?php endif; ?>

        <div class="mt-8 bg-white rounded shadow p-6 max-w-xl">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">➕ Thêm địa chỉ mới</h2>
            <form method="POST" action="/customer/addAddress" class="grid gap-4">
                <input type="text" name="name" placeholder="Tên" class="border p-2 rounded w-full" required>
                <input type="text" name="phone" placeholder="Số điện thoại" class="border p-2 rounded w-full">
                <input type="text" name="street" placeholder="Số nhà, đường" class="border p-2 rounded w-full" required>
                <input type="text" name="city" placeholder="Thành phố" class="border p-2 rounded w-full" required>
                <input type="text" name="country_code" placeholder="Quốc gia" class="border p-2 rounded w-full" required>
                <textarea name="detail" class="mt-1 p-2 w-full border rounded"></textarea>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 w-fit">Thêm địa chỉ</button>
            </form>
        </div>
    </main>
</div>
