<div class="container mx-auto p-6 mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- Phần 1: Địa chỉ giao hàng -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Địa chỉ giao hàng</h2>

        <?php if (isset($customer_id)): ?>
            <!-- FORM CHO NGƯỜI DÙNG ĐÃ ĐĂNG NHẬP -->
            <?php if (isset($message)): ?>
                <div class="mb-4 p-2 text-green-700 rounded"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form method="POST" action="/checkout/saveAddress/"
                  x-data="{ showForm: <?php echo empty($addresses) ? 'true' : 'false'; ?> }"
                  x-init="if (!showForm) { selectedAddress = '<?php echo htmlspecialchars($selected_address ?? ''); ?>' }">
                <div x-show="showForm">
                    <button type="button" x-on:click="showForm = false" class="mt-2 bg-gray-500 text-white p-2 rounded">Quay lại</button>
                    <!-- Các trường nhập địa chỉ -->
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

                <!-- Chọn địa chỉ đã lưu -->
                <div x-show="!showForm" x-cloak>
                    <?php if ($selected_address): ?>
                        <div class="mb-4 p-2 bg-gray-100 rounded">
                            <p><strong>Địa chỉ đã chọn:</strong></p>
                            <?php $addr = json_decode($selected_address, true); ?>
                            <input type="hidden" name="selected_address" value="<?php echo htmlspecialchars($selected_address); ?>">
                            <p><?php echo htmlspecialchars($addr['name'] ?? '') . ' - ' . htmlspecialchars($addr['phone'] ?? '') . ' - ' . htmlspecialchars($addr['street'] ?? '') . ', ' . htmlspecialchars($addr['city'] ?? '') . ', ' . htmlspecialchars($addr['country_code'] ?? '') . ' - ' . htmlspecialchars($addr['detail'] ?? ''); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($addresses)): ?>
                        <h3 class="text-md font-semibold mb-2">Địa chỉ đã lưu:</h3>
                        <ul class="space-y-2">
                            <?php foreach ($addresses as $addr): ?>
                                <li class="p-2 border rounded flex justify-between items-center">
                                <span class="flex-1">
                                    <?php echo htmlspecialchars($addr['name']) . ' - ' . htmlspecialchars($addr['phone']) . ' - ' . htmlspecialchars($addr['street']) . ', ' . htmlspecialchars($addr['city']) . ', ' . htmlspecialchars($addr['country_code']) . ' - ' . htmlspecialchars($addr['detail']); ?>
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
                        <button type="button" x-on:click="showForm = true" class="mt-2 bg-green-500 text-white p-2 rounded">Thêm địa chỉ mới</button>
                    <?php else: ?>
                        <p>Chưa có địa chỉ nào được lưu.</p>
                        <button type="button" x-on:click="showForm = true" class="mt-2 bg-green-500 text-white p-2 rounded">Thêm địa chỉ mới</button>
                    <?php endif; ?>
                </div>
            </form>

        <?php else: ?>
            <!-- KHÁCH CHƯA ĐĂNG NHẬP -->
            <div>
                <h3 class="text-md font-semibold mb-2">Thông tin giao hàng</h3>
                <!-- Những trường này sẽ nằm trong form="order-form" -->
                <div class="mb-4">
                    <label class="block text-sm font-medium">Họ tên</label>
                    <input type="text" name="customer_name" form="order-form" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="customer_email" form="order-form" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">SĐT</label>
                    <input type="tel" name="customer_phone" form="order-form" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Đường / Phố</label>
                    <input type="text" name="street" form="order-form" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Thành phố</label>
                    <input type="text" name="city" form="order-form" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Mã quốc gia</label>
                    <input type="text" name="country_code" form="order-form" required class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Chi tiết</label>
                    <textarea name="detail" form="order-form" class="w-full p-2 border rounded"></textarea>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- PHẦN 2: GIAO HÀNG & THANH TOÁN -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Giao hàng & Thanh toán</h2>
        <div class="mb-4">
            <label class="block font-medium">Phương thức giao hàng</label>
            <label><input type="radio" name="shipping_method" value="standard" form="order-form" checked> Tiêu chuẩn</label>
            <label><input type="radio" name="shipping_method" value="express" form="order-form"> Nhanh</label>
            <label><input type="radio" name="shipping_method" value="economy" form="order-form"> Tiết kiệm</label>
        </div>
        <div>
            <label class="block font-medium">Thanh toán</label>
            <label><input type="radio" name="payment_method" value="cod" form="order-form" checked> COD</label>
            <label><input type="radio" name="payment_method" value="bank" form="order-form"> Ngân hàng</label>
            <label><input type="radio" name="payment_method" value="visa" form="order-form"> Visa</label>
        </div>
    </div>

    <!-- PHẦN 3: GIỎ HÀNG + MUA -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Thông tin giỏ hàng</h2>
        <?php if (!empty($cart_items)): ?>
            <form method="POST" action="/order/confirm" id="order-form">
                <ul class="space-y-4">
                    <?php foreach ($cart_items as $item): ?>
                        <li class="flex items-center">
                            <img src="/assets/images/<?php echo htmlspecialchars($item['image'] ?? 'placeholder.jpg'); ?>" class="w-16 h-16 mr-4">
                            <div>
                                <p><?php echo htmlspecialchars($item['product_name']); ?></p>
                                <p class="text-sm text-gray-500"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ x <?php echo $item['quantity']; ?></p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="mt-4 text-right">
                    <p class="text-lg font-bold">Tổng: <?php echo number_format($total, 0, ',', '.'); ?>đ</p>
                    <input type="hidden" name="total" value="<?php echo $total; ?>">
                    <?php if (!empty($selected_address)): ?>
                        <input type="hidden" name="selected_address" value="<?php echo htmlspecialchars($selected_address); ?>">
                    <?php endif; ?>
                </div>
                <button type="submit" class="mt-4 w-full bg-green-500 text-white p-2 rounded">Mua hàng</button>
            </form>
        <?php else: ?>
            <p>Giỏ hàng trống.</p>
        <?php endif; ?>
    </div>
</div>
    <!-- Phần 3: Giỏ hàng và nút mua hàng -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-lg font-bold mb-4">Thông tin giỏ hàng</h2>
        <?php if (!empty($cart_items)): ?>
            <form method="POST" action="/order/confirm" id="order-form">
            <ul class="space-y-4">
                    <?php foreach ($cart_items as $item): ?>
                        <li class="flex items-center">
                            <?php
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
                    <input type="hidden" name="selected_address" value="<?php echo htmlspecialchars($selected_address ?? ''); ?>">
                    <input type="hidden" name="shipping_method" value="<?php echo htmlspecialchars($_POST['shipping_method'] ?? 'standard'); ?>">
                    <input type="hidden" name="payment_method" value="<?php echo htmlspecialchars($_POST['payment_method'] ?? 'cod'); ?>">
                    <?php if (!$customer_id): ?>
                        <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($_POST['customer_name'] ?? ''); ?>">
                        <input type="hidden" name="customer_email" value="<?php echo htmlspecialchars($_POST['customer_email'] ?? ''); ?>">
                        <input type="hidden" name="customer_phone" value="<?php echo htmlspecialchars($_POST['customer_phone'] ?? ''); ?>">
                    <?php endif; ?>
                </div>
                <button type="submit" name="place_order" class="mt-4 w-full bg-green-500 text-white p-2 rounded">Mua hàng</button>
            </form>
        <?php else: ?>
            <p>Giỏ hàng trống.</p>
        <?php endif; ?>
    </div>