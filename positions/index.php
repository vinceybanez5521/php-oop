<?php
session_start();
include_once "../is-authenticated.php";
include_once "../templates/header.php";
require_once "../config/database.php";

$sql = "SELECT * FROM positions";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$positions = $result->fetch_all(MYSQLI_ASSOC);
// print_r($positions);
$stmt->close();
$conn->close();
?>

<div class="row">
    <div class="col-12">
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
            <div class="card-header d-md-flex justify-content-between align-items-center">
                <h1 class="card-title fw-light">Positions</h1>
                <a href="create.php" class="btn btn-primary">Add New Position</a>
            </div>
            <div class="card-body">
                <?php if (empty($positions)) : ?>
                    <p class="lead text-center">No positions yet</p>
                <?php endif; ?>

                <?php if (!empty($positions)) : ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date Added</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($positions as $pos) : ?>
                                    <tr>
                                        <td><?= $pos['name'] ?></td>
                                        <td><?= date_format(date_create($pos['date_added']), 'F j, Y h:i:s a') ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $pos['id'] ?>" class="btn btn-success">Edit</a>
                                            <form action="delete.php" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $pos['id'] ?>">
                                                <button class="btn btn-danger delete-position">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once "../templates/footer.php"; ?>