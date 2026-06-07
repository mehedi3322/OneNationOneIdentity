<?php
include_once('../dbconn.php');
session_start();

// Redirect if not logged in
if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $d_nid        = $_POST['d_nid'];
    $dmdc_id      = $_POST['dmdc_id'];
    $visiting_fee = $_POST['visiting_fee'];
    $password     = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $specialist   = $_POST['specialist'];
    $name         = $_POST['name'];
    $mobile_no    = $_POST['mobile_no'];
    $gender       = (int)$_POST['gender'];
    $blood        = $_POST['blood'];

    // New numeric fields
    $finger_print = isset($_POST['finger_print']) ? (int)$_POST['finger_print'] : null;
    $retina_print = isset($_POST['retina_print']) ? (int)$_POST['retina_print'] : null;

    $conn->begin_transaction();

    try {
        // Insert into person table with finger_print and retina_print
        $stmt1 = $conn->prepare("INSERT INTO person (nid, name, mobile_no, gender, blood, finger_print, retina_print) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt1->bind_param("sssissi", $d_nid, $name, $mobile_no, $gender, $blood, $finger_print, $retina_print);
        $stmt1->execute();

        // Insert into doctor table (same finger_print and retina_print if needed)
        // If doctor table also needs these fields, add them; otherwise remove from here
        $stmt2 = $conn->prepare("INSERT INTO doctor (d_nid, dmdc_id, visiting_fee, password, specialist, name, mobile_no, gender, blood) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("ssissssss", $d_nid, $dmdc_id, $visiting_fee, $password, $specialist, $name, $mobile_no, $gender, $blood);
        $stmt2->execute();

        $conn->commit();
        $message = "Doctor added successfully!";
    } catch (mysqli_sql_exception $e) {
        $conn->rollback();
        $message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Doctor</title>
    <link href="assets/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-5">
    <h2 class="mb-4">Add New Doctor</h2>

    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="row mb-3">
            <div class="col-md-6">
                <label>NID:</label>
                <input type="text" name="d_nid" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>DMDC ID:</label>
                <input type="text" name="dmdc_id" class="form-control" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Visiting Fee (৳):</label>
                <input type="number" name="visiting_fee" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Specialist:</label>
            <input type="text" name="specialist" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Full Name:</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label>Mobile No:</label>
                <input type="text" name="mobile_no" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Gender:</label>
                <select name="gender" class="form-select" required>
                    <option value="">Select</option>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                    <option value="3">Other</option>
                </select>
            </div>
            <div class="col-md-3">
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
        </div>

        <!-- New numeric inputs for fingerprint and retina print -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label>Finger Print (Numeric):</label>
                <input type="number" name="finger_print" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label>Retina Print (Numeric):</label>
                <input type="number" name="retina_print" class="form-control" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add Doctor</button>
        <a href="manage_doctors.php" class="btn btn-secondary">Back</a>
    </form>
</body>
</html>
