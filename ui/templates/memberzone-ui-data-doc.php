<fieldset><legend><h3>Upload gambar untuk</h3></legend>
	<form action="<?php echo esc_url(admin_url('/admin-post.php'));?>" method="post" enctype="multipart/form-data">
	<input name="memberzone-penawaran-kirim" type="hidden" value="upload_img">
	<input name="action" type="hidden" value="memberzone_penawaran">
	
		<table>
			<?php 
			$kol=get_list_fields('gambar');
			
			$getallimg=get_data_table('gambar',$kol,false,false);
			if ($getallimg) {
				foreach($getallimg as $valimg) { ?>					
					<tr>
						<td><strong><?php echo $valimg['type'];?></strong></td>
						<td>
							<?php 
							if (cekosong($valimg['url'])) { ?>
								<input type="file" name="fileimg-<?php echo $valimg['id'];?>" class="regular-text" />(format:<strong>jpeg</strong>)
							<?php }else{ 
								add_thickbox();
								$toshow='image';
								ob_start();				
								include MEMBERZONE_UI.'templates/memberzone-ui-thickbox.php';
								$img=ob_get_clean();
								echo $img;
							}?>
						</td>
						<td>
						<button type="submit" name="test_form">Upload</button>
						<a href="#" id="<?php echo $valimg['id'];?>" class="reset-img">reset</a>
						</td>
					</tr>
				<?php }
			}?>
		</table>
	</form>
	</fieldset>
	<hr>
	tekan tombol <strong>ENTER</strong> untuk menyimpan perubahan pada form <strong>informasi bank toko</strong>
	<hr>
	<fieldset><legend><h3>Informasi bank toko</h3></legend>
	<form id="addbank" method="post" action="<?php echo esc_url(admin_url('/admin-post.php'));?>">
	<input name="id" type="hidden" value="x" class="idbank">
	<input name="action" type="hidden" value="memberzone_penawaran">
	<input name="memberzone-penawaran-kirim" type="hidden" value="addbank">
	<table class="wp-list-table widefat fixed striped posts">
		<tr>
			<td><input type="text" name="bank_nama" class="regular-text input nb" placeholder="Nama Bank" /></td>
			<td><input type="text" name="bank_an" class="regular-text input anb" placeholder="Atas Nama Bank" /></td>
			<td><input type="number" name="bank_norek" class="regular-text input norek" placeholder="No. Rekening Bank MAX 18 Karakter" id="txtboxToFilter"/></td>
			<td>Aksi</td>
		</tr>
		<?php
		$kol		=get_list_fields('bank_toko');
		$banktoko	=get_data_table('bank_toko',$kol,false,false);
		if ($banktoko) {
			foreach ($banktoko as $kbank => $valbank) { ?>
			<tr id="trow-<?php echo $valbank['id'];?>">
				<td><strong id="nb-<?php echo $valbank['id'];?>"><?php echo $valbank['bank_nama'];?></strong></td>
				<td><strong id="anb-<?php echo $valbank['id'];?>"><?php echo $valbank['bank_an'];?></strong></td>
				<td><strong id="norek-<?php echo $valbank['id'];?>"><?php echo $valbank['bank_norek'];?></strong></td>
				<td>
					<a href="#" class="bank-edit" id="<?php echo $valbank['id'];?>"><span class="dashicons dashicons-edit"></span></a>
					<a href="#" class="bank-del" id="<?php echo $valbank['id'];?>"><span class="dashicons dashicons-trash"></span></a>
				</td>
			</tr>
			<?php 
			}
		}
		?>
	</table >
	<span id="fsubmit" style="display:none;">addbank</span>
	<input type="submit" style="display:none;"/>
	</form>
	</fieldset>
	<hr>

</div>		
