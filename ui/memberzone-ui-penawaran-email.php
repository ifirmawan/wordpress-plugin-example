Halo,

Ada permintaan penawaran baru di <?php bloginfo('name'); ?>.
Produk yang dimaksud adalah <?php $post=get_post($idpost);echo $post->post_title; ?> sebanyak <?php echo $data['jml']; ?> unit.
<?php echo "\r\n";?>
Keterangan Opsional : <?php echo $data['opsional'];?>
<?php echo "\r\n";?>
Berikut informasi klien yang bersangkutan. 
<?php
foreach ($data as $key => $value) {
	if ($key!='produk' && $key!='jml' && $key!='opsional') {
		echo $key.' : '.$value."\r\n";
	}
}
?>

Silahkan ditindaklanjuti sampai transaksi berhasil.

Salam,
Java Multi Mandiri