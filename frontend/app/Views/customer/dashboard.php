<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">👤 Hồ sơ khách hàng</h1>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-semibold text-blue-600 mb-4"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600"><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
                <p class="text-gray-600"><strong>Số điện thoại:</strong> <?= htmlspecialchars($customer['phone_number']) ?></p>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-700 mb-2">📍 Địa chỉ đã lưu:</h3>
                <ul class="list-disc pl-6 text-gray-600 space-y-1">
                    <?php
                    $addresses = $customerModel->getCustomerAddresses($customer['id']);
                    if (!empty($addresses)) {
                        foreach ($addresses as $address) {
                            echo "<li>" . htmlspecialchars($address['street'] . ', ' . $address['city'] . ', ' . $address['country_code']) . "</li>";
                        }
                    } else {
                        echo "<li>Chưa có địa chỉ nào</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
