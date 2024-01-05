<section class="content">
<div class="container-fluid">
<div class="row">

<div class="col-md-12">

<div class="card-header">
</div>


<form id="quickForm">
<div class="card-body">
  <div class="form-group">
     <label for="exampleInputEmail1">Nama pasien</label>
     <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Nama pasien">
   </div>
   <div class="form-group">
     <label for="exampleInputPassword1">Alamat</label>
     <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
   </div>
   <div class="form-group">
     <label for="exampleInputPassword1">Nomor ktp</label>
     <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
   </div>
   <div class="form-group">
     <label for="exampleInputPassword1">Nomor hp</label>
     <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
   </div>
   <div class="form-group">
     <label for="exampleInputPassword1">Nomor RM</label>
     <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
   </div>
</div>

<div class="card-footer">
<button type="submit" class="btn btn-primary">Simpan</button>
</div>
</form>
</div>

</div>


<div class="col-md-6">
</div>

</div>

</div>
</section>


<?php
if (!isset($_SESSION)) {
  session_start();
}

// Include the database connection file (koneksi.php)
include_once("koneksi.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_obat'])) {
  $id = $_POST['id'];
  $newNama = $_POST['new_nama'];
  $newAlamat = $_POST['new_alamat'];
  $newNo_ktp = $_POST['new_no_ktp'];
  $newNo_hp = $_POST['new_no_hp'];
  $newNo_ktp = $_POST['new_no_ktp'];
  $newNo_rm = $_POST['new_no_rm'];


  // Update obat in the database using prepared statement
  $updateQuery = "UPDATE pasien SET nama=?, alamat=?, no_ktp=?, no_hp=?, no_rm=? WHERE id=?";
  $stmt = $mysqli->prepare($updateQuery);
  $stmt->bind_param("sssss", $newNama, $newAlamat, $newNo_ktp, $newNo_hp, $newNo_rm, $id);

  if ($stmt->execute()) {
    // Update successful
    header("Location: pasien.php");
    exit();
  } else {
    // Update failed, handle error (you may redirect or display an error message)
    echo "Update failed: " . $stmt->error;
  }

  $stmt->close();
  
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_obat'])) {
     $newNama = $_POST['add_nama'];
     $newAlamat = $_POST['add_alamat'];
     $newNo_ktp = $_POST['add_no_ktp'];
     $newNo_hp = $_POST['add_no_hp'];
     $newNo_ktp = $_POST['add_no_ktp'];
     $newNo_rm = $_POST['add_no_rm'];

  // Insert new obat into the database using prepared statement
  $insertQuery = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES (?, ?, ?, ?, ?)";
  $stmt = $mysqli->prepare($insertQuery);
  $stmt->bind_param("sssss", $newNama, $newAlamat, $newNo_ktp, $newNo_hp, $newNo_rm);

  if ($stmt->execute()) {
    // Insertion successful
    header("Location: pasien.php");
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

  // Lanjutkan dengan penghapusan pasien
  $deleteObatQuery = "DELETE FROM pasien WHERE id=?";
  $stmtObat = $mysqli->prepare($deleteObatQuery);
  $stmtObat->bind_param("i", $id);

  // Jalankan penghapusan obat
  if ($stmtObat->execute()) {
      // Penghapusan obat berhasil
      // Bersihkan output buffer
      ob_clean();

      // Redirect kembali ke halaman utama atau tampilkan pesan keberhasilan
      header("Location: pasien.php");
      exit();
  } else {
      // Penghapusan obat gagal, tangani kesalahan
      echo "Penghapusan obat gagal: " . $stmtObat->error;
  }

  // Tutup prepared statement
  $stmtObat->close();
}





// Fetch data from the 'obat' table
$obatQuery = "SELECT * FROM pasien";
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
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#addModal">Tambah pasien</button>

                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Nama pasien</th>
                      <th>Alamat</th>
                      <th>No ktp</th>
                      <th>No hp</th>
                      <th>No RM</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($obatData as $obatRow) {
                      echo "<tr>";
                      echo "<td>" . $obatRow['id'] . "</td>";
                      echo "<td>" . $obatRow['nama'] . "</td>";
                      echo "<td>" . $obatRow['alamat'] . "</td>";
                      echo "<td>" . $obatRow['no_ktp'] . "</td>";
                      echo "<td>" . $obatRow['no_hp'] . "</td>";
                      echo "<td>" . $obatRow['no_rm'] . "</td>";
                      
                      echo "<td>
                                                <form method='post' action=''>
                                                    <input type='hidden' name='id' value='" . $obatRow['id'] . "'>
                                                    <input type='hidden' name='new_nama' value='" . $obatRow['nama'] . "'>
                                                    <input type='hidden' name='new_alamat' value='" . $obatRow['alamat'] . "'>
                                                    <input type='hidden' name='new_no_ktp' value='" . $obatRow['no_ktp'] . "'>
                                                    <input type='hidden' name='new_no_hp' value='" . $obatRow['no_hp'] . "'>
                                                    <input type='hidden' name='new_no_rm' value='" . $obatRow['no_rm'] . "'>

                                                    <button type='button' name='update_obat' class='btn btn-warning btn-sm update-btn' data-toggle='modal' data-target='#updateModal' 
                                                    data-id='" . $obatRow['id'] . "' 
                                                    data-nama='" . $obatRow['nama'] . "' 
                                                    data-alamat='" . $obatRow['alamat'] . "' 
                                                    data-no_ktp='" . $obatRow['no_ktp'] . "'
                                                    data-no_ho='" . $obatRow['no_hp'] . "'
                                                    data-no_rm='" . $obatRow['no_rm'] . "'>Update</button>
                                                    
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
          <h5 class="modal-title" id="updateModalLabel">Perbarui pasien</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="pasien.php">
            <!-- Replace with the actual update PHP file -->
            <input type="hidden" name="id" id="update_id">
            <div class="form-group">
              <label for="update_nama">Nama pasien</label>
              <input type="text" class="form-control" id="update_nama" name="new_nama" required>
            </div>
            <div class="form-group">
              <label for="update_alamat">Alamat</label>
              <input type="text" class="form-control" id="update_alamat" name="new_alamat" required>
            </div>
            <div class="form-group">
              <label for="update_no_ktp">Nomor ktp</label>
              <input type="text" class="form-control" id="update_no_ktp" name="new_no_ktp" required>
            </div>
            <div class="form-group">
              <label for="update_no_hp">Nomor hp</label>
              <input type="text" class="form-control" id="update_no_hp" name="new_no_hp" required>
            </div>
            <div class="form-group">
              <label for="update_no_rm">Nomor rm</label>
              <input type="text" class="form-control" id="update_no_rm" name="new_no_rm" required>
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
        <h5 class="modal-title" id="addModalLabel">Tambah pasien</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="post" action="pasien.php">
          <!-- Replace with the actual add PHP file -->
          <div class="form-group">
            <label for="add_nama_obat">Nama pasien</label>
            <input type="text" class="form-control" id="add_nama" name="add_nama" required>
          </div>
          <div class="form-group">
            <label for="add_kemasan">Alamat</label>
            <input type="text" class="form-control" id="add_alamat" name="add_alamat" required>
          </div>
          <div class="form-group">
            <label for="add_no_ktp">Nomor ktp</label>
            <input type="text" class="form-control" id="add_no_ktp" name="add_no_ktp" required>
          </div>
          <div class="form-group">
            <label for="add_no_hp">Nomor hp</label>
            <input type="text" class="form-control" id="add_no_hp" name="add_no_hp" required>
          </div>
          <div class="form-group">
            <label for="add_no_rm">Nomor rm</label>
            <input type="text" class="form-control" id="add_no_rm" name="add_no_rm" required>
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
          var nama_obat = button.getAttribute('data-nama_pasien');
          var kemasan = button.getAttribute('data-alamat');
          var harga = button.getAttribute('data-no_ktp');
          var hp = button.getAttribute('data-no_hp');
          var rm = button.getAttribute('data-no_rm');

          document.getElementById('update_id').value = id;
          document.getElementById('update_nama').value = nama;
          document.getElementById('update_alamat').value = alamat;
          document.getElementById('update_no_ktp').value = no_ktp;
          document.getElementById('update_no_hp').value = no_hp;
          document.getElementById('update_no_rm').value = no_rm;
        });
      });
    });
  </script>
</body>

</html>