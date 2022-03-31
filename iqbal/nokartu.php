<?php
	include "koneksi.php";
	//baca isi tabel tmprfid
	$sql = mysqli_query($konek, "select * from tmprfid");
	$data = mysqli_fetch_array($sql);
	//baca nokartu
	$nokartu = $data['nokartu'];
	$finger = $data['finger'];
?>

<div class="form-group">
	<label>No.Kartu</label>
	<input type="text" name="nokartu" id="nokartu" placeholder="tempelkan kartu rfid Anda" class="form-control" style="width: 200px" value="<?php echo $nokartu; ?>">
	<label>Finger</label>
	<input type="text" name="finger" id="finger" placeholder="Letakkan Jari Anda" class="form-control" style="width: 200px" value="<?php echo $finger; ?>">
</div>