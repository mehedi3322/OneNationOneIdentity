<?php
include_once('../dbconn.php'); 
session_start();

if (!isset($_SESSION['admin_username'])) {
    header("Location: index.php");
    exit();
}

function getCount($table) {
    global $conn;
    $res = $conn->query("SELECT COUNT(*) AS count FROM $table");
    return $res->fetch_assoc()['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background-color: #f3f4f6;
        }

        .content {
            flex: 1;
        }

        footer {
            background-color: #f8f9fa;
            padding: 15px 0;
            text-align: center;
        }

        .dashboard-card {
            transition: transform 0.3s ease-in-out;
            border: none;
            border-radius: 15px;
            color: white;
        }

        .dashboard-card:hover {
            transform: scale(1.05);
        }

        .bg-doctor { background: linear-gradient(135deg, #007bff, #00c6ff); }
        .bg-patient { background: linear-gradient(135deg, #28a745, #88e39e); }
        .bg-hospital { background: linear-gradient(135deg, #17a2b8, #64c3d9); }

        .letter {
            display: inline-block;
            transition: color 0.5s ease-in-out;
        }

        .btn-group a {
            margin: 5px;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">
                Welcome, <span id="admin-username" style="font-size:50px;" class="fw-bold text-dark"></span>
            </h2>
        </div>

        <a href="logout.php" class="btn btn-danger mb-4"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card dashboard-card bg-doctor shadow">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-person-badge fs-1 me-3"></i>
                        <div>
                            <h4>Doctors</h4>
                            <h2><?= getCount("doctor") ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card bg-patient shadow">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-people fs-1 me-3"></i>
                        <div>
                            <h4>Patients</h4>
                            <h2><?= getCount("patient") ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card dashboard-card bg-hospital shadow">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-hospital fs-1 me-3"></i>
                        <div>
                            <h4>Hospitals</h4>
                            <h2><?= getCount("hospital") ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-group">
            <a href="manage_doctors.php" class="btn btn-outline-primary"><i class="bi bi-gear-fill me-1"></i> Manage Doctors</a>
            <a href="manage_patients.php" class="btn btn-outline-success"><i class="bi bi-person-lines-fill me-1"></i> Manage Patients</a>
            <a href="manage_hospitals.php" class="btn btn-outline-info"><i class="bi bi-building me-1"></i> Manage Hospitals</a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <p class="text-muted mb-0">&copy; 2025 IdentityBasedHealthcareSystem</p>
</footer>

<!-- Scripts -->
<script>
    window.onload = function () {
        const username = "<?php echo htmlspecialchars($_SESSION['admin_username']); ?>";
        const usernameElement = document.getElementById('admin-username');
        
        let letterHTML = '';
        for (let i = 0; i < username.length; i++) {
            letterHTML += `<span class="letter">${username[i]}</span>`;
        }
        usernameElement.innerHTML = letterHTML;

        const letters = document.querySelectorAll('.letter');
        function changeLetterColor() {
            letters.forEach(letter => {
                letter.style.color = getRandomColor();
            });
        }
        setInterval(changeLetterColor, 1000);
    };

    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }
</script>
</body>
</html>
