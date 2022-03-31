<?php 
	include"koneksi.php";

	mysqli_query($konek, "UPDATE tb_mode SET mode = '1' WHERE id_mode = '1' ");

	if(isset($_POST['btnDaftar'])){
		//simpan ke tabel karyawan
		$mode = 2;
		$id = 1;

		$simpan = mysqli_query($konek, "UPDATE tb_mode SET mode = '$mode' WHERE id_mode = '$id' ");
		header("Location:tambah.php");
	}

	if(isset($_POST['btnTransaksi'])){
		//simpan ke tabel karyawan
		$mode = 3;
		$id = 1;

		$simpan = mysqli_query($konek, "UPDATE tb_mode SET mode = '$mode' WHERE id_mode = '$id' ");
		header("Location:datakaryawan.php");
	}
	
	if(isset($_POST['btnHapus'])){
		//simpan ke tabel karyawan
		$mode = 4;
		$id = 1;

		$simpan = mysqli_query($konek, "UPDATE tb_mode SET mode = '$mode' WHERE id_mode = '$id' ");
		header("Location:del.php");
	}

	if(isset($_POST['btnReset'])){
		//simpan ke tabel karyawan
		$mode = 1;
		$id = 1;

		$simpan = mysqli_query($konek, "UPDATE tb_mode SET mode = '$mode' WHERE id_mode = '$id' ");

		//jika berhasil tersimpan, tampilkan pesan Tersimpan,
		//kembali ke data karyawan
		if($simpan)
		{
			echo "
				<script>
					alert('Tersimpan');
					location.replace('mode.php');
				</script>
			";
		}
		else
		{
			echo "
				<script>
					alert('Gagal Tersimpan');
					location.replace('mode.php');
				</script>
			";
		}
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php include "header.php"; ?>
	<title>Mode Menu</title>
</head>
<body>
	<?php include "menu.php"; ?>
	<div class="container">
		<h3 class="text-center">Pilih Menu Transaksi</h3>
		<br><br><br>
		<div class="menu text-center">
			<div class="row">
				<div class="col-12">
					<form action="" method="POST">
						<button name="btnDaftar" id="btnDaftar" class="btn btn-large btn-success" style="width:140px">Daftar</button>
					</form>
				</div>
				<br><br>
				<div class="col-12" >
					<form action="" method="POST">
						<button name="btnTransaksi" id="btnTransaksi" class="btn btn-large btn-primary" style="width:140px">Transaksi</button>
					</form>
				</div>
				<br><br>
				<div class="col-12" >
					<form action="" method="POST">
						<button name="btnHapus" id="btnHapus" class="btn btn-large btn-primary" style="width:140px">Hapus</button>
					</form>
				</div>
				<br><br>
				<div class="col-12" >
					<form action="" method="POST">
						<button name="btnReset" id="btnReset" class="btn btn-large btn-danger" style="width:140px">Reset Mode</button>
					</form>
				</div>
			</div>
		</div>		
	</div>
</body>
</html>