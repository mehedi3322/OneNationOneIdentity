<?php
include_once('../dbconn.php');

$doctors = $conn->query("
    SELECT 
        person.nid, person.name, doctor.specialist, 
        person.mobile_no AS phone, person.gender, person.blood
    FROM doctor 
    INNER JOIN person ON doctor.d_nid = person.nid
");

function getGenderText($gender) {
    switch ($gender) {
        case 1: return "Male";
        case 2: return "Female";
        case 3: return "Other";
        default: return "Unknown";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
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

    <div class="page-header">
        <h2 class="mb-0">Doctor Management Panel</h2>
    </div>

    <!-- Success message -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Doctor added successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="dashboard.php" class="btn btn-outline-secondary btn-icon">
            ⬅️ Back to Dashboard
        </a>
        <a href="add_doctor.php" class="btn btn-success btn-icon">
            ➕ Add New Doctor
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-dark">
                <tr>
                    <th>NID</th>
                    <th>Name</th>
                    <th>Specialist</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Blood Group</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($doctors->num_rows > 0): ?>
                    <?php while ($row = $doctors->fetch_assoc()) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nid']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['specialist']) ?></td>
                            <td><?= htmlspecialchars($row['phone']) ?></td>
                            <td><?= getGenderText($row['gender']) ?></td>
                            <td><?= htmlspecialchars($row['blood']) ?></td>
                            <td>
                                <a href="edit_doctor.php?nid=<?= urlencode($row['nid']) ?>" class="btn btn-sm btn-warning btn-icon mb-1">
                                    ✏️ Edit
                                </a>
                                <a href="delete_doctor.php?nid=<?= urlencode($row['nid']) ?>" class="btn btn-sm btn-danger btn-icon" onclick="return confirm('Delete this doctor?')">
                                    🗑️ Delete
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">No doctors found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
