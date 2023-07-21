<?php
session_start();
include_once "../is-authenticated.php";
include_once "../templates/header.php";
require_once "../config/database.php";

$name = "";
$name_err = "";

if (isset($_POST['submit'])) {
    // Validate name
    if (empty($_POST['name'])) {
        $name_err = "Please enter name";
    } else {
        $name = $_POST['name'];
    }

    if (empty($name_err)) {
        $sql = "INSERT INTO positions(name) VALUES(?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            // echo "Position Added! " . $stmt->affected_rows;
            $_SESSION['success_msg'] = "Position Added!";
            header('Location: ./');
        } else {
            // echo "Position Not Added!";
            $_SESSION['error_msg'] = "Position Not Added!";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <?php if (isset($_SESSION['error_msg'])) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION['error_msg'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php unset($_SESSION['error_msg']);
        endif; ?>
        <div class="card">
            <div class="card-header d-md-flex justify-content-between align-items-center">
                <h1 class="card-title fw-light">Add New Position</h1>
                <a href="./" class="btn btn-primary">Positions</a>
            </div>
            <div class="card-body">
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="application/x-www-form-urlencoded">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control <?= $name_err ? 'is-invalid' : null; ?>" id="name" name="name" value="<?= $name ?>">
                        <span class="invalid-feedback">
                            <?= $name_err ?>
                        </span>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "../templates/footer.php"; ?>