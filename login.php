<?php
session_start();
include_once "templates/header.php";
require_once "config/database.php";

if (isset($_SESSION['is_authenticated'])) {
    header('Location: index.php');
}

$email = $password = "";
$email_err = $password_err = "";

if (isset($_POST['submit'])) {
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

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            if ($user['password'] == md5($password)) {
                $_SESSION['login_success'] = "Login Successful!";
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['is_authenticated'] = true;
                header('Location: index.php');
            } else {
                $_SESSION['error_msg'] = "Invalid Credentials!";
            }
        } else {
            $_SESSION['error_msg'] = "Invalid Credentials!";
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<div class="row justify-content-center">
    <div class="col-md-4">
        <?php if (isset($_SESSION['success_msg'])) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION['success_msg'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['success_msg']);
        endif; ?>
        <?php if (isset($_SESSION['error_msg'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error_msg'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['error_msg']);
        endif; ?>
        <div class="card">
            <div class="card-header">
                <h1 class="card-title fw-light">Login</h1>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="application/x-www-form-urlencoded">
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
                    <button type="submit" name="submit" class="btn btn-primary">Login</button>
                    <p class="mt-3">Don't have an account yet? <a href="register.php">Register</a></p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "templates/footer.php"; ?>