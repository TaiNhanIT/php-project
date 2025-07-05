<body class="bg-gray-100">
<div class="container mx-auto p-6 mt-10">
    <h2 class="text-2xl font-bold mb-6">Lịch sử đơn hàng</h2>

    <?php if (!empty($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($orders)): ?>
        <div class="space-y-4">
            <?php foreach ($orders as $order): ?>
                <div x-data="{ open: false }" class="bg-white p-4 rounded shadow transition-all duration-300 hover:shadow-lg">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-lg font-semibold">Mã đơn: #<?= htmlspecialchars($order['id']) ?></p>
                            <p class="text-sm text-gray-600">Ngày: <?= date('d/m/Y H:i', strtotime($order['created_at'] ?? 'now')) ?></p>
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
                        <button
                                x-on:click="open = !open"
                                x-text="open ? 'Ẩn chi tiết' : 'Xem chi tiết'"
                                class="text-blue-600 hover:underline focus:outline-none">
                        </button>
                    </div>

                    <div x-show="open" x-cloak class="mt-4 border-t pt-4 space-y-2">
                        <p><strong>Tên khách:</strong> <?= htmlspecialchars($order['customer_name'] ?? '') ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email'] ?? '') ?></p>
                        <p><strong>SĐT:</strong> <?= htmlspecialchars($order['customer_phone'] ?? '') ?></p>
                        <?php $address = json_decode($order['address'] ?? '{}', true); ?>
                        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars(($address['street'] ?? '') . ', ' . ($address['city'] ?? '') . ', ' . ($address['country_code'] ?? '')) ?></p>
                        <p class="font-semibold">Tổng tiền: <?= number_format($order['total'] ?? 0, 0, ',', '.') ?> VNĐ</p>
                        <a href="/order/detail/<?= $order['id'] ?>" class="inline-block mt-2 text-sm text-blue-500 hover:underline">Chi tiết sản phẩm →</a>
                        <?php if ($order['status'] != 4): ?>
                            <form method="POST" action="/order/cancel/<?= $order['id'] ?>" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng?');" class="mt-2">
                                <button type="submit" class="btn btn-danger btn-sm">Hủy đơn hàng</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-500">Không có đơn hàng nào.</p>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>