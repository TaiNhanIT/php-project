

<div style="display: flex;width: 100%;height: 100%;" class="cntainer">
    <div class="sidebar" style="height: 100vh;">
            <h5 class="text-center">Admin</h5>
            <a href="#" class="nav-link" data-section="dashboard">Dashboard</a>
            <a href="#" class="nav-link" data-section="orders">Orders</a>
            <a href="#" class="nav-link" data-section="products">Products</a>
            <a href="#" class="nav-link" data-section="customers">Customers</a>
        </div>
        <div class="content">
            <!-- Dashboard -->
            <div id="dashboard" class="section" style="">
                <h2>Dashboard</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h5>Total Customers</h5>
                                <h2 id="totalCustomers">2</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h5>Total Orders</h5>
                                <h2 id="totalOrders">2</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders -->
            <div id="orders" class="section" style="display: none;">
                <h2>Orders</h2>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody id="ordersTable"><tr><td>1</td><td>John Doe</td><td>$100</td><td>Pending</td></tr><tr><td>2</td><td>Jane Smith</td><td>$200</td><td>Shipped</td></tr></tbody>
                </table>
            </div>

            <!-- Products -->
            <div id="products" class="section" style="display: none;">
                <h2>Products</h2>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Stock</th>
                    </tr>
                    </thead>
                    <tbody id="productsTable"><tr><td>1</td><td>Product A</td><td>$50</td><td>10</td></tr><tr><td>2</td><td>Product B</td><td>$75</td><td>5</td></tr></tbody>
                </table>
            </div>

            <!-- Customers -->
            <div id="customers" class="section" style="display: none;">
                <h2>Customers</h2>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Name</th>
                        <th>Email</th>
                    </tr>
                    </thead>
                    <tbody id="customersTable"><tr><td>1</td><td>John Doe</td><td>john@example.com</td></tr><tr><td>2</td><td>Jane Smith</td><td>jane@example.com</td></tr></tbody>
                </table>
            </div>
        </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    const orders = [
        { id: 1, customer: "John Doe", total: "$100", status: "Pending" },
        { id: 2, customer: "Jane Smith", total: "$200", status: "Shipped" }
    ];

    const products = [
        { id: 1, name: "Product A", price: "$50", stock: 10 },
        { id: 2, name: "Product B", price: "$75", stock: 5 }
    ];

    const customers = [
        { id: 1, name: "John Doe", email: "john@example.com" },
        { id: 2, name: "Jane Smith", email: "jane@example.com" }
    ];

    function populateDashboard() {
        $("#totalCustomers").text(customers.length);
        $("#totalOrders").text(orders.length);
    }

    function populateTables() {
        // Orders
        $("#ordersTable").html("");
        orders.forEach(o => {
            $("#ordersTable").append(`<tr><td>${o.id}</td><td>${o.customer}</td><td>${o.total}</td><td>${o.status}</td></tr>`);
        });

        // Products
        $("#productsTable").html("");
        products.forEach(p => {
            $("#productsTable").append(`<tr><td>${p.id}</td><td>${p.name}</td><td>${p.price}</td><td>${p.stock}</td></tr>`);
        });

        // Customers
        $("#customersTable").html("");
        customers.forEach(c => {
            $("#customersTable").append(`<tr><td>${c.id}</td><td>${c.name}</td><td>${c.email}</td></tr>`);
        });
    }

    $(document).ready(function () {
        populateDashboard();
        populateTables();

        $(".nav-link").click(function () {
            $(".section").hide();
            const section = $(this).data("section");
            $("#" + section).show();
        });
    });
</script>
</body>
</html>
