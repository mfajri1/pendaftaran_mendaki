<?php
	include "koneksi.php";

	//baca nomor kartu dari NodeMCU
	$nokartu = $_GET['nokartu'];
	$finger = $_GET['finger'];
	//kosongkan tabel tmprfid
	mysqli_query($konek, "delete from tmprfid");

	//simpan nomor kartu yang baru ke tabel tmprfid
	$simpan = mysqli_query($konek, "insert into tmprfid(nokartu, finger)values('$nokartu', '$finger')");
	if($simpan)
		echo "Berhasil";
	else
		echo "Gagal";
?>