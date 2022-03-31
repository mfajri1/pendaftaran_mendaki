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
	<title>Data Pengunjung</title>
</head>
<body>

	<?php include "menu.php"; ?>

	<!--isi -->
	<div class="container-fluid">
		<h3>Data Pengunjung</h3>
		<table class="table table-bordered">
			<thead>
				<tr style="background-color: grey; color: white;">
					<th style="width: 10px; text-align: center">No.</th>
					<th style="width: 200px; text-align: center">Nik Pengunjung</th>
					<th style="width: 200px; text-align: center">Kode Finger</th>
					<th style="width: 200px; text-align: center">Nama</th>
					<th style="width: 200px; text-align: center">Alamat</th>
					<th style="width: 200px; text-align: center">Status</th>
					<th style="width: 100px; text-align: center">Aksi</th>
				</tr>
			</thead>
			<tbody>

				<?php
					//koneksi ke database
					include "koneksi.php";

					//baca data karyawan
					$sql = mysqli_query($konek, "select * from tb_pengunjung");
					$no = 0;
					while($data = mysqli_fetch_array($sql))
					{
						$no++;
				?>

				<tr>
					<td> <?php echo $no; ?> </td>
					<td> <?php echo $data['nik_peng']; ?> </td>
					<td> <?php echo $data['finger_peng']; ?> </td>
					<td> <?php echo $data['nama_peng']; ?> </td>
					<td> <?php echo $data['alamat']; ?> </td>
					<td> <?php echo $data['status_card']; ?> </td>
					<td>
						<a href="edit.php?id=<?php echo $data['id_peng']; ?>"> Edit</a> | <a href="hapus.php?id=<?php echo $data['id_peng']; ?>"> Hapus</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

		<!-- tombol tambah data karyawan -->
		<!--<a href="tambah.php"> <button class="btn btn-primary">Tambah Data Pengunjung</button></a>--> 
	</div>

</body>
</html>