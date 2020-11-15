<<!DOCTYPE html>
<html>
<head>
	<title>Pdf Invoice-<?php echo $id;?></title>
</head>
<body>
<p>
	Berikut kami lampirkan dokumen Penagihan <strong><?php echo($data['produk']);?></strong><br/>
	anda bisa melakukan konfirmasi pembayaran pada area member.
	</strong>
	<hr/>
	Batas maksimal konfirmasi pembayaran jatuh pada <strong><?php echo $data['exp_time'];?></strong> atau 3 hari setelah diterbitkannya surat penagihan ini.<br/>
</p>
<br/>
Terima kasih
Salam,
Java Multi Mandiri
</body>
</html>
