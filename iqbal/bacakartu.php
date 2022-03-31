<?php 
	include "koneksi.php";
	
	//baca tabel tmprfid
	$baca_data = mysqli_query($konek, "SELECT * FROM tmprfid");
	$data_simpan = mysqli_fetch_array($baca_data);
	$nokartu    = $data_simpan['nokartu'];
	$finger		= $data_simpan['finger'];
?>


<div class="container-fluid" style="text-align: center;">
	<?php if($nokartu=="" && $finger == "") { ?>
		<h3>Silahkan Tempelkan Kartu RFID</h3>
		<h3>Dan Finger Anda</h3>
		<img src="images/rfid.png" style="width: 200px"> <br>
		<img src="images/animasi2.gif">
	<?php } else {
		//cek nomor kartu RFID tersebut apakah terdaftar di tabel karyawan
		$cariPengunjung = mysqli_query($konek, "SELECT * FROM tb_pengunjung WHERE nik_peng = '$nokartu' AND finger_peng='$finger'");
		$jumlah_data = mysqli_num_rows($cariPengunjung);
		if($jumlah_data == 0){?>
			<h4 class="text-danger font-weight-bold">Data Anda Tidak terdeteksi!! Silahkan Daftar Dulu</h4>
		<?php}else{
			$queryPengunjung = mysqli_query($konek, "SELECT * FROM tb_transaksi WHERE nik= '$nokartu'");
			$jumlah_pengunjung = mysqli_num_rows($queryPengunjung);
			if($jumlah_pengunjung == 0){
				$tanggal_masuk = date('Y-m-d');
				$insertTrans = mysqli_query($konek, "INSERT INTO tb_transaksi(nik, tanggal_masuk, tanggal_keluar, mode_peng, status, ket) VALUES('$nokartu', '$tanggal_masuk', '', 'M', 'A', 'Sedang Dalam Pulau' )");
				?>
				<h4 class="text-danger font-weight-bold">Data Anda terdeteksi!! Silahkan Masuk</h4><?php
			}else{
				$tanggal_keluar = date('Y-m-d');
				$updPeng = mysqli_query($konek, "UPDATE tb_transaksi SET tanggal_keluar='$tanggal_keluar', mode_peng='K', status='N', ket='Sudah Keluar Pulau' WHERE nik='$nokartu'");
				?>
				<h4 class="text-danger font-weight-bold">Data Anda terdeteksi!! Silahkan Keluar</h4><?php
			}
			mysqli_query($konek, "delete from tmprfid");
		} ?>

</div>