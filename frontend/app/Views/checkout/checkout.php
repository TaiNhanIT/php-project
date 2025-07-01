<div class="container mx-auto p-6 mt-10 grid grid-cols-1 md:grid-cols-3 gap-6">
    <!-- PHẦN 1: ĐỊA CHỈ GIAO HÀNG -->
    <div class="bg-white p-4 rounded shadow col-span-1 md:col-span-2">
        <h2 class="text-lg font-bold mb-4">Địa chỉ giao hàng</h2>

        <?php if (isset($message)): ?>
            <div class="mb-4 p-2 text-green-700 rounded"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- FORM LƯU ĐỊA CHỈ MỚI -->
        <form method="POST" action="/checkout/saveAddress">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block">Tên người nhận</label>
                    <input type="text" name="name" required class="w-full border p-2 rounded">
                </div>
                <div>
                    <label class="block">Số điện thoại</label>
                    <input type="tel" name="phone" required class="w-full border p-2 rounded">
                </div>
                <div class="col-span-2">
                    <label class="block">Chi tiết địa chỉ</label>
                    <textarea name="detail" class="w-full border p-2 rounded" required></textarea>
                </div>
            </div>
            <button type="submit" name="save_address" class="mt-4 bg-blue-500 text-white p-2 rounded">Lưu địa chỉ</button>
        </form>

        <!-- DANH SÁCH ĐỊA CHỈ ĐÃ LƯU -->
        <h3 class="mt-6 font-semibold">Địa chỉ đã lưu:</h3>
        <?php if (!empty($addresses)): ?>
            <ul class="space-y-2 mt-2">
                <?php foreach ($addresses as $addr): ?>
                    <li class="p-2 border rounded flex justify-between items-center">
                        <span><?php echo htmlspecialchars("{$addr['name']} - {$addr['phone']} - {$addr['detail']}"); ?></span>
                        <div class="flex space-x-2">
                            <form method="POST" action="/checkout/saveAddress/">
                                <input type="hidden" name="select_address_id" value="<?php echo $addr['id']; ?>">
                                <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Chọn</button>
                            </form>
                            <form method="POST" action="/checkout/deleteAddress/">
                                <input type="hidden" name="delete_address_id" value="<?php echo $addr['id']; ?>">
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded text-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">Xóa</button>
                            </form>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">Chưa có địa chỉ nào.</p>
        <?php endif; ?>
    </div>

    <!-- PHẦN 2: ĐẶT HÀNG -->
    <div class="bg-white p-4 rounded shadow col-span-1">
        <h2 class="text-lg font-bold mb-4">Đặt hàng</h2>

        <?php if ($selected_address && !empty($_SESSION['cart'])): ?>
            <form method="POST" action="/order/confirm">
                <!-- Địa chỉ đã chọn -->
                <?php $addr = json_decode($selected_address, true); ?>
                <input type="hidden" name="selected_address" value='<?php echo htmlspecialchars(json_encode($addr)); ?>'>
                <div class="mb-4 p-2 bg-gray-100 rounded text-sm">
                    <strong>Địa chỉ đã chọn:</strong>
                    <p><?php echo htmlspecialchars("{$addr['name']} - {$addr['phone']} - {$addr['detail']}"); ?></p>
                </div>

                <!-- Giỏ hàng -->
                <div class="mt-4">
                    <h3 class="font-bold mb-2">Giỏ hàng</h3>
                    <ul class="space-y-3 text-sm">
                        <?php $total = 0;
                        foreach ($_SESSION['cart'] as $item):
                            $item_total = $item['price'] * $item['quantity'];
                            $total += $item_total;
                            ?>
                            <li class="flex justify-between">
                                <span><?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?></span>
                                <span><?php echo number_format($item_total, 0, ',', '.'); ?>₫</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="mt-2 text-right font-bold">
                        Tổng cộng: <?php echo number_format($total, 0, ',', '.'); ?>₫
                    </div>
                    <input type="hidden" name="total_price" value="<?php echo $total; ?>">
                </div>

                <!-- Nút mua hàng -->
                <button type="submit" name="place_order" class="mt-4 bg-green-500 text-white p-2 rounded w-full">Mua hàng</button>
            </form>
        <?php else: ?>
            <p class="text-red-600">Vui lòng chọn địa chỉ và thêm sản phẩm vào giỏ hàng trước khi đặt hàng.</p>
        <?php endif; ?>
    </div>
</div>
