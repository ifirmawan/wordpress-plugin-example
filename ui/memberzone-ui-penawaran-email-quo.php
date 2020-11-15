<<!DOCTYPE html>
<html>
<head>
	<title>PDF Quotation-<?php echo($id);?></title>
</head>
<body>
<p>
Berikut kami lampirkan dokumen penawaran <strong><?php echo $data['produk'];?></strong> yang anda minta pada tanggal <i><?php echo $data['tgl'];?></i><br/>
Jika anda menyetujui untuk tahap pembelian silahkan klik link dibawah ini<br/>
<a href="<?php echo esc_url(home_url('?ac='.md5($id)));?>"><h2>Saya menyetujui penawaran ini</h2></a>
<?php
	$duedate=date_create(date('Y-m-d'));
	date_add($duedate,date_interval_create_from_date_string('3 days'));
?>
<hr>
Batas maximal berlakunya penawaran ini sampai dengan : <strong><?php echo date_format($duedate,'Y-m-d');?> </strong> dimulai dari lampiran penawaran diterbitkan kepada pelanggan yang terkait
</p>
<br/>
Terima kasih
Salam,
Java Multi Mandiri
</body>
</html>