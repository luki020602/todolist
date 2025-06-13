<?php
/**
 * Fungsi untuk menampilkan daftar tugas dalam bentuk tabel Bootstrap.
 * @param array $tasks Array daftar tugas
 */
function tampilkanDaftar(array $tasks): void {
    echo '<table class="table table-striped mt-3">';
    echo '<thead><tr><th>Status</th><th>Judul</th><th>Aksi</th></tr></thead><tbody>';

    foreach ($tasks as $index => $task) {
        $checked = $task['status'] === 'selesai' ? 'checked' : '';
        $rowClass = $task['status'] === 'selesai' ? 'table-success' : '';

        echo "<tr class='$rowClass'>";
        echo "<td>
                <form method='post' class='d-inline'>
                    <input type='hidden' name='toggle_index' value='$index'>
                    <input type='checkbox' onchange='this.form.submit()' $checked>
                </form>
              </td>";
        echo "<td>" . htmlspecialchaars($task['judul']) . "</td>";
        echo "<td>
                <form method='post' class='d-inline'>
                    <input type='hidden' name='hapus_index' value='$index'>
                    <button class='btn btn-sm btn-danger'>Hapus</button>
                </form>
              </td>";
        echo "</tr>";
    }

    echo '</tbody></table>';
}
?>
