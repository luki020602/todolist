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

// Update tugas berdasarkan index
if (isset($_POST['edit_index'])) {
    $i = $_POST['edit_index'];
    $newTitle = trim($_POST['edit_title']);
    if (!empty($newTitle) && isset($_SESSION['tasks'][$i])) {
        $_SESSION['tasks'][$i]['judul'] = $newTitle;
    }
}

// Fungsi untuk menampilkan daftar tugas dalam bentuk tabel Bootstrap.
function tampilkanDaftar(array $tasks): void {
    echo '<table class="table table-bordered table-hover mt-4">';
    echo '<thead><tr><th>Ceklis</th><th>Status</th><th>Judul</th><th>Aksi</th></tr></thead><tbody>';

    foreach ($tasks as $index => $task) {
        $checked = $task['status'] === 'selesai' ? 'checked' : '';
        $rowClass = $task['status'] === 'selesai' ? 'table-success' : '';
        $statusText = ucfirst($task['status']); // 'Selesai' atau 'Belum'

        echo "<tr class='$rowClass'>";
        
        // Kolom ceklis
        echo "<td>
                <form method='post' class='d-inline'>
                    <input type='hidden' name='toggle_index' value='$index'>
                    <input type='checkbox' onchange='this.form.submit()' $checked>
                </form>
              </td>";
        
        // Kolom status
        echo "<td>$statusText</td>";

        // Kolom judul
        echo "<td>" . htmlspecialchars($task['judul']) . "</td>";

        // Kolom aksi (edit dan hapus)
        echo "<td>
                <form method='post' class='d-inline'>
                    <input type='hidden' name='hapus_index' value='$index'>
                    <button class='btn btn-sm btn-danger'><i class='bi bi-trash'></i> Hapus</button>
                </form>
                <button class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#editModal' onclick='setEditData($index, \"" . htmlspecialchars($task['judul'], ENT_QUOTES) . "\")'>Edit</button>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-4">
        <!-- Header -->
        <header class="text-center mb-4 shadow-sm p-4 rounded-3 bg-success">
            <h1 class="text-white">Tambah Jadwal Belajar</h1>
        </header>

        <!-- Form Tambah -->
        <div class="card mb-4 shadow-lg border-0 rounded-3">
            <div class="card-body">
                <form method="post" class="d-flex gap-2">
                    <input type="text" name="judul" class="form-control form-control-lg" placeholder="Tugas baru..." required>
                    <button type="submit" class="btn btn-success btn-lg">Tambah <i class="bi bi-plus-circle"></i></button>
                </form>
            </div>
        </div>

        <!-- Tabel Tugas -->
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body">
                <h5 class="card-title">Daftar Jadwal</h5>
                <?php tampilkanDaftar($_SESSION['tasks']); ?>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tugas -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Tugas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" name="edit_index" id="edit_index">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Judul Tugas</label>
                            <input type="text" class="form-control" name="edit_title" id="edit_title" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function setEditData(index, title) {
            document.getElementById('edit_index').value = index;
            document.getElementById('edit_title').value = title;
        }
    </script>
</body>
</html>
