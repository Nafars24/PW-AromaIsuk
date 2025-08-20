<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'aroma_isuk');
$admin_id = $_SESSION['admin_id'];
$result = $conn->query("SELECT profile_pic FROM admin WHERE id = $admin_id");
$admin = $result->fetch_assoc();
$profilePic = !empty($admin['profile_pic']) ? $admin['profile_pic'] : 'image/admin_profile.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #3b2f2f;
            color: #f5ebe0;
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2e2625;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
        }

        .sidebar .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #d4a373;
            margin-bottom: 10px;
        }

        .sidebar .profile h3 {
            color: #d4a373;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #f5ebe0;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #6f4e37;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        .dashboard-main {
            flex-grow: 1;
            padding: 20px;
        }

        .dashboard-main h1 {
            font-size: 24px;
            color: #d4a373;
            margin-bottom: 10px;
        }

        .dashboard-main p {
            color: #c8b6a6;
            margin-bottom: 20px;
        }

        #content-area {
            background-color: #2e2625;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            border: 1px solid #6c4e4e;
            padding: 10px;
            text-align: left;
            color: #f5ebe0;
        }

        table th {
            background-color: #6f4e37;
            color: #f5ebe0;
        }

        table tr:nth-child(even) {
            background-color: #3b2f2f;
        }

        table tr:nth-child(odd) {
            background-color: #2e2625;
        }

        table tr:hover {
            background-color: #8d6346;
        }

        button {
            background-color: #6f4e37;
            color: #f5ebe0;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #8d6346;
        }

        .modal { display: none; position: fixed; z-index: 1; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: white; margin: 10% auto; padding: 20px; width: 50%; }
        .close { float: right; font-size: 28px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="profile">
            <img src="<?= $profilePic ?>" alt="Admin Profile">
            <h3>Admin</h3>
        </div>  
        <ul>
            <li><a href="#" onclick="loadContent('statistik')"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#" onclick="loadContent('hubungi')"><i class="fas fa-envelope"></i> Kelola Hubungi</a></li>
            <li><a href="#" onclick="loadContent('menu')"><i class="fas fa-mug-hot"></i> Kelola Kopi</a></li>
            <li><a href="#" onclick="loadContent('users')"><i class="fas fa-users"></i> Kelola Users</a></li>
            <li><a href="#" onclick="loadContent('order')"><i class="fas fa-shopping-cart"></i> Kelola Pesanan</a></li>
            <li><a href="#" onclick="loadContent('edit_profile')"><i class="fas fa-user-edit"></i> Edit Profil</a></li>
            <li><a href="?logout=true"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div id="menuModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <iframe id="menuFrame" src="" style="width:100%; height:400px; border:none;"></iframe>
    </div>
    </div>

    <main class="dashboard-main">
        <h1>Selamat Datang, Admin</h1>
        <p>Gunakan menu di sebelah kiri untuk mengelola website.</p>

        <div id="content-area">
        </div>
    </main>

    <script>
    function loadContent(page) {
    const contentArea = document.getElementById('content-area');
    contentArea.innerHTML = '<p>Memuat...</p>';
    const xhr = new XMLHttpRequest();
    xhr.open('GET', page + '.php', true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            contentArea.innerHTML = xhr.responseText;

            if (document.getElementById('select_all')) {
                document.getElementById('select_all').addEventListener('click', function () {
                    const checkboxes = document.querySelectorAll('.select-item');
                    checkboxes.forEach(cb => cb.checked = this.checked);
                });
            }

            if (document.getElementById('delete-button')) {
                document.getElementById('delete-button').addEventListener('click', deleteSelected);
            }

            if (document.getElementById('search-form')) {
                document.getElementById('search-form').addEventListener('submit', function (event) {
                    event.preventDefault();
                    searchEntries(page);
                });
            }

            // Initialize reply buttons after content is loaded
            initializeReplyButtons();
        } else {
            contentArea.innerHTML = '<p>Gagal memuat konten.</p>';
        }
    };
    xhr.send();
}

function deleteSelected() {
    const ids = [...document.querySelectorAll('.select-item:checked')].map(cb => cb.value);
    if (ids.length > 0) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'hubungi.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                loadContent('hubungi');
            }
        };
        xhr.send('delete_ids=' + encodeURIComponent(ids.join(',')));
    }
}

function searchEntries(page) {
    const query = document.getElementById('search-query').value;
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `${page}.php?search=${encodeURIComponent(query)}`, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            document.getElementById('content-area').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function openModal(action, id = '') {
    let url = '';
    if (action === 'edit') {
        url = `menu_form.php?action=edit&id=${id}`;
    } else if (action === 'add') {
        url = 'tambah_menu.php?action=add';
    } else if (action === 'reply') {
        url = `hubungi_form.php?id=${id}`;
    }
    document.getElementById('menuFrame').src = url;
    document.getElementById('menuModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('menuModal').style.display = 'none';
}

function loadChartData() {
    fetch('get_order_stats.php')
        .then(response => response.json())
        .then(data => {
            var ctx = document.getElementById('statistikChart').getContext('2d');

            var chartData = {
                labels: data.labels,
                datasets: [{
                    label: 'Jumlah Pesanan per Hari',
                    data: data.values,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            };

            var statistikChart = new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
}

document.addEventListener('click', function(event) {
    if (event.target.matches('.open-modal')) {
        let action = event.target.getAttribute('data-action');
        let id = event.target.getAttribute('data-id') || '';
        openModal(action, id);
    }
});

// This function initializes reply buttons for dynamically loaded content
function initializeReplyButtons() {
    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            openModal('reply', id);
        });
    });
}

$(document).ready(function () {
    $(document).on("submit", ".confirm-form", function (e) {
        e.preventDefault(); // Mencegah halaman berpindah

        let form = $(this);
        let orderId = form.find("[name='order_id']").val();

        $.ajax({
            type: "POST",
            url: "order.php",
            data: { order_id: orderId },
            success: function (response) {
                $(".dashboard").load("order.php"); // Refresh tabel setelah konfirmasi
            },
            error: function () {
                alert("Gagal mengonfirmasi pesanan!");
            }
        });
    });
});
</script>

</body>
</html>
