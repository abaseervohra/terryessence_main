<?php
include("header.php");
include('dbcon.php');

$user_id = 3;


$userQuery = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
$userQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$userQuery->execute();
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_img = $user['user_img'];
    if (isset($_FILES['user_img']) && $_FILES['user_img']['error'] === UPLOAD_ERR_OK) {
        $uploads_dir = 'img/users/';
        $tmp_name = $_FILES['user_img']['tmp_name'];
        $name = basename($_FILES['user_img']['name']);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (in_array($file_extension, $allowed_extensions)) {
            move_uploaded_file($tmp_name, "$uploads_dir/$name");
            $user_img = $name;
        } else {
            echo "<p>Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.</p>";
        }
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $user_desc = trim($_POST['user_desc']);
    $city = trim($_POST['city']);
    $lane = trim($_POST['lane']);
    $zip_code = trim($_POST['zip_code']);
    $country = trim($_POST['country']);

    // Update user details in the database
    try {
        $updateUserQuery = $pdo->prepare("UPDATE users SET user_img = :user_img, username = :username, email = :email, phone = :phone, address = :address, user_desc = :user_desc, city = :city, lane = :lane, zip_code = :zip_code, country = :country WHERE id = :user_id");
        $updateUserQuery->bindParam(':user_img', $user_img);
        $updateUserQuery->bindParam(':username', $username);
        $updateUserQuery->bindParam(':email', $email);
        $updateUserQuery->bindParam(':phone', $phone);
        $updateUserQuery->bindParam(':address', $address);
        $updateUserQuery->bindParam(':user_desc', $user_desc);
        $updateUserQuery->bindParam(':city', $city);
        $updateUserQuery->bindParam(':lane', $lane);
        $updateUserQuery->bindParam(':zip_code', $zip_code);
        $updateUserQuery->bindParam(':country', $country);
        $updateUserQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($updateUserQuery->execute()) {
            echo "<p>Profile updated successfully!</p>";
            $userQuery->execute();
            $user = $userQuery->fetch(PDO::FETCH_ASSOC);
        } else {
            echo "<p>Error updating profile. Please try again.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error: " . $e->getMessage() . "</p>";
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
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="user-details">
            <div class="user-header">
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
            </div>
            <div class="row user-info align-items-start justify-content-between">
                <!-- User Profile Picture and Info Column -->
                <div class="col-md-4 text-center">
                    <img src="img/users/<?php echo htmlspecialchars($user['user_img']); ?>" alt="Profile Picture"
                        class="profile-pic">
                    <p class="mt-2"><strong><?php echo htmlspecialchars($user['username']); ?></strong></p>
                    <p><?php echo htmlspecialchars($user['user_desc']); ?></p>
                    
                    <div class="form-group">
                            <label>User Image</label>
                            <input type="file" name="user_img" class="form-control">
                        </div>
                </div>

                <div class="col-md-4">
                   
                       
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control"
                                value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control"
                                value="<?php echo htmlspecialchars($user['phone']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control"
                                value="<?php echo htmlspecialchars($user['address']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="user_desc"
                                class="form-control"><?php echo htmlspecialchars($user['user_desc']); ?></textarea>
                        </div>
                       
                </div>

                <!-- Additional Information Column -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" class="form-control"
                            value="<?php echo htmlspecialchars($user['city']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Lane</label>
                        <input type="text" name="lane" class="form-control"
                            value="<?php echo htmlspecialchars($user['lane']); ?>">
                    </div>
                    <div class="form-group">
                        <label>Zip Code</label>
                        <input type="text" name="zip_code" class="form-control"
                            value="<?php echo htmlspecialchars($user['zip_code']); ?>">
                    </div>
                    <div class="form-group">
                        <label>country</label>
                        <input type="text" name="country" class="form-control" 
                        value="<?php echo htmlspecialchars($user['country']); ?>"
                        >

                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>