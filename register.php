<?php
session_start();
include_once "templates/header.php";
require_once "config/database.php";

if (isset($_SESSION['is_authenticated'])) {
    header('Location: index.php');
}

$name = $email = $password = "";
$name_err = $email_err = $password_err = "";

if (isset($_POST['submit'])) {
    // Validate name
    if (empty($_POST['name'])) {
        $name_err = "Please enter name";
    } else {
        $name = $_POST['name'];
    }

    // Validate email
    if (empty($_POST['email'])) {
        $email_err = "Please enter email";
    } else {
        $email = $_POST['email'];
    }

    // Validate password
    if (empty($_POST['password'])) {
        $password_err = "Please enter password";
    } else {
        $password = $_POST['password'];
    }

    if (empty($name_err) && empty($email_err) && empty($password_err)) {
        // Check if email already exists
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows() == 1) {
            $email_err = "Email already exists";
            $stmt->close();
        } else {
            $hashed_password = md5($password);

            $sql = "INSERT INTO users(name, email, password) VALUES(?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['success_msg'] = "Registration Successful!";
                header('Location: login.php');
            } else {
                $_SESSION['error_msg'] = "Registration Failed!";
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <?php if (isset($_SESSION['error_msg'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error_msg'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['error_msg']);
        endif; ?>
        <div class="card">
            <div class="card-header">
                <h1 class="card-title fw-light">Register</h1>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="application/x-www-form-urlencoded">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control <?= $name_err ? 'is-invalid' : null ?>" value="<?= $name ?>">
                        <span class="invalid-feedback">
                            <?= $name_err ?>
                        </span>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control <?= $email_err ? 'is-invalid' : null ?>" value="<?= $email ?>">
                        <span class="invalid-feedback">
                            <?= $email_err ?>
                        </span>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control <?= $password_err ? 'is-invalid' : null ?>" value="<?= $password ?>">
                        <span class="invalid-feedback">
                            <?= $password_err ?>
                        </span>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Register</button>
                    <p class="mt-3">Already have an account? <a href="login.php">Login</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "templates/footer.php"; ?>