<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-6 mt-10">
    <div class="bg-white p-6 rounded shadow-lg">
        <h2 class="text-2xl font-bold mb-4">Chi tiết đơn hàng #<?php echo htmlspecialchars($order['id'] ?? ''); ?></h2>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <?php echo htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <?php echo htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="mb-6 space-y-2">
            <p><strong>Khách hàng:</strong> <?php echo htmlspecialchars($order['customer_name'] ?? ''); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['customer_phone'] ?? ''); ?></p>
            <?php
            $address = json_decode($order['address'] ?? '{}', true);
            $address_str = trim(($address['street'] ?? '') . ', ' . ($address['city'] ?? '') . ', ' . ($address['country_code'] ?? '') .
                (isset($address['detail']) && $address['detail'] ? ', ' . $address['detail'] : ''));
            ?>
            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($address_str ?: 'Chưa có địa chỉ'); ?></p>
            <p><strong>Phương thức giao hàng:</strong> <?php echo htmlspecialchars($order['shipping_method'] ?? ''); ?></p>
            <p><strong>Thanh toán:</strong> <?php echo htmlspecialchars($order['payment_method'] ?? ''); ?></p>
            <p><strong>Trạng thái:</strong>
                <?php
                $statusLabels = [
                    1 => 'Đang chờ xử lý',
                    2 => 'Đang giao hàng',
                    3 => 'Hoàn thành',
                    4 => 'Đã hủy'
                ];
                echo htmlspecialchars($statusLabels[$order['status'] ?? 0] ?? 'Không xác định');
                ?>
            </p>
        </div>

        <h3 class="text-xl font-semibold mb-2">Sản phẩm:</h3>
        <div class="table-responsive">
            <table class="w-full table-auto border">
                <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2 text-left">Sản phẩm</th>
                    <th class="border px-4 py-2 text-right">Giá</th>
                    <th class="border px-4 py-2 text-center">Số lượng</th>
                    <th class="border px-4 py-2 text-right">Tổng</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($item['product_name'] ?? ''); ?></td>
                        <td class="border px-4 py-2 text-right"><?php echo number_format($item['price'] ?? 0, 0, ',', '.'); ?> đ</td>
                        <td class="border px-4 py-2 text-center"><?php echo $item['quantity'] ?? 0; ?></td>
                        <td class="border px-4 py-2 text-right">
                            <?php echo number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.'); ?> đ
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 text-right text-xl font-bold">
            Tổng tiền: <?php echo number_format($order['total'] ?? 0, 0, ',', '.'); ?> đ
        </div>
        <a href="/order" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Quay lại danh sách</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>