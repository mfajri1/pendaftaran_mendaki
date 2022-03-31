<!-- proses penyimpanan -->

<?php 
	include "koneksi.php";

	//jika tombol simpan diklik
	if(isset($_POST['btnSimpan']))
	{
		//baca isi inputan form
		$nokartu = $_POST['nokartu'];
		$finger  = $_POST['finger'];
		$nama    = $_POST['nama'];
		$alamat  = $_POST['alamat'];
		$statusCard = "A";

		//simpan ke tabel karyawan
		$simpan = mysqli_query($konek, "insert into tb_pengunjung(nik_peng, finger_peng, nama_peng, alamat, status_card)values('$nokartu', '$finger', '$nama', '$alamat', '$statusCard')");

		//jika berhasil tersimpan, tampilkan pesan Tersimpan,
		//kembali ke data karyawan
		if($simpan)
		{
			echo "
				<script>
					alert('Tersimpan');
					location.replace('datakaryawan.php');
				</script>
			";
			$mode = 1;
			$id = 1;

			$updateMode = mysqli_query($konek, "UPDATE tb_mode SET mode = '$mode' WHERE id_mode = '$id' ");
		}
		else
		{
			echo "
				<script>
					alert('Gagal Tersimpan');
					location.replace('datakaryawan.php');
				</script>
			";
			$mode = 1;
			$id = 1;

			$updateMode = mysqli_query($konek, "UPDATE tb_mode SET mode = '$mode' WHERE id_mode = '$id' ");
		}

	}

	//kosongkan tabel tmprfid
	mysqli_query($konek, "delete from tmprfid");
?>

<!DOCTYPE html>
<html>
<head>
	<?php include "header.php"; ?>
	<title>Tambah Data Pengunjung</title>

	<!-- pembacaan no kartu otomatis -->
	<script type="text/javascript">
		$(document).ready(function(){
			setInterval(function(){
				$("#norfid").load('nokartu.php')
			}, 0);  //pembacaan file nokartu.php, tiap 1 detik = 1000
		});
	</script>

</head>
<body>

	<?php include "menu.php"; ?>

	<!-- isi -->
	<div class="container-fluid">
		<h3>Tambah Data Pengunjung</h3>

		<!-- form input -->
		<form method="POST">
			<div id="norfid"></div>

			<div class="form-group">
				<label>Nama Pengunjung</label>
				<input type="text" name="nama" id="nama" placeholder="nama Pengunjung" class="form-control" style="width: 400px">
			</div>
			<div class="form-group">
				<label>Alamat</label>
				<textarea class="form-control" name="alamat" id="alamat" placeholder="alamat" style="width: 400px"></textarea>
			</div>

			<button class="btn btn-primary" name="btnSimpan" id="btnSimpan">Simpan</button>
		</form>
	</div>

</body>
</html>