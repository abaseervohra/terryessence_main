<?php
include("header.php");
include('dbcon.php');

$users = 1; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = $pdo->prepare("SELECT * FROM orders WHERE id = :id");
    $query->bindParam(':id', $id, PDO::PARAM_INT); // Bind the user ID to the query
    $query->execute(); // Execute the query
    $users = $query->fetch(PDO::FETCH_ASSOC); // Fetch the user data as an associative array

    if (!$user) {
        echo "order not found.";
        exit;
    }
} else {
    echo "No order ID specified.";
    exit;
}
?>
?>
<div class="content-body">
<?php
        $orderId = $id; // The order ID to check
        
        // Step 1: Query to fetch total number of unique products and total amount from order_detail
        $query = "
        SELECT 
        COUNT(DISTINCT oi.product_id) AS total_products,
        SUM(oi.product_qty * oi.product_price) AS total_amount
        FROM order_detail oi
        WHERE oi.order_id = :order_id
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $order_summary = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set the total products and total amount
        $total_products = $order_summary['total_products'] ?? 0; // Default to 0 if NULL
        $total_amount = $order_summary['total_amount'] ?? 0; // Default to 0 if NULL
        
        // Step 2: Fetch the order status
        $order_info_query = "
        SELECT status 
        FROM orders 
        WHERE order_id = :order_id
        ";
        $order_info_stmt = $pdo->prepare($order_info_query);
        $order_info_stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
        $order_info_stmt->execute();
        $order_info = $order_info_stmt->fetch(PDO::FETCH_ASSOC);
        ?>
<div class="row justify-content-between align-items-center mb-10">
    <div class="col-12 col-lg-auto mb-20">
        <div class="page-heading">
            <h3 class="title">User Order History <span>/ Blank</span></h3>
        </div>
    </div>
    <div>
    <div class="col-12 mb-30">
            <div class="order-details-customer-info row mbn-20">

                <!--Billing Info Start-->
                <div class="col-lg-4 col-md-6 col-12 mb-20">
                    <h4 class="mb-25">Billing Info</h4>
                    <ul>
                        <li> <span>Name</span> <span>Jonathin doe</span> </li>
                        <li> <span>Country</span> <span>USA</span> </li>
                        <li> <span>Address</span> <span>13/2 Minar St, Sanfrancisco <br>CA 8788 USA.</span> </li>
                        <li> <span>State</span> <span>United Stade</span> </li>
                        <li> <span>City</span> <span>Sanfrancisco</span> </li>
                        <li> <span>Email</span> <span>domain@mail.com</span> </li>
                        <li> <span>Phone</span> <span>+1 022 3665 88</span> </li>
                    </ul>
                </div>
                <!--Billing Info End-->

                <!--Shipping Info Start-->
                <div class="col-lg-4 col-md-6 col-12 mb-20">
                    <h4 class="mb-25">Shipping Info</h4>
                    <ul>
                        <li> <span>Name</span> <span>Jonathin doe</span> </li>
                        <li> <span>Country</span> <span>USA</span> </li>
                        <li> <span>Address</span> <span>13/2 Minar St, Sanfrancisco <br>CA 8788 USA.</span> </li>
                        <li> <span>State</span> <span>United Stade</span> </li>
                        <li> <span>City</span> <span>Sanfrancisco</span> </li>
                        <li> <span>Email</span> <span>domain@mail.com</span> </li>
                        <li> <span>Phone</span> <span>+1 022 3665 88</span> </li>
                    </ul>
                </div>
                <!--Shipping Info End-->
                <!-- Purchase Info Start -->
                <div class="col-lg-4 col-md-6 col-12 mb-20">
                    <h4 class="mb-25">Purchase Info</h4>
                    <ul>
                        <div class="product-info">
                            <span>Total Products:</span>
                            <span><?php echo htmlspecialchars($total_products); ?></span>
                        </div>
                        <div class="product-info">
                            <span>Total Amount:</span>
                            <span><?php echo htmlspecialchars($total_amount) ?></span>
                        </div>

                        <div class="product-info">
                            <span class="h5 fw-600">Payment Status:</span>
                            <span><?php echo htmlspecialchars($order_info['status']) ?></span>
                        </div>
                    </ul>
                </div>
                <!-- Purchase Info End -->
                 
        <!--Order Details List Start-->
        <div class="col-12 mb-30">
            <div class="table-responsive">
                <table class="table table-bordered table-vertical-middle">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Assuming you have the order ID in $id
                        $itemQuery = $pdo->prepare("
                       SELECT  
                         od.id,
                         od.product_name,
                         od.product_price,
                         od.product_qty,
                        o.order_at
      
                        FROM order_detail od
                        INNER JOIN orders o ON od.order_id = o.order_id
                        WHERE od.order_id = :id

   
                       ");
                        $itemQuery->bindParam(':id', $id, PDO::PARAM_INT);
                        $itemQuery->execute();
                        $order_details = $itemQuery->fetchAll(PDO::FETCH_ASSOC); // Fetch all order details as an associative array
                        ?>

                        <?php foreach ($order_details as $order_detail) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order_detail['id']) ?></td>
                                <td><?php echo htmlspecialchars($order_detail['product_name']) ?> </td>
                                <td><?php echo htmlspecialchars($order_detail['product_price']) ?> </td>
                                <td><?php echo htmlspecialchars($order_detail['product_qty']) ?> </td>
                                <td><?php echo htmlspecialchars($order_detail['order_at']) ?></td>
                            </tr>
                        <?php } ?>


                    </tbody>
                </table>
            </div>
        </div>
        <!--Order Details List End-->


            </div>
        </div>
    </div>
</div>





<?php 
include('footer.php');
?>