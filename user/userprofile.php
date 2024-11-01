<?php
include("header.php");
include('dbcon.php');

// Hardcoded user ID for testing
$user_id = 1; // Change this to the user ID you want to test with

// Step 1: Query to fetch user details based on user ID
$userQuery = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$userQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$userQuery->execute();
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}

// Step 2: Handling form submission to update user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch and sanitize user input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $user_desc = trim($_POST['user_desc']);

    // Update user details in the database
    $updateUserQuery = $pdo->prepare("UPDATE users SET username = :username, email = :email, phone = :phone, address = :address, user_desc = :user_desc, password_hash = :password_hash, WHERE id = :user_id");
    $updateUserQuery->bindParam(':username', $username);
    $updateUserQuery->bindParam(':email', $email);
    $updateUserQuery->bindParam(':phone', $phone);
    $updateUserQuery->bindParam(':address', $address);
    $updateUserQuery->bindParam(':user_desc', $user_desc);
    $updateUserQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($updateUserQuery->execute()) {
        echo "<p>Profile updated successfully!</p>";
        // Fetch the updated user details to reflect changes
        $userQuery->execute();
        $user = $userQuery->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "<p>Error updating profile. Please try again.</p>";
    }
}
?>

<div class="content-body">
    <div class="row justify-content-between align-items-center mb-10">
        <div class="col-12 col-lg-auto mb-20">
            <div class="page-heading">
                <h3 class="title">User Profile</h3>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="user-details">
            <div class="user-header">
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            </div>
            <div class="row user-info align-items-start justify-content-between">
                <!-- User Profile Picture and Info Column -->
                <div class="col-md-4 text-center">
                    <img src="img/users/<?php echo htmlspecialchars($user['user_img']); ?>" alt="Profile Picture" class="profile-pic">
                    <p class="mt-2"><strong><?php echo htmlspecialchars($user['username']); ?></strong></p>
                    <p><?php echo htmlspecialchars($user['user_desc']); ?></p>
                </div>

                <!-- Edit User Details Form Column -->
                <div class="col-md-4">
                    <form method="POST" action="your_form_action.php">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($user['address']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="user_desc" class="form-control"><?php echo htmlspecialchars($user['user_desc']); ?></textarea>
                        </div>
                       
                </div>

                <!-- Additional Information Column -->
                <div class="col-md-4">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($user['city']); ?>">
                        </div>
                        <div class="form_group">
                            <label>lane</label>
                            <input type="text" name="lane" class="form-control" value="<?php echo htmlspecialchars($user['lane']); ?>">
                        </div>
                        <div class="form_group">
                          <label>Zip code</label>
                          <input type="text" name="zip_code" class="form-control" value="<?php echo htmlspecialchars($user['zip_code']); ?>">
                        </div>
                        <div class="form_group">
                            <label>Country</label>
                            <input type="text" name="country" class="form-control" value="<?php echo htmlspecialchars($user['country']) ?>" >
                        </div>
                   
                        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include("footer.php"); ?>
