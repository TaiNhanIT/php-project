<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Giỏ Hàng</h1>

    <?php if (!empty($_SESSION['success_message'])): ?>
        <div class="bg-green-500 text-white p-4 mb-4 rounded">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error_message'])): ?>
        <div class="bg-red-500 text-white p-4 mb-4 rounded">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <?php if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])): ?>
        <div class="bg-red-100 text-red-600 p-4 mb-4 rounded">Giỏ hàng của bạn đang trống.</div>
    <?php else: ?>
        <table class="w-full border-collapse">
            <thead>
            <tr class="bg-gray-200">
                <th class="p-3 text-left">Sản phẩm</th>
                <th class="p-3 text-left">Hình ảnh</th>
                <th class="p-3 text-left">Giá</th>
                <th class="p-3 text-left">Số lượng</th>
                <th class="p-3 text-left">Tổng</th>
                <th class="p-3 text-left">Hành động</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $item):
                $name = $item['product_name'] ?? 'Tên không xác định';
                $productId = $item['product_id'] ?? 0;
                $price = $item['price'] ?? 0;
                $quantity = $item['quantity'] ?? 1;
                $image = $item['image'] ?? 'default.jpg';

                $item_total = $price * $quantity;
                $total += $item_total;
                ?>
                <tr class="border-b">
                    <td class="p-3"><?= htmlspecialchars($name) ?></td>
                    <td class="p-3">
                        <img src="/assets/images/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($name) ?>" class="w-16 h-16 object-cover">
                    </td>
                    <td class="p-3"><?= number_format($price, 0, ',', '.') ?> VNĐ</td>
                    <td class="p-3">
                        <form method="post" action="/cart/index" class="inline">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($productId) ?>">
                            <input type="number" name="quantity" value="<?= htmlspecialchars($quantity) ?>" min="1" class="w-16 p-1 border rounded" required>
                            <button type="submit" name="update" class="ml-2 bg-blue-500 text-white p-1 rounded">Cập nhật</button>
                        </form>
                    </td>
                    <td class="p-3"><?= number_format($item_total, 0, ',', '.') ?> VNĐ</td>
                    <td class="p-3">
                        <form method="post" action="/cart/index" class="inline">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($productId) ?>">
                            <button type="submit" name="remove" class="bg-red-500 text-white p-1 rounded">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-6 text-right">
            <p class="text-xl font-bold">Tổng cộng: <?= number_format($total, 0, ',', '.') ?> VNĐ</p>
            <a href="/checkout" class="bg-green-500 text-white p-2 rounded mt-4 inline-block">Thanh toán</a>
        </div>
    <?php endif; ?>
</div>
