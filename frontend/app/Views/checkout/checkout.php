<div class="container mx-auto p-6 mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Phần 1: Địa chỉ giao hàng -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Địa chỉ giao hàng</h2>
        <?php if (isset($message)): ?>
            <div class="mb-4 p-2 text-green-700 rounded"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" action="/checkout/saveAddress/" x-data="{ showForm: <?php echo empty($addresses) ? 'true' : 'false'; ?> }" x-init="if (!showForm) { selectedAddress = '<?php echo htmlspecialchars($selected_address ?? ''); ?>' }">
            <div x-show="showForm">
                <button type="button" x-on:click="showForm = false" class="mt-2 bg-gray-500 text-white p-2 rounded">Quay lại</button>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Tên người nhận</label>
                    <input type="text" name="name" class="mt-1 p-2 w-full border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                    <input type="tel" name="phone" class="mt-1 p-2 w-full border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Đường/Phố</label>
                    <input type="text" name="street" class="mt-1 p-2 w-full border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Thành phố</label>
                    <input type="text" name="city" class="mt-1 p-2 w-full border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Mã quốc gia</label>
                    <input type="text" name="country_code" class="mt-1 p-2 w-full border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Chi tiết địa chỉ</label>
                    <textarea name="detail" class="mt-1 p-2 w-full border rounded"></textarea>
                </div>
                <button type="submit" name="save_address" class="mt-2 bg-blue-500 text-white p-2 rounded">Lưu địa chỉ</button>
            </div>
            <div x-show="!showForm" x-cloak>
                <?php if ($selected_address): ?>
                    <div class="mb-4 p-2 bg-gray-100 rounded">
                        <p><strong>Địa chỉ đã chọn:</strong></p>
                        <?php $addr = json_decode($selected_address, true); ?>
                        <input type="hidden" name="selected_address" value="<?php echo htmlspecialchars(json_encode($addr)); ?>">
                        <p><?php echo htmlspecialchars((isset($addr['name']) ? $addr['name'] : '') . ' - ' . (isset($addr['phone']) ? $addr['phone'] : '') . ' - ' . (isset($addr['street']) ? $addr['street'] : '') . ', ' . (isset($addr['city']) ? $addr['city'] : '') . ', ' . (isset($addr['country_code']) ? $addr['country_code'] : '') . ' - ' . (isset($addr['detail']) ? $addr['detail'] : '')); ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($addresses)): ?>
                    <h3 class="text-md font-semibold mb-2">Địa chỉ đã lưu:</h3>
                    <ul class="space-y-2">
                        <?php foreach ($addresses as $addr): ?>
                            <li class="p-2 border rounded flex justify-between items-center">
                                <span class="flex-1">
                                    <?php echo htmlspecialchars((isset($addr['name']) ? $addr['name'] : '') . ' - ' . (isset($addr['phone']) ? $addr['phone'] : '') . ' - ' . (isset($addr['street']) ? $addr['street'] : '') . ', ' . (isset($addr['city']) ? $addr['city'] : '') . ', ' . (isset($addr['country_code']) ? $addr['country_code'] : '') . ' - ' . (isset($addr['detail']) ? $addr['detail'] : '')); ?>
                                </span>
                                <div class="flex space-x-2">
                                    <form method="POST" action="/checkout/saveAddress/" style="display:inline;">
                                        <input type="hidden" name="select_address_id" value="<?php echo $addr['id']; ?>">
                                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Chọn</button>
                                    </form>
                                    <form method="POST" action="/checkout/deleteAddress/" style="display:inline;">
                                        <input type="hidden" name="delete_address_id" value="<?php echo $addr['id']; ?>">
                                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">Xóa</button>
                                    </form>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Chưa có địa chỉ nào được lưu.</p>
                    <button type="button" x-on:click="showForm = true" class="mt-2 bg-green-500 text-white p-2 rounded">Thêm địa chỉ mới</button>
                <?php endif; ?>
                <?php if (!empty($addresses)): ?>
                    <button type="button" x-on:click="showForm = true" class="mt-2 bg-green-500 text-white p-2 rounded">Thêm địa chỉ mới</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Phần 2: Phương thức giao hàng và thanh toán -->
    <div class="bg-white p-4 rounded shadow">
        <div class="mb-5">
            <h2 class="text-lg font-bold mb-4">Phương thức giao hàng</h2>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="radio" name="shipping_method" value="standard" class="mr-2" checked>
                    Giao hàng tiêu chuẩn (2-5 ngày)
                </label>
                <label class="flex items-center">
                    <input type="radio" name="shipping_method" value="express" class="mr-2">
                    Giao hàng nhanh (1-2 ngày)
                </label>
                <label class="flex items-center">
                    <input type="radio" name="shipping_method" value="economy" class="mr-2">
                    Giao hàng tiết kiệm (5-7 ngày)
                </label>
            </div>
        </div>
        <div>
            <h2 class="text-lg font-bold mb-4">Phương thức thanh toán</h2>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="radio" name="payment_method" value="cod" class="mr-2" checked>
                    Thanh toán khi nhận hàng (COD)
                </label>
                <label class="flex items-center">
                    <input type="radio" name="payment_method" value="bank" class="mr-2">
                    Thanh toán qua ngân hàng
                </label>
                <label class="flex items-center">
                    <input type="radio" name="payment_method" value="visa" class="mr-2">
                    Thanh toán bằng Visa/Mastercard
                </label>
            </div>
        </div>
    </div>

    <!-- Phần 3: Giỏ hàng và nút mua hàng -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Thông tin giỏ hàng</h2>
        <?php if (!empty($cart_items)): ?>
            <ul class="space-y-4">
                <?php foreach ($cart_items as $item): ?>
                    <li class="flex items-center">
                        <?php
                        // Debug: Kiểm tra giá trị image
                        $imagePath = !empty($item['image']) ? '/assets/images/' . htmlspecialchars($item['image']) : '/placeholder.jpg';
                        error_log("Image path for product ID {$item['product_id']}: $imagePath");
                        ?>
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="w-16 h-16 object-cover mr-4">
                        <div>
                            <p class="font-medium"><?php echo htmlspecialchars($item['product_name']); ?></p>
                            <p class="text-gray-600">Giá: <?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ x <?php echo $item['quantity']; ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="mt-4 text-right">
                <p class="text-lg font-bold">Tổng: <?php echo number_format($total, 0, ',', '.'); ?> VNĐ</p>
                <input type="hidden" name="total" value="<?php echo $total; ?>">
            </div>
            <button type="submit" formaction="/checkout/confirm" formmethod="POST" name="place_order" class="mt-4 w-full bg-green-500 text-white p-2 rounded">Mua hàng</button>
        <?php else: ?>
            <p>Giỏ hàng trống.</p>
        <?php endif; ?>
    </div>
</div>