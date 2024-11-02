<?php
include("header.php");
include('dbcon.php');

$users = 1; 

?>

<div class="content-body">

    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3 class="title">User Order History <span>/ Blank</span></h3>
            </div>
        </div>

        <?php
        // Fetch orders for the specific user
        $orderQuery = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY order_at ASC");
        $orderQuery->bindParam(':user_id', $users, PDO::PARAM_INT); // Bind the user ID to the query
        $orderQuery->execute(); // Execute the query
        $orders = $orderQuery->fetchAll(PDO::FETCH_ASSOC); // Fetch all orders for the user
        ?>

        <?php
        // Query to get total number of orders and total spending
        $orderSummaryQuery = $pdo->prepare("
            SELECT COUNT(*) AS total_orders, SUM(order_amount) AS total_spendings
            FROM orders
            WHERE user_id = :user_id
        ");

        $orderSummaryQuery->bindParam(':user_id', $users, PDO::PARAM_INT); // Bind the user ID to the query
        $orderSummaryQuery->execute(); // Execute the query
        $orderSummary = $orderSummaryQuery->fetch(PDO::FETCH_ASSOC); // Fetch the summary
        
        // Now you can access total_orders and total_spendings from $orderSummary
        $totalOrders = $orderSummary['total_orders'];
        $totalSpendings = $orderSummary['total_spendings'] ?? 0; // Default to 0 if NULL
        ?>

        <!-- User Stats Section -->
        <div class="row stats mt-4">
            <div class="col-md-6">
                <div class="stat-box">
                    <h4>Total Orders</h4>
                    <p><?php echo htmlspecialchars($totalOrders); ?></p> <!-- Display the Total Orders -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-box">
                    <h4>Total Spendings</h4>
                    <p>$<?php echo htmlspecialchars(number_format($totalSpendings, 2)); ?></p>
                    <!-- Display Total Spendings -->
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="orders-list mt-4">
            <h4>Your Orders</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="4" class="text-center">No orders found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                      
                                <td>$<?php echo htmlspecialchars(number_format($order['order_amount'], 2)); ?></td>
                                <td><?php echo htmlspecialchars($order['status']); ?></td>
                                <td><?php echo htmlspecialchars($order['order_at']); ?></td>
                                <td>
                                        <a href="user_order_details.php?id=<?php echo $order['id']; ?>">
                                            <button class="button button-xs button-primary">
                                                <i class="zmdi zmdi-edit"></i>Edit
                                            </button>
                                        </a>
                                        <a href="?DLTPRO=<?php echo $products['id']; ?>">
                                            <button class="button button-xs button-danger">
                                                <i class="zmdi zmdi-delete"></i>Delete
                                            </button>
                                        </a>
                                    </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include("footer.php"); // Include the footer file
?>
