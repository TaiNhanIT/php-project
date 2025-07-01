<div class="container mx-auto p-6 mt-10">
    <h2 class="text-2xl font-bold mb-6">Lịch sử đơn hàng</h2>

    <?php if (!empty($_SESSION['success_message'])): ?>
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            <?= $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($orders)): ?>
        <div class="space-y-4">
            <?php foreach ($orders as $order): ?>
                <div x-data="{ open: false }" class="bg-white p-4 rounded shadow">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-lg font-semibold">Mã đơn: <?= htmlspecialchars($order['order_code']) ?></p>
                            <p class="text-sm text-gray-600">Ngày: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                            <p class="text-sm text-gray-600">
                                Trạng thái:
                                <?php
                                $labels = [
                                    1 => '🕒 Chờ xử lý',
                                    2 => '🚚 Đang giao',
                                    3 => '✅ Hoàn tất',
                                    4 => '❌ Đã huỷ'
                                ];
                                echo $labels[$order['status_id']] ?? 'Không rõ';
                                ?>
                            </p>
                        </div>
                        <button x-on:click="open = !open" class="text-blue-600 hover:underline">Xem chi tiết</button>
                    </div>

                    <div x-show="open" x-cloak class="mt-4 border-t pt-4">
                        <p><strong>Tên khách:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                        <p><strong>SĐT:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
                        <p class="font-semibold mt-2">Tổng tiền: <?= number_format($order['total_amount'], 0, ',', '.') ?> VNĐ</p>
                        <a href="/order/detail/<?= $order['id'] ?>" class="inline-block mt-3 text-sm text-blue-500 hover:underline">Chi tiết sản phẩm &rarr;</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Không có đơn hàng nào.</p>
    <?php endif; ?>
</div>
