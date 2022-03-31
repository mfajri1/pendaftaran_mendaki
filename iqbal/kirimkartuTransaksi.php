<?php
	include "koneksi.php";

	//baca nomor kartu dari NodeMCU
	$nokartu = $_GET['nokartu'];
	$finger = $_GET['finger'];
	//kosongkan tabel tmprfid
	mysqli_query($konek, "delete from tmprfid");

	//simpan nomor kartu yang baru ke tabel tmprfid
	$simpan = mysqli_query($konek, "insert into tmprfid(nokartu, finger)values('$nokartu', '$finger)");

	$cariPengunjung = mysqli_query($konek, "SELECT * FROM tb_pengunjung WHERE nik_peng = '$nokartu' AND finger_peng = '$finger'");
	$jumlah_data = mysqli_num_rows($cariPengunjung);
	if($jumlah_data == 0){
		echo "kosong";
	}else{
		$queryTrans = mysqli_query($konek, "SELECT * FROM tb_transaksi WHERE nik = '$nokartu'");
		$jumlah_Trans = mysqli_num_rows($queryTrans);
		if($jumlah_Trans == 0){
			$tanggal_masuk = date('Y-m-d');
			$insertTrans = mysqli_query($konek, "INSERT INTO tb_transaksi(nik, tanggal_masuk, tanggal_keluar, mode_peng, status, ket) VALUES('$nokartu', '$tanggal_masuk', '', 'M', 'A', 'Sedang Dalam Pulau' )");
			echo "Masuk";
			
		}else{
			$tanggal_keluar = date('Y-m-d');
			$updPeng = mysqli_query($konek, "UPDATE tb_transaksi SET tanggal_keluar='$tanggal_keluar', mode_peng='K', status='N', ket='Sudah Keluar Pulau' WHERE nik='$nokartu'");
			echo "Keluar";
		}
		
		//kosongkan tabel tmprfid
		mysqli_query($konek, "delete from tmprfid");
	}

	
	
?>