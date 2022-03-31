<?php
include "koneksi.php";
$mode = 1;
$id = 1;

$updateMode = mysqli_query($konek, "UPDATE tb_mode SET mode = '$mode' WHERE id_mode = '$id' ");


 ?>
<!DOCTYPE html>
<html>
<head>
	<?php include "header.php"; ?>
	<title>Menu Utama</title>
</head>
<body>
	<?php include "menu.php"; ?>

	<!-- isi -->
	<div class="container-fluid" style="padding-top: 10%; text-align: center">
		<h1>
			Selamat Datang <br>
			Pengunjung Gunung Merapi
		</h1>
	</div>

	
</body>
</html>