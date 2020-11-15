<a href="#TB_inline?width=600&height=250&inlineId=pay-content-<?php echo $quo['id'];?>" class="thickbox button button-primary">Periksa Pembayaran</a>
<div id="pay-content-<?php echo $quo['id'];?>" style="display:none;">
	<div class="pay-info" >
	<ul>
		<li><span id="memberzone-label">Nama Bank 					</span> <strong><?php echo getpay($noquopay,'bank');?></strong></li>
		<li><span id="memberzone-label">No. Rekening 				</span> <strong><?php echo getpay($noquopay,'norek');?></strong></li>
		<li><span id="memberzone-label">Atas Nama Rekening			</span><strong><?php echo getpay($noquopay,'an_rek');?></strong></li>
		<li><span id="memberzone-label">Nominal Pembayaran Transfer </span><strong>Rp. <h3><?php echo getpay($noquopay,'nominal');?></h3></strong></li>
		<li><span id="memberzone-label">Berita Acara				</span><strong><?php echo getpay($noquopay,'berita_acara');?></strong></li>
		<li><span id="memberzone-label">Status Tagihan				</span><strong><?php echo getpay($noquopay,'status');?></strong></li>
		<li><span id="memberzone-label">Waktu Transfer				</span><strong><?php echo getpay($noquopay,'time');?></strong></li>
	</ul>
	<span id="imgurl" style="display:none;"> <?php echo MEMBERZONE_AST.'images/ajax-loader.gif';?> </span>
	<a href="#" class="memberzone-button btn-terima" id="<?php echo $quo['id'];?>">Di Terima</a>
</div>
</div>