<?php
session_start();
include_once "../is-authenticated.php";
include_once "../templates/header.php";
require_once "../config/database.php";

$sql = "SELECT e.*, CONCAT(e.first_name, ' ', e.last_name) AS full_name, p.name AS position FROM employees e INNER JOIN positions p ON e.position_id=p.id ORDER BY e.first_name";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$employees = $result->fetch_all(MYSQLI_ASSOC);
// print_r($employees);
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
                <h1 class="card-title fw-light">Employees</h1>
                <a href="create.php" class="btn btn-primary">Add New Employee</a>
            </div>
            <div class="card-body">
                <?php if (empty($employees)) : ?>
                    <p class="lead text-center">No employees yet</p>
                <?php endif; ?>

                <?php if (!empty($employees)) : ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Gender</th>
                                    <th>Position</th>
                                    <th>Salary</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $employee) : ?>
                                    <tr>
                                        <td><?= $employee['full_name'] ?></td>
                                        <td><?= ucfirst($employee['gender']) ?></td>
                                        <td><?= $employee['position'] ?></td>
                                        <td><?= $employee['salary'] ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $employee['id'] ?>" class="btn btn-success">Edit</a>
                                            <form action="delete.php" method="POST" class="d-inline">
                                                <input type="hidden" name="id" value="<?= $employee['id'] ?>">
                                                <button class="btn btn-danger delete-employee">Delete</button>
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