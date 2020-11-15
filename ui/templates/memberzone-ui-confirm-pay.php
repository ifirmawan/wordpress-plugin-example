<form method="post" action="<?php echo esc_url(home_url('wp-admin/admin-post.php')); ?>">
	<input name="action" type="hidden" value="memberzone_penawaran">
	<table>
		<tr>
			<td>No. Rekening Tujuan</td>
			<td>
				<select name="idrektoko">
				<?php echo (!is_null($option_isi))? $option_isi : '<option value="0">none</option>';?>
				</select>
			</td>
		</tr>
		<tr>
			<td><strong>Nama bank</strong></td>
			<td><input type="text" name="bank" value="<?php echo (cekosong($recent_nabank))? '' : $recent_nabank;?>" /></td>
		</tr>
		<tr>
			<td><strong>No. Rekening</strong></td>
			<td>
				<span style="float:left;" >
				<input type="number" name="norek" class="norek" value="<?php echo (cekosong($recent_norek))? '' : $recent_norek;?>"/>
				</span>
				<span style="float:left;" >
					<p style="color:#000;background:#F5D76E;padding:5px;">maksimal 18 karakter</p>
				</span>					
			</td>
		</tr>
		<tr>
			<td><strong>Atas nama Rekening</strong></td>
			<td><input type="text" name="an_rek" value="<?php echo (cekosong($recent_anbank))? '' : $recent_anbank;?>"/>
			</td>
		</tr>
		<tr>
			<td><strong>Nominal Pembayaran </strong></td>
			<td>
			<input type="number" name="nominal" class="maxval"  value="<?php echo $inv_sisa;?>" maxlength="<?php echo strlen($inv_sisa);?>"/>
			</td>
		</tr>
		<tr>
			<td><strong>Berita Acara </strong></td>
			<td>
				<textarea name="berita_acara"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<span id="lengthbill" style="display:none;"><?php echo ($lengthbill)? $lengthbill : '';?></span>
				<input type="hidden" value="<?php echo memberzone_enkrip($valquo['id'].'-'.$valquo['noquo']);?>" name="noquo" />
				<input class="button button-primary btn-confirm-pay" name="memberzone-penawaran-kirim" type="submit" value="konfirmasi" >
			</td>
			<td>
				<a href="<?php echo esc_url(admin_url('/admin.php?page=request-page'));?>" class="memberzone-button">batal</a>
			</td>
		</tr>
	</table>
	</form>