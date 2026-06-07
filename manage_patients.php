<?php
include_once('../dbconn.php');

$patients = $conn->query("
    SELECT person.nid, person.name, person.mobile_no AS phone, person.gender, person.blood
    FROM patient
    INNER JOIN person ON patient.p_nid = person.nid
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
    <title>Patient Management</title>
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
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .table thead {
            background-color: #e9ecef;
        }
    </style>
</head>
<body class="container py-4">

    <div class="page-header text-center">
        <h2 class="mb-0">Patient Management Panel</h2>
    </div>

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="dashboard.php" class="btn btn-outline-secondary">
            ⬅️ Back to Dashboard
        </a>
        <a href="add_patient.php" class="btn btn-success">
            ➕ Add New Patient
        </a>
    </div>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['msg']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead>
                <tr>
                    <th>NID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Blood Group</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $patients->fetch_assoc()) { ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nid']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= getGenderText($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['blood']) ?></td>
                        <td>
                            <a href="edit_patient.php?nid=<?= $row['nid'] ?>" class="btn btn-sm btn-warning me-1">
                                ✏️ Edit
                            </a>
                            <a href="delete_patient.php?nid=<?= $row['nid'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this patient?')">
                                🗑️ Delete
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($patients->num_rows == 0): ?>
                    <tr>
                        <td colspan="6">No patients found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
