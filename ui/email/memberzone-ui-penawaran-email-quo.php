Berikut kami lampirkan dokumen penawaran <strong><?php echo $data['produk'];?></strong> yang anda minta pada tanggal <i><?php echo $data['tgl'];?></i><br/>
Jika anda menyetujui untuk tahap pembelian silahkan klik link dibawah ini<br/>
<a href="<?php echo esc_url(home_url('?ac='.md5($id)));?>"><h2>Saya menyetujui penawaran ini</h2></a>
<hr>
Batas maximal berlakunya penawaran ini sampai dengan : <strong><?php echo date_format($duedate,'Y-m-d');?> </strong> dimulai dari lampiran penawaran diterbitkan kepada pelanggan yang terkait
</p>
<br/>
Terima kasih
Salam,
Java Multi Mandiri

