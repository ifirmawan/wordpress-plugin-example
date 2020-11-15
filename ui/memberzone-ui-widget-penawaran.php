<?php if (is_user_logged_in()): ?>
	<?php if (is_single()): ?>
		<form method="post" action="<?php echo esc_url(home_url('wp-admin/admin-post.php')); ?>">
			<p>
				<label>Produk</label><br/><br/>
				<label><?php the_title(); ?></label>
			</p>
			<p>
				<label>Jumlah</label>
				<input class="memberzone-width-100" name="memberzone-penawaran-jumlah" type="text">
			</p>
			<?php if($get_opsi):
					foreach($get_opsi as $valopsi) { ?>
					<p>
						<label><?php echo $valopsi['meta_label'];?> :</label>
						<?php 
							$get_opsional = get_data_byid('custom_meta_opsional','meta_key',$valopsi['meta_key']);
							switch ($valopsi['meta_type']) {
								case 'select':
								 ?>
									<select name="<?php echo $valopsi['meta_key'];?>">
										<?php foreach($get_opsional as $pilihan):?>
											<option value="<?php echo $pilihan['id'];?>" <?php echo ($pilihan['id']==get_post_meta($uri2,$valopsi['meta_key'], true))? 'selected' : '';?>><?php echo $pilihan['opsional'];?></option>
										<?php endforeach;?>
									</select>
									<?php break;
								case 'checkbox':
								
								foreach($get_opsional as $pilihan):?>
								<input type="radio" name="<?php echo $valopsi['meta_key'];?>" value="<?php echo $pilihan['id'];?>"/> <?php echo $pilihan['opsional'];;?>
								<?php endforeach;
								 break;
								case 'radio':
								
								foreach($get_opsional as $pilihan):?>
								<input type="<?php echo $valopsi['meta_type'];?>" name="<?php echo $valopsi['meta_key'];?>" value="<?php echo $pilihan['id'];?>"/> <?php echo $pilihan['opsional'];;?>
								<?php endforeach;
								 break;
							}
						?>
					</p>	
				<?php }
				endif;
				?>
			<p>
				<label>keterangan Opsional</label>
				<textarea class="form-control" rows="3" placeholder="keterangan Opsional" name="memberzone-penawaran-opsional"></textarea>
			</p>
			<input name="memberzone-penawaran-produk" type="hidden" value="<?php echo $post_id; ?>">
			<input name="action" type="hidden" value="memberzone_penawaran">
			<input class="memberzone-button memberzone-button-primary memberzone-width-100" name="memberzone-penawaran-kirim" type="submit" value="Kirim">
		</form>
	<?php else: ?>
		<p>Silahkan pilih produk terlebih dahulu.</p>
	<?php endif; ?>
<?php else: ?>
	<p>Silahkan login atau mendaftar sebagai member terlebih dahulu.</p>
	<p><a class="memberzone-button memberzone-button-primary memberzone-width-100" href="<?php echo esc_url(home_url('/wp-login.php?p='.$post_id)); ?>">Login</a></p>
	<p><a class="memberzone-button memberzone-button-success memberzone-width-100" href="<?php echo esc_url(home_url('/wp-login.php?action=register')); ?>">Daftar</a></p>
<?php endif; ?>
