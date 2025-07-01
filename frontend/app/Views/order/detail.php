<div class="container mx-auto p-6">
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Chi tiết đơn hàng #<?php echo $order['id']; ?></h2>

        <div class="mb-6">
            <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['customer_name']); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['customer_phone']); ?></p>
            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['customer_address']); ?></p>
            <p><strong>Phương thức giao hàng:</strong> <?php echo htmlspecialchars($order['shipping_method']); ?></p>
            <p><strong>Thanh toán:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
            <p><strong>Trạng thái:</strong>
                <?php
                $statusLabels = [
                    1 => 'Đang chờ xử lý',
                    2 => 'Đang giao hàng',
                    3 => 'Hoàn thành',
                    4 => 'Đã hủy'
                ];
                echo $statusLabels[$order['status_id']] ?? 'Không xác định';
                ?>
            </p>
        </div>

        <h3 class="text-xl font-semibold mb-2">Sản phẩm:</h3>
        <table class="w-full table-auto border">
            <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2 text-left">Sản phẩm</th>
                <th class="border px-4 py-2">Giá</th>
                <th class="border px-4 py-2">Số lượng</th>
                <th class="border px-4 py-2">Tổng</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo htmlspecialchars($item['product_name']); ?></td>
                    <td class="border px-4 py-2 text-right"><?php echo number_format($item['price'], 0, ',', '.'); ?> đ</td>
                    <td class="border px-4 py-2 text-center"><?php echo $item['quantity']; ?></td>
                    <td class="border px-4 py-2 text-right">
                        <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> đ
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-6 text-right text-xl font-bold">
            Tổng tiền: <?php echo number_format($order['total_price'], 0, ',', '.'); ?> đ
        </div>
    </div>
</div>
