<?php if (is_user_logged_in()): ?>
	<a class="memberzone-button memberzone-button-attention memberzone-width-100" href="#memberzone-modal" data-uk-modal>Minta Harga</a>

	<div class="memberzone-modal" id="memberzone-modal" >
		<div class="memberzone-modal-dialog">
			<form method="post" action="<?php echo esc_url(home_url('wp-admin/admin-post.php')); ?>">
			<p>
					<label>Produk</label>
					<input class="memberzone-form-blank memberzone-width-100" name="memberzone-penawaran-produk" type="text" value="<?php the_title(); ?>" readonly>
				</p>
				<p>
					<label>Jumlah</label >
					<input class="memberzone-width-100" name="memberzone-penawaran-jumlah" type="text">
				</p>
				<input name="memberzone-penawaran-nama" type="hidden" value="<?php// echo $member_extra['NAMALENGKAP']; ?>">
				<input name="memberzone-penawaran-email" type="hidden" value="<?php //echo $member_basic->user_email; ?>">
				<textarea name="memberzone-penawaran-alamat" style="display:none;"><?php //echo $member_extra['ALAMAT']; ?></textarea>
				<input name="memberzone-penawaran-telepon" type="hidden" value="<?php //echo $member_extra['TELEPON']; ?>">
				<input name="memberzone-penawaran-handphone" type="hidden" value="<?php //echo $member_extra['HANDPHONE']; ?>">
				<input name="memberzone-penawaran-post-id" type="hidden" value="<?php //echo $post_id; ?>">
				<input name="action" type="hidden" value="memberzone_penawaran">
				
				<input class="memberzone-button memberzone-button-primary memberzone-width-100" name="memberzone-penawaran-kirim" type="submit" value="Kirim">
			</form>
		</div>
	</div>
<?php else: ?>
	<div id="#page-masuk" ></div>
	<div class="memberzone-button-group">
		<button type="button" class="btn-login memberzone-button memberzone-button-primary memberzone-width-100" >Login</button>
		<button type="button" class="btn-reg memberzone-button memberzone-button-success memberzone-width-100" >Daftar</button>
	</div>
	<script type="text/javascript" > 
	$(document).ready(function(){
		$(document).on('click','.btn-login',function(){
			$('#page-masuk').text("hello world");
		});
	});
	</script>
<?php endif; ?>