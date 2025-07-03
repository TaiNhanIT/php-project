<body>
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-4"><?php echo htmlspecialchars($product['product_name']); ?></h1>
    <div class="flex gap-6">
        <img src="/assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="w-64 h-64 object-cover">
        <div>
            <p class="text-lg">Giá: <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</p>
            <p class="text-gray-600">Mô tả: <?php echo htmlspecialchars($product['description']); ?></p>
            <p class="text-gray-600">Số lượng tồn: <?php echo $product['stock_quantity']; ?></p>
            <?php
            if (isset($_SESSION['success_message'])) {
                echo '<div class="bg-green-500 text-white p-2 mb-2 rounded">' . htmlspecialchars($_SESSION['success_message']) . '</div>';
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                echo '<div class="bg-red-500 text-white p-2 mb-2 rounded">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                unset($_SESSION['error_message']);
            }
            ?>
            <form method="post" action="/cart/add/<?php echo $product['id']; ?>">
                <label for="quantity" class="block mb-1">Số lượng:</label>
                <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $product['stock_quantity']; ?>" value="1" class="w-20 p-1 border rounded mb-2" required>
                <button type="submit" name="add_to_cart" class="bg-blue-500 text-white p-2 rounded mt-2">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>
</div>