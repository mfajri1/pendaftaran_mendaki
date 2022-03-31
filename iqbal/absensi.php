<?php
include "koneksi.php";
$mode = 3;
$id = 1;

$updateMode = mysqli_query($konek, "UPDATE tb_mode SET mode = '$mode' WHERE id_mode = '$id' ");


 ?>
<!DOCTYPE html>
<html>
<head>
	<?php include "header.php"; ?>
	<title>Transaksi Pengunjung</title>
</head>
<body>

	<?php include "menu.php"; ?>

	<!-- isi -->
	<div class="container-fluid">
		<h3>Transaksi Pengunjung</h3>
		<table class="table table-bordered">
			<thead>
				<tr style="background-color: grey; color:white">
					<th style="width: 10px; text-align: center">No.</th>
					<th style="text-align: center">Nik</th>
					<th style="text-align: center">Tanggal Masuk</th>
					<th style="text-align: center">Tanggal Keluar</th>
					<th style="text-align: center">Mode Pengunjung</th>
					<th style="text-align: center">Status</th>
					<th style="text-align: center">Keterangan</th>
					<th style="text-align: center">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
					include "koneksi.php";

					//baca tabel absensi dan relasikan dengan tabel karyawan berdasarkan nomor kartu RFID untuk tanggal hari ini

					//baca tanggal saat ini
					$tanggal = date('Y-m-d');

					//filter absensi berdasarkan tanggal saat ini
					$sql = mysqli_query($konek, "SELECT * FROM tb_transaksi");

					$no = 0;
					while($data = mysqli_fetch_array($sql))
					{
						$no++;
				?>
				<tr>
					<td> <?php echo $no; ?> </td>
					<td> <?php echo $data['nik']; ?> </td>
					<td> <?php echo $data['tanggal_masuk']; ?> </td>
					<td> <?php echo $data['tanggal_keluar']; ?> </td>
					<td> <?php echo $data['mode_peng']; ?> </td>
					<td> <?php echo $data['status']; ?> </td>
					<td> <?php echo $data['ket']; ?> </td>
					<td>
						<a href="edit.php?id=<?php echo $data['id_trasaksi']; ?>"> Edit</a> | <a href="hapus.php?id=<?php echo $data['id_trasaksi']; ?>"> Hapus</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

</body>
</html>