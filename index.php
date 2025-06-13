<?php
session_start();  // Mulai sesi untuk menyimpan data tugas secara sementara

// Inisialisasi data tugas jika belum ada dalam sesi
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [
        ['judul' => 'Tugas Algoritma', 'status' => 'belum'],
        ['judul' => 'Ujian Algoritma', 'status' => 'selesai']
    ];
}

// Tambah tugas baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['judul'])) {
    $judul = trim($_POST['judul']);
    if (!empty($judul)) {
        $_SESSION['tasks'][] = ['judul' => $judul, 'status' => 'belum'];
    }
}

// Toggle status tugas (selesai/belum)
if (isset($_POST['toggle_index'])) {
    $i = $_POST['toggle_index'];
    if (isset($_SESSION['tasks'][$i])) {
        $_SESSION['tasks'][$i]['status'] = $_SESSION['tasks'][$i]['status'] === 'selesai' ? 'belum' : 'selesai';
    }
}

// Hapus tugas berdasarkan index
if (isset($_POST['hapus_index'])) {
    $i = $_POST['hapus_index'];
    if (isset($_SESSION['tasks'][$i])) {
        unset($_SESSION['tasks'][$i]);
        $_SESSION['tasks'] = array_values($_SESSION['tasks']); // reset index
    }
}

// Fungsi untuk menampilkan daftar tugas dalam bentuk tabel Bootstrap.
function tampilkanDaftar(array $tasks): void {
    echo '<table class="table table-bordered table-hover mt-4">';
    echo '<thead><tr><th>Status</th><th>Judul</th><th>Aksi</th></tr></thead><tbody>';

    foreach ($tasks as $index => $task) {
        $checked = $task['status'] === 'selesai' ? 'checked' : '';  // Cek jika tugas selesai
        $rowClass = $task['status'] === 'selesai' ? 'table-success' : '';  // Jika selesai, beri kelas table-success

        echo "<tr class='$rowClass'>";
        echo "<td>
                <form method='post' class='d-inline'>
                    <input type='hidden' name='toggle_index' value='$index'>
                    <input type='checkbox' onchange='this.form.submit()' $checked>
                </form>
              </td>";
        echo "<td>" . htmlspecialchars($task['judul']) . "</td>";
        echo "<td>
                <form method='post' class='d-inline'>
                    <input type='hidden' name='hapus_index' value='$index'>
                    <button class='btn btn-sm btn-danger'><i class='bi bi-trash'></i> Hapus</button>
                </form>
              </td>";
        echo "</tr>";
    }

    echo '</tbody></table>';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Belajar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet"> <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="style.css"> <!-- Link ke style.css -->
</head>
<body class="bg-light">

    <div class="container py-4">
        <!-- Header Section -->
        <header class="text-center mb-4 shadow-sm p-4 rounded-3 bg-success">
            <h1 class="text-white">Tambah Jadwal Belajar</h1>  <!-- Teks di dalam header sekarang putih -->
        </header>

        <!-- Form Tambah Tugas -->
        <div class="card mb-4 shadow-lg border-0 rounded-3">
            <div class="card-body">
                <form method="post" class="d-flex gap-2">
                    <input type="text" name="judul" class="form-control form-control-lg" placeholder="Tugas baru..." required>
                    <button type="submit" class="btn btn-success btn-lg">Tambah <i class="bi bi-plus-circle"></i></button>
                </form>
            </div>
        </div>

        <!-- Daftar Tugas -->
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body">
                <h5 class="card-title">Daftar Jadwal</h5>
                <?php tampilkanDaftar($_SESSION['tasks']); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS -->
</body>
</html>
