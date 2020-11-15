Halo,

Ada permintaan penawaran baru di <?php bloginfo('name'); ?>.
Produk yang dimaksud adalah <?php echo $postingan->post_title; ?> sebanyak <?php echo $list_quo['memberzone-penawaran-jumlah']; ?> unit.
<?php echo "\r\n";?>
Keterangan Opsional : <?php echo $list_quo['memberzone-penawaran-opsional'];?>
<?php 
$customer=get_user_by('id',$list_quo['idcus']);
echo "\r\n";
?>
Dari Pelanggan dengan Username : <?php echo $customer->user_login.' dan Email : '.$customer->user_email; ?>  


Silahkan ditindaklanjuti sampai transaksi berhasil.

Salam,
Java Multi Mandiri