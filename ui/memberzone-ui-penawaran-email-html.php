<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php $post=get_post($data['produk']);?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <style type="text/css">
  @media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
  </style>
</head>
<body style="margin: 0; padding: 0;">
 <table  cellpadding="0" cellspacing="0" style="width: 100% min-width: 200px;" class="chunk">

  <tr>
   <td>
    <table align="center"  cellpadding="0" width="600" style="border-collapse:collapse;">
    	<tr>
    		<td style="font-size:0;line-height:0;" height="10" >&nbsp;</td>
    	</tr>
    	<tr>
    		<td bgcolor="#70bbd9" align="center" style="padding:40px 0 30px 0;">
    		<h1>Halo, </h1>
    		</td>
    	</tr>
		<tr>
    		<td bgcolor="#ffffff" style="padding:40px 30px 40px 30px;"
    		>
    		<table  cellspacing="0" cellpadding="0" width="100%">
    			<tr>
    				<td><strong>Permintaan penawaran ID =<?php echo $data['produk'];?> berjudul =<?php echo $post->post_title;?></strong></td>
    			</tr>
    			<tr>
    				<td style="padding:20px 0 30px 0;">
    				Ada permintaan penawaran baru di <?php bloginfo('name'); ?>.
					Produk yang dimaksud adalah <?php echo $post->post_title; ?> sebanyak <?php echo $data['jumlah']; ?> unit.
					<br/>
					Keterangan Opsional : <?php echo $data['opsional'];?>
					<br/>
					Berikut informasi klien yang bersangkutan.<br/>
					<?php
					foreach ($data as $key => $value) {
						if ($key!='produk' && $key!='jumlah' && $key!='opsional') {
							echo $key.' : '.$value.'<br/>';
						}
						} ?>
    				</td>
    			</tr>
    			<tr>
    				<td> Silahkan ditindaklanjuti sampai transaksi berhasil. <br/>Salam,<br/>Java Multi Mandiri</td>
    			</tr>
    		</table>
    		</td>
    	</tr>
    	<tr>
    		<td bgcolor="#f16122" style="padding:30px 30px 30px 30px">
    		<table  cellpadding="0" cellspacing="0" width="100%">
    		<tr>
    			<td>
    			<img src="jvm.jpg" alt="jvm.co.id" width="45%"/>
  				</td>
  				<td>
  				Our email : info@jvm.co.id<br/>

  				</td>
 			</tr>
			</table>

    		</td>
    	</tr>
    </table>
   </td>
  </tr>
 </table>
</body>
</html>