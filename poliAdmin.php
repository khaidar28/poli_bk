<?php
if (!isset($_SESSION)) {
  session_start();
}

// Include the database connection file (koneksi.php)
include_once("koneksi.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_obat_modal'])) {
  $id = $_POST['id'];
  $newNama_poli = $_POST['new_nama_poli'];
  $newKeterangan = $_POST['new_keterangan'];

  // Update obat in the database using prepared statement
  $updateQuery = "UPDATE poli SET nama_poli=?, keterangan=? WHERE id=?";
  $stmt = $mysqli->prepare($updateQuery);
  $stmt->bind_param("sssi", $newNamaObat, $newKemasan, $newHarga, $id);

  if ($stmt->execute()) {
    // Update successful
    header("Location: poli.php");
    exit();
  } else {
    // Update failed, handle error (you may redirect or display an error message)
    echo "Update failed: " . $stmt->error;
  }

  $stmt->close();
  
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_obat'])) {
  $newNama_poli = $_POST['add_nama_poli'];
  $newKeterangan = $_POST['add_keterangan'];

  // Insert new obat into the database using prepared statement
  $insertQuery = "INSERT INTO poli (nama_poli, keterangan) VALUES (?, ?)";
  $stmt = $mysqli->prepare($insertQuery);
  $stmt->bind_param("ss", $newNama_poli, $newKeterangan);

  if ($stmt->execute()) {
    // Insertion successful
    header("Location: poli.php");
    exit();
  } else {
    // Insertion failed, handle error (you may redirect or display an error message)
    echo "Insertion failed: " . $stmt->error;
  }

  $stmt->close();
}

// Menangani penghapusan obat dan catatan terkait di detail_periksa
if (isset($_POST['delete_obat'])) {
  $id = $_POST['id'];

  // Lanjutkan dengan penghapusan obat
  $deleteObatQuery = "DELETE FROM poli WHERE id=?";
  $stmtObat = $mysqli->prepare($deleteObatQuery);
  $stmtObat->bind_param("i", $id);

  // Jalankan penghapusan obat
  if ($stmtObat->execute()) {
      // Penghapusan obat berhasil
      // Bersihkan output buffer
      ob_clean();

      // Redirect kembali ke halaman utama atau tampilkan pesan keberhasilan
      header("Location: poli.php");
      exit();
  } else {
      // Penghapusan obat gagal, tangani kesalahan
      echo "Penghapusan obat gagal: " . $stmtObat->error;
  }

  // Tutup prepared statement
  $stmtObat->close();
}





// Fetch data from the 'obat' table
$obatQuery = "SELECT * FROM poli";
$obatResult = $mysqli->query($obatQuery);

// Fetch the data as an associative array
$obatData = $obatResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Add your head section here -->

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/css/bootstrap.min.css">

  <!-- Add other necessary CSS links here -->
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addModal">Tambah poli</button>

                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama poli</th>
                      <th>Keterangan</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($obatData as $obatRow) {
                      echo "<tr>";
                      echo "<td>" . $obatRow['id'] . "</td>";
                      echo "<td>" . $obatRow['nama_poli'] . "</td>";
                      echo "<td>" . $obatRow['keterangan'] . "</td>";
                      echo "<td>
                                                <form method='post' action=''>
                                                    <input type='hidden' name='id' value='" . $obatRow['id'] . "'>
                                                    <input type='hidden' name='new_nama_poli' value='" . $obatRow['nama_poli'] . "'>
                                                    <input type='hidden' name='new_keterangan' value='" . $obatRow['keterangan'] . "'>

                                                    <button type='button' name='update_obat' class='btn btn-warning btn-sm update-btn' data-toggle='modal' data-target='#updateModal' 
                                                    data-id='" . $obatRow['id'] . "' 
                                                    data-nama_poli='" . $obatRow['nama_poli'] . "' 
                                                    data-keteranagn='" . $obatRow['keterangan'] . "' >Update</button>
                                                    
                                                    <form method='post' action=''>
                                                        <input type='hidden' name='id' value='" . $obatRow['id'] . "'>
                                                        <button type='submit' name='delete_obat' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\");'>Delete</button>
                                                    </form>
                                                </form>
                                            </td>";
                      echo "</tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateModalLabel">Perbarui poli</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="menuAdmin.php">
            <!-- Replace with the actual update PHP file -->
            <input type="hidden" name="id" id="update_id">
            <div class="form-group">
              <label for="update_nama_obat">Nama poli</label>
              <input type="text" class="form-control" id="update_nama_obat" name="new_nama_obat" required>
            </div>
            <div class="form-group">
              <label for="update_kemasan">Kemasan</label>
              <input type="text" class="form-control" id="update_kemasan" name="new_kemasan" required>
               </div>
            <button type="submit" name="update_obat_modal" class="btn btn-primary">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<!-- Modal for adding obat -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Tambah poli</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="poli.php">
          <!-- Replace with the actual add PHP file -->
          <div class="form-group">
            <label for="add_nama_poli">Nama poli</label>
            <input type="text" class="form-control" id="add_nama_poli" name="add_nama_poli" required>
          </div>
          <div class="form-group">
            <label for="add_keterangan">Keterangan</label>
            <input type="text" class="form-control" id="add_keterangan" name="add_keterangan" required>
          </div>
          <button type="submit" name="add_obat" class="btn btn-primary">Tambah</button>
        </form>
      </div>
    </div>
  </div>
</div>


  <!-- Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.6.0/js/bootstrap.min.js"></script>

  <!-- Add other necessary script includes here -->

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Add your JavaScript code here
      var updateButtons = document.querySelectorAll('.update-btn');

      updateButtons.forEach(function(button) {
        button.addEventListener('click', function() {
          var id = button.getAttribute('data-id');
          var nama_poli = button.getAttribute('data-nama_poli');
          var keterangan = button.getAttribute('data-keterangan');

          document.getElementById('update_id').value = id;
          document.getElementById('update_nama_poli').value = nama_poli;
          document.getElementById('update_keterangan').value = keterangan;
        });
      });
    });
  </script>
</body>

</html>