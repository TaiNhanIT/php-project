<div class="max-w-6xl mx-auto px-4 py-8 flex gap-6">
    <aside class="w-64 shrink-0">
        <?php include __DIR__ . '/../partials/customerSidebar.php'; ?>
    </aside>

    <main class="flex-1">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">🧾 Danh sách đơn hàng</h1>

        <?php if (!empty($orders)): ?>
            <div class="space-y-4">
                <?php foreach ($orders as $order): ?>
                    <div x-data="{ open: false }" class="bg-white rounded shadow-md p-4 transition-all hover:shadow-lg">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold text-gray-800">Mã đơn: #<?= htmlspecialchars($order['id']) ?></p>
                                <p class="text-sm text-gray-500">Ngày: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                                <p class="text-sm text-gray-600">
                                    Trạng thái:
                                    <?php
                                    $labels = [
                                        1 => '🕒 Chờ xử lý',
                                        2 => '🚚 Đang giao',
                                        3 => '✅ Hoàn tất',
                                        4 => '❌ Đã huỷ'
                                    ];
                                    echo $labels[$order['status'] ?? 0] ?? 'Không rõ';
                                    ?>
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-blue-700"><?= number_format($order['total'], 0, ',', '.') ?> VNĐ</p>
                                <a href="/order/detail/<?= $order['id'] ?>" class="text-sm text-blue-500 hover:underline">Chi tiết sản phẩm →</a>
                                <button x-on:click="open = !open" class="ml-2 text-sm text-gray-600 hover:text-blue-600">
                                    <span x-text="open ? 'Ẩn chi tiết' : 'Xem thêm'"></span>
                                </button>
                            </div>
                        </div>

                        <div x-show="open" x-cloak class="mt-4 border-t pt-4 text-sm text-gray-700 space-y-2">
                            <p><strong>Tên khách:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                            <p><strong>SĐT:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                            <?php $addr = json_decode($order['address'], true); ?>
                            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars(($addr['street'] ?? '') . ', ' . ($addr['city'] ?? '') . ', ' . ($addr['country_code'] ?? '')) ?></p>

                            <?php if ($order['status'] != 4): ?>
                                <form method="POST" action="/order/cancel/<?= $order['id'] ?>" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng?');" class="mt-2">
                                    <button type="submit" class="text-red-500 hover:underline">❌ Hủy đơn hàng</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-gray-500">Không có đơn hàng nào.</p>
        <?php endif; ?>
    </main>
</div>
