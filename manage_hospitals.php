<?php
include_once('../dbconn.php');

$hospitals = $conn->query("SELECT * FROM hospital");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hospital Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f5f8fa;
        }
        .page-header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-icon {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body class="container py-4">

    <!-- Page Header -->
    <div class="page-header">
        <h2 class="mb-0">Hospital Management Panel</h2>
    </div>

    <!-- Navigation Buttons -->
    <div class="mb-4 d-flex justify-content-between">
        <a href="dashboard.php" class="btn btn-outline-secondary btn-icon">
            ⬅️ Back to Dashboard
        </a>
        <a href="add_hospital.php" class="btn btn-success btn-icon">
            ➕ Add New Hospital
        </a>
    </div>

    <!-- Hospital Table -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Hospital Name</th>
                    <th>No. of Wards</th>
                    <th>No. of Cabins</th>
                    <th>Doctor Reg.</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($hospitals->num_rows > 0): ?>
                    <?php while ($row = $hospitals->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['hospital_id']) ?></td>
                            <td><?= htmlspecialchars($row['hospital_name']) ?></td>
                            <td><?= htmlspecialchars($row['numberof_ward']) ?></td>
                            <td><?= htmlspecialchars($row['numberof_cabin']) ?></td>
                            <td><?= htmlspecialchars($row['docreg']) ?></td>
                            <td>
                                <a href="edit_hospital.php?hospital_id=<?= urlencode($row['hospital_id']) ?>" class="btn btn-sm btn-warning btn-icon mb-1">
                                    ✏️ Edit
                                </a>
                                <a href="delete_hospital.php?hospital_id=<?= urlencode($row['hospital_id']) ?>" class="btn btn-sm btn-danger btn-icon" onclick="return confirm('Delete this hospital?')">
                                    🗑️ Delete
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No hospitals found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
