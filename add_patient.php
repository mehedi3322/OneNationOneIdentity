<?php
include_once('../dbconn.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nid           = $_POST['nid'];
    $name          = $_POST['name'];
    $mobile_no     = $_POST['mobile_no'];
    $gender        = (int)$_POST['gender'];
    $blood         = $_POST['blood'];
    $finger_print  = $_POST['finger_print'];
    $retina_print  = $_POST['retina_print'];
    $password      = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $conn->begin_transaction();

    try {
        // Check if nid already exists in person
        $stmt_check = $conn->prepare("SELECT nid FROM person WHERE nid = ?");
        $stmt_check->bind_param("s", $nid);
        $stmt_check->execute();
        $stmt_check->store_result();

        // If not exists, insert into person
        if ($stmt_check->num_rows === 0) {
            $stmt1 = $conn->prepare("INSERT INTO person (nid, name, mobile_no, gender, blood, finger_print, retina_print) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt1->bind_param("sssisss", $nid, $name, $mobile_no, $gender, $blood, $finger_print, $retina_print);
            $stmt1->execute();
        }

        // Insert into patient table
        $stmt2 = $conn->prepare("INSERT INTO patient (p_nid, password) VALUES (?, ?)");
        $stmt2->bind_param("ss", $nid, $password);
        $stmt2->execute();

        $conn->commit();
        $message = "Patient added successfully!";
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Patient</title>
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2 class="mb-4">Add New Patient</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label>NID:</label>
            <input type="text" name="nid" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Full Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Mobile No:</label>
            <input type="text" name="mobile_no" class="form-control" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Gender:</label>
                <select name="gender" class="form-select" required>
                    <option value="">Select</option>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                    <option value="3">Other</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Blood Group:</label>
                <select name="blood" class="form-select" required>
                    <option value="">Select</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Finger Print (numeric):</label>
                <input type="number" name="finger_print" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Ratina Print (numeric):</label>
                <input type="number" name="retina_print" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add Patient</button>
        <a href="manage_patients.php" class="btn btn-secondary">Back</a>
    </form>
</body>
</html>
