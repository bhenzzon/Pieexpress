<?php
session_start();
$connection = new mysqli("localhost", "root", "", "ordering_system");

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Handle order status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['order_status'])) {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    if ($order_status === "Completed") {
        $check_payment_query = "SELECT payment_status FROM orders WHERE id = ?";
        $stmt = $connection->prepare($check_payment_query);
        $stmt->bind_param("i", $order_id);  
        $stmt->execute();
        $stmt->bind_result($payment_status);
        $stmt->fetch();
        $stmt->close();

        if ($payment_status !== "Paid") {
            echo "error: unpaid"; // Return error message if order is unpaid
            exit;
        }

        // Move to order_history
        $move_query = "INSERT INTO order_history (id, customer_name, contact, address, order_status, payment_status, total_price)
                       SELECT id, customer_name, contact, address, order_status, payment_status, total_price FROM orders WHERE id = ?";
        $stmt = $connection->prepare($move_query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();

        // Move order items to order_history_items
        $move_items_query = "INSERT INTO order_history_items (order_id, product_name, price, quantity, subtotal)
                             SELECT order_id, product_name, price, quantity, (price * quantity) FROM order_items WHERE order_id = ?";
        $stmt = $connection->prepare($move_items_query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();

        // Delete from orders table
        $delete_query = "DELETE FROM orders WHERE id = ?";
        $stmt = $connection->prepare($delete_query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();

        // Delete from order_items table
        $delete_items_query = "DELETE FROM order_items WHERE order_id = ?";
        $stmt = $connection->prepare($delete_items_query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Just update status
        $update_query = "UPDATE orders SET order_status = ? WHERE id = ?";
        $stmt = $connection->prepare($update_query);
        $stmt->bind_param("si", $order_status, $order_id);
        $stmt->execute();
        $stmt->close();
    }

    echo "success"; // Success message
    exit;
}

// Handle payment status update separately
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['payment_status'])) {
    $order_id = $_POST['order_id'];
    $payment_status = $_POST['payment_status'];

    $update_payment_query = "UPDATE orders SET payment_status = ? WHERE id = ?";
    $stmt = $connection->prepare($update_payment_query);
    $stmt->bind_param("si", $payment_status, $order_id);
    $stmt->execute();
    $stmt->close();
    exit;
}

// Fetch orders
$order_query = "SELECT o.id AS order_id, o.customer_name, o.contact, o.email, o.address, o.city, o.order_status, 
                       o.payment_status, o.payment_screenshot, o.payment_method, o.total_price, 
                       i.product_name, i.price, i.quantity, (i.price * i.quantity) AS subtotal
                FROM orders o
                JOIN order_items i ON o.id = i.order_id
                ORDER BY o.id DESC";

$order_result = $connection->query($order_query);

$orders = [];
while ($row = $order_result->fetch_assoc()) {
    $orders[$row['order_id']]['customer_name'] = $row['customer_name'];
    $orders[$row['order_id']]['contact'] = $row['contact'];
    $orders[$row['order_id']]['email'] = $row['email'];
    $orders[$row['order_id']]['address'] = $row['address'];
    $orders[$row['order_id']]['city'] = $row['city'];
    $orders[$row['order_id']]['order_status'] = $row['order_status'];
    $orders[$row['order_id']]['payment_status'] = $row['payment_status'];
    $orders[$row['order_id']]['payment_screenshot'] = $row['payment_screenshot'];
    $orders[$row['order_id']]['payment_method'] = $row['payment_method'];
    $orders[$row['order_id']]['total_price'] = $row['total_price'];
    $orders[$row['order_id']]['products'][] = [
        'name' => $row['product_name'],
        'price' => $row['price'],
        'quantity' => $row['quantity'],
        'subtotal' => $row['subtotal']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Orders</title>
    <link rel="icon" type="image/x-icon" href="/project/img/logo.png">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      text-align: center;
      background-color: #f8f8f8;
    }
        table { width: 100%; border-collapse: collapse; margin-top: 3%; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #013220; color: white; }
        .pending { background-color: orange; color: white; padding: 5px; border-radius: 5px; }
        .unpaid { background-color: orange; color: white; padding: 5px; border-radius: 5px; }
        .completed { background-color: green; color: white; padding: 5px; border-radius: 5px; }
        .cancelled { background-color: red; color: white; padding: 5px; border-radius: 5px; }
        .delivered { background-color: green; color: white; padding: 5px; border-radius: 5px; }
        .received { background-color: green; color: white; padding: 5px; border-radius: 5px; }
        .paid    { background-color: green; color: white; padding: 5px; border-radius: 5px; }
        .preparing { background-color: #0f5298; color: white; padding: 5px; border-radius: 5px; }
        .disabled { background-color: #0f5298; color: white; padding: 5px; border-radius: 5px; }
        .to, .ship { background-color: #FF5349; color: white; padding: 5px; border-radius: 5px; }
        .btn { padding: 8px 12px; border: none; border-radius: 5px; cursor: pointer; color: white; margin: 2px; }
        .accept-btn { background-color: green; }
        .pay-btn { background-color: green; }
        .accept-btn:hover, .pay-btn:hover { 
          background-color: darkgreen; 
          transform: scale(1.05); /* Slight zoom effect */
          color: white;
        }
        .reject-btn { background-color: red; }
        .reject-btn:hover { 
            background-color: darkred; 
            transform: scale(1.05); 
            color: white;
        }
        .delivered-btn { background-color: Green;}
        .delivered-btn:hover { 
            background-color: darkgreen; 
            transform: scale(1.05); 
            color: white;
        }
        .prepare-btn { background-color: #0f5298; }
        .prepare-btn:hover { 
            background-color: #063c75; /* Darker blue */
            transform: scale(1.05); 
            color: white;
        }
        .ship-btn { background-color: #FF5349; }
        .ship-btn:hover { 
            background-color: orange; 
            transform: scale(1.05); 
            color: white;
        }
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: rgba(1, 50, 32, 1);
            padding: 0px 1px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s;
            z-index: 1000;
            height: 110px;
        }
        .navbar.scrolled {
            background-color: rgba(1, 50, 32, 0.8);
        }
        .logo {
            display: flex;
            align-items: center;
        }
        .logo img {
            width: 90px;
            height: auto;
            max-height: 100%;
        }
        .logo span {
            color: white;
            font-size: 24px;
            font-weight: 600;
        }
    
    .nav-links {
      display: flex;
      gap: 20px;
      margin-right: 50px;
    }
    .nav-links a {
      color: white;
      text-decoration: none;
      padding: 8px 12px;
      font-weight: 600;
      border-radius: 5px;
    }
    .order-now {
      background-color: #FFC107;
      color: #013220;
      padding: 10px 15px;
      border-radius: 5px; 
    }
    .title {
        margin-top: 9%;
        color: rgba(1, 50, 32, 1);
    }
    .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 100%;
            margin: auto;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 8%;
            text-align: left;
        }
        .modal-container {
            max-width: 150%;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px; /* Space between details and image */
}

.order-details {
    flex: 1; /* Takes up available space */
}

.payment-screenshot {
    flex: 0.5; /* Smaller width */
    text-align: center;
}

.payment-screenshot img {
    width: 150%;
    max-height: 200%;
    object-fit: contain;
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 5px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
}
#modalPaymentStatus {
    width: 150px;
    padding: 8px;
    font-size: 16px;
    border-radius: 8px;
    border: 2px solid #007bff; /* Bootstrap primary blue */
    background-color: #f8f9fa; /* Light gray */
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease;
}

#modalPaymentStatus:hover,
#modalPaymentStatus:focus {
    border-color: #0056b3; /* Darker blue on hover */
    background-color: #e9ecef; /* Slightly darker gray */
}

/* Style for the options inside the dropdown */
#modalPaymentStatus option {
    background: white;
    color: #333;
    font-weight: bold;
}

/* Change colors based on selection */
#modalPaymentStatus[value="Paid"] {
    background-color: #28a745 !important; /* Green */
    color: white;
}

#modalPaymentStatus[value="Unpaid"] {
    background-color: #dc3545 !important; /* Red */
    color: white;
}
/* Style for the modal table */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    background-color: #fff; /* White background */
    border-radius: 8px; /* Rounded corners */
    overflow: hidden; /* Ensures rounded borders apply */
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Light shadow */
}

/* Header styling */
.table thead {
    background-color: #007bff; /* Bootstrap primary blue */
    color: white;
}

.table thead th {
    padding: 12px;
    text-align: left;
    background-color: rgba(1, 50, 32, 1);
    color: white;
}

/* Table row styling */
.table tbody tr {
    border-bottom: 1px solid #ddd; /* Light border */
    transition: background-color 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa; /* Light gray hover effect */
}

/* Table cell styling */
.table tbody td {
    padding: 10px;
    color: #333;
}

/* Alternate row color */
.table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}
/* Style for disabled buttons */
button:disabled {
    background-color: gray !important; /* Gray background */
    color: #fff !important; /* White text */
    cursor: not-allowed !important; /* Show 'not-allowed' cursor */
    opacity: 0.6; /* Reduce opacity to indicate it's disabled */
    border: none; /* Remove border */
}   

#modalPaymentMethod {
    font-weight: bold;
    color: #007bff; /* Blue color */
    text-transform: uppercase;
}

    </style>
</head>
<body>
     <nav class="navbar" id="navbar">
        <div class="logo">
            <a class="navbar-brand" href="/project/project-folder/index.php"><img src="/project/img/logo.png" alt="logo" class="img-responsive"></a> 
        </div>
        <div class="nav-links">
            <a href="/project/project-folder/index.php" class="order-now">Home</a>
            <a href="/project/project-folder/php/load_products.php">Store</a>
            <a href="/project/project-folder/php/add_image.php">Add Item</a>
            <a href="/project/project-folder/php/orders.php">Orders</a>    
            <a href="/project/project-folder/php/order_history.php">History</a>        
        </div>
    </nav>
    <div class="container">
    <h2>Order Management</h2>
    
    <?php if (empty($orders)): ?>
    <div style="text-align: center; margin-top: 20px;">
        <p style="font-size: 18px; font-weight: bold; color: #013220;">No orders for now.</p>
        <a href="order_history.php" style="display: inline-block; padding: 10px 20px; background-color: #FFC107; color: #013220; text-decoration: none; border-radius: 5px; font-weight: bold;">
            Check Order History
        </a>
    </div>
    </div>
<?php else: ?>
    <table>
    <tr>
        <th>Order ID</th>
        <th>Status</th>
        <th>Payment Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($orders as $order_id => $order): ?>
        <?php 
        $total_price = array_sum(array_map(function($product) {
            return $product['price'] * $product['quantity'];
        }, $order['products']));

        // Disable "Completed" button unless the status is "Delivered"
        $disableCompleted = !in_array($order['order_status'], ['Delivered', 'Cancelled']) ? 'disabled' : '';

        // Disable "Paid" button if already paid
        $disablePaid = ($order['payment_status'] === 'Paid') ? 'disabled' : '';
        ?>
        <?php foreach ($order['products'] as $index => $product): ?>
            <tr>
                <?php if ($index === 0): ?>
                    <td rowspan="<?php echo count($order['products']); ?>"> <?php echo $order_id; ?> </td>
                <?php endif; ?>
                <?php if ($index === 0): ?>
                    <td rowspan="<?php echo count($order['products']); ?>">
                        <span class="<?php echo strtolower($order['order_status']); ?>"> <?php echo ucfirst($order['order_status']); ?> </span>
                    </td>
                    <td rowspan="<?php echo count($order['products']); ?>">
                        <span class="<?php echo strtolower($order['payment_status'] ?? 'unpaid'); ?>">
                            <?php echo ucfirst($order['payment_status'] ?? 'Unpaid'); ?>
                        </span>
                    </td>
                    <td rowspan="<?php echo count($order['products']); ?>">
                    <button class="btn prepare-btn" onclick="updateOrderStatus(<?php echo $order_id; ?>, 'Preparing')">Preparing</button>
                    <button class="btn ship-btn" onclick="updateOrderStatus(<?php echo $order_id; ?>, 'To Ship')">To Ship</button>
                    <button class="btn delivered-btn" onclick="updateOrderStatus(<?php echo $order_id; ?>, 'Delivered')">Delivered</button>
                    <button class="btn accept-btn" 
                            onclick="updateOrderStatus(<?php echo $order_id; ?>, 'Completed', '<?php echo $order['payment_status']; ?>')" 
                            <?php echo $disableCompleted; ?>>
                        Completed
                    </button>
                    <button class="btn reject-btn" onclick="openCancelModal(<?php echo $order_id; ?>)">Reject</button>
                    <button class="btn pay-btn" onclick="updatePaymentStatus(<?php echo $order_id; ?>, 'Paid')" <?php echo $disablePaid; ?>>Paid</button>
                    <button class="btn btn-primary" onclick="showOrderDetails(
                        <?php echo $order_id; ?>, 
                        '<?php echo htmlspecialchars($order['customer_name']); ?>', 
                        '<?php echo htmlspecialchars($order['contact']); ?>', 
                        '<?php echo htmlspecialchars($order['email']); ?>', 
                        '<?php echo htmlspecialchars($order['address']); ?>', 
                        '<?php echo htmlspecialchars($order['city']); ?>', 
                        <?php echo htmlspecialchars(json_encode($order['products'])); ?>, 
                        <?php echo $order['total_price']; ?>,   
                        '<?php echo htmlspecialchars($order['payment_status']); ?>',
                        '<?php echo htmlspecialchars($order['payment_screenshot']); ?>',
                        '<?php echo htmlspecialchars($order['payment_method']); ?>',
                        '<?php echo htmlspecialchars($order['total_price']); ?>'
                    )">View Details</button>

                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
</table>
<?php endif; ?>
<div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg"> 
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="modal-container">
                    <!-- Left Side: Order Details -->
                    <div class="order-details">
                        <p><strong>Customer Name:</strong> <span id="modalCustomerName"></span></p>
                        <p><strong>Contact:</strong> <span id="modalContact"></span></p>
                        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                        <p><strong>Address:</strong> <span id="modalAddress"></span></p>
                        <p><strong>City:</strong> <span id="modalCity"></span></p>

                        <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modalOrderItems"></tbody>
                    </table>


                        <p><strong>Total Price:</strong> ₱<span id="modalTotalPrice"></span></p>

                        <p><strong>Payment Method: </strong><span id="modalPaymentMethod"></span></p>

                        <p><strong>Payment Status:</strong> 
                            <select id="modalPaymentStatus" onchange="updatePaymentStatus(modalOrderId, this.value)">
                                <option value="Unpaid">Unpaid</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </p>
                    </div>

                    <!-- Right Side: Payment Screenshot -->
                    <div class="payment-screenshot">
                        <strong>Payment Screenshot:</strong>
                        <img id="modalPaymentScreenshot" src="" alt="Payment Screenshot">
                        <p id="noPaymentText" style="display: none; font-weight: bold; color: #007bff;">Cash on Delivery</p>
                    </div>
                    
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="cancelModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background: white; padding: 20px; width: 400px; margin: 10% auto; border-radius: 10px;">
        <h3>Cancel Order</h3>
        <p>What is the reason for cancellation?</p>
        <textarea id="cancelReason" style="width: 100%; height: 80px; margin-bottom: 10px;"></textarea>
        <input type="hidden" id="cancelOrderId">
        <div style="text-align: right;">
            <button onclick="closeCancelModal()" style="background: gray; color: white; padding: 8px 12px; border: none; border-radius: 5px;">Close</button>
            <button onclick="confirmCancellation()" style="background: red; color: white; padding: 8px 12px; border: none; border-radius: 5px;">Confirm Cancellation</button>
        </div>
    </div>
</div>

<script>
function updateOrderStatus(orderId, status, paymentStatus) { 
    if (status === 'Completed' && paymentStatus !== 'Paid') {
        Swal.fire({
            icon: 'error',
            title: 'Cannot Complete Order',
            text: 'The order must be marked as "Paid" before completing.',
        });
        return;
    }

    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to mark this order as ${status}. This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('orders.php', {
                method: 'POST',
                body: new URLSearchParams({ order_id: orderId, order_status: status }),
            }).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Order Updated',
                    text: `The order has been marked as ${status}.`,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => location.reload());
            });
        }
    });
}

function updatePaymentStatus(orderId, status) {
    Swal.fire({
        title: "Confirm Payment Update",
        text: `Are you sure you want to mark this order as "${status}"?`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Update",
        cancelButtonText: "No, Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('orders.php', {
                method: 'POST',
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams({ order_id: orderId, payment_status: status }),
            })
            .then(response => response.text())
            .then(data => {
                console.log("Payment Update Response:", data);
                Swal.fire("Updated!", "Payment status has been updated.", "success");
                setTimeout(() => location.reload(), 1500); // Auto-refresh after success message
            })
            .catch(error => {
                console.error("Error updating payment:", error);
                Swal.fire("Error!", "Something went wrong.", "error");
            });
        }
    });
}


let modalOrderId = null;  // Store order ID globally

function showOrderDetails(orderId, customerName, contact, email, address, City, products, totalPrice, paymentStatus, paymentScreenshot, paymentMethod) {
    modalOrderId = orderId;  // Store the current order ID
    document.getElementById("modalCustomerName").textContent = customerName;
    document.getElementById("modalContact").textContent = contact;
    document.getElementById("modalEmail").textContent = email;
    document.getElementById("modalAddress").textContent = address;
    document.getElementById("modalCity").textContent = City;
    document.getElementById("modalTotalPrice").textContent = totalPrice;
    document.getElementById("modalPaymentStatus").value = paymentStatus;
    document.getElementById('modalPaymentMethod').innerText = paymentMethod || 'N/A';   // Set payment status

    const paymentScreenshotElement = document.getElementById("modalPaymentScreenshot");

if (paymentScreenshot) {
    paymentScreenshotElement.src = "uploads/gallery/" + paymentScreenshot;
    paymentScreenshotElement.style.display = "block"; // Show the image
    document.getElementById("noPaymentText").style.display = "none"; // Show text
} else {
    paymentScreenshotElement.style.display = "none"; // Hide the image
    document.getElementById("noPaymentText").style.display = "block"; // Show text
}

    let orderItemsTable = document.getElementById("modalOrderItems");
    orderItemsTable.innerHTML = ""; // Clear previous items

    products.forEach(product => {
        let row = `<tr>
            <td>${product.name}</td>
            <td>₱${parseFloat(product.price).toFixed(2)}</td>
            <td>${product.quantity}</td>
            <td>₱${(product.price * product.quantity).toFixed(2)}</td>
        </tr>`;
        orderItemsTable.innerHTML += row;
    });
    

    // Show the modal
    let modal = new bootstrap.Modal(document.getElementById("orderModal"));
    modal.show();
}

function openCancelModal(orderId) {
    Swal.fire({
        title: "Cancel Order",
        input: "text",
        inputLabel: "Enter cancellation reason",
        inputPlaceholder: "Type your reason here...",
        inputValidator: (value) => {
            if (!value.trim()) {
                return "You must enter a reason!";
            }
        },
        showCancelButton: true,
        confirmButtonText: "Proceed",
        cancelButtonText: "Cancel",
        icon: "warning"
    }).then((result) => {
        if (result.isConfirmed) {
            confirmCancellation(orderId, result.value);
        }
    });
}

function confirmCancellation(orderId, reason) {
    let formData = new FormData();
    formData.append("id", orderId); // ✅ Correct
    formData.append("status", "Cancelled"); 
    formData.append("reason", reason);

    fetch("update_order_status.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Server Response:", data); // Debugging

        if (data.trim() === "success") {
            Swal.fire({
                icon: "success",
                title: "Cancelled!",
                text: "Order has been cancelled successfully.",
                confirmButtonText: "OK"
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Error!",
                text: "Server Error: " + data, // Display real error
                confirmButtonText: "OK"
            });
        }
    })
    .catch(error => {
        console.error("Fetch Error:", error);
        Swal.fire({
            icon: "error",
            title: "Error!",
            text: "An error occurred. Please try again.",
            confirmButtonText: "OK"
        });
    });
}


</script>

                </body>    
</html>
<?php
$connection->close();
?>