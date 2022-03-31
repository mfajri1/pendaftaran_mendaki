<?php 
	include"koneksi.php";

	$cekMode = mysqli_query($konek, "SELECT * FROM tb_mode WHERE id_mode = 1");
	$result_mode = mysqli_fetch_array($cekMode);

	echo $result_mode['mode'];


 ?>