<?php
include_once('../dbconn.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hospital_name     = $_POST['hospital_name'];
    $numberof_ward     = $_POST['numberof_ward'];
    $wardfee_perday    = $_POST['wardfee_perday'];
    $numberof_cabin    = $_POST['numberof_cabin'];
    $cabinfee_perday   = $_POST['cabinfee_perday'];
    $password          = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $docreg            = $_POST['docreg'];
    $docregtext        = $_POST['docregtext'];

    // Insert query
    $stmt = $conn->prepare("INSERT INTO hospital 
        (hospital_id, hospital_name, numberof_ward, wardfee_perday, numberof_cabin, cabinfee_perday, password, docreg, docregtext)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $hospital_id = uniqid('HOS-'); // Auto-generate ID

    $stmt->bind_param("ssiiiisss", $hospital_id, $hospital_name, $numberof_ward, $wardfee_perday, $numberof_cabin, $cabinfee_perday, $password, $docreg, $docregtext);

    if ($stmt->execute()) {
        $success = "✅ Hospital added successfully!";
    } else {
        $error = "❌ Error adding hospital: " . $conn->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Hospital</title>
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
        .form-section {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="container py-4">

    <!-- Page Header -->
    <div class="page-header">
        <h2 class="mb-0">Add New Hospital</h2>
    </div>

    <!-- Feedback -->
    <?php if (!empty($success)): ?>
        <div class="alert alert-success text-center"><?= $success ?></div>
    <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger text-center"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form -->
    <div class="form-section">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="hospital_name" class="form-label">Hospital Name</label>
                <input type="text" name="hospital_name" id="hospital_name" class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="numberof_ward" class="form-label">Number of Wards</label>
                    <input type="number" name="numberof_ward" id="numberof_ward" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="wardfee_perday" class="form-label">Ward Fee/Day</label>
                    <input type="number" name="wardfee_perday" id="wardfee_perday" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="numberof_cabin" class="form-label">Number of Cabins</label>
                    <input type="number" name="numberof_cabin" id="numberof_cabin" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cabinfee_perday" class="form-label">Cabin Fee/Day</label>
                    <input type="number" name="cabinfee_perday" id="cabinfee_perday" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Hospital Login Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Doctor Registration Available?</label><br>
                <div class="form-check form-check-inline">
                    <input type="radio" name="docreg" id="docreg_yes" value="Y" class="form-check-input" required>
                    <label for="docreg_yes" class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="docreg" id="docreg_no" value="N" class="form-check-input">
                    <label for="docreg_no" class="form-check-label">No</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="docregtext" class="form-label">Doctor Registration Info (if any)</label>
                <textarea name="docregtext" id="docregtext" class="form-control" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-between">
                <a href="manage_hospitals.php" class="btn btn-secondary">⬅️ Back</a>
                <button type="submit" class="btn btn-primary">✅ Add Hospital</button>
            </div>
        </form>
    </div>

</body>
</html>
