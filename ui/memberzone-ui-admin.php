<div class="wrap">
	<h2>Administrasi <?php echo ucwords($this->plugin_name); ?></h2>
	<form method="post" action="options.php">
		<?php 
		settings_fields($this->plugin_name . '_options'); 
		$options = get_option($this->plugin_name . '_marketer'); 
		?>
		<table class="wp-list-table widefat fixed striped posts">
			<tbody>
				<tr>
					<td scope="row" >Email marketer</td>
					<td >
						<input type="email" class="regular-text" name="<?php echo $this->plugin_name; ?>_marketer[email]" value="<?php echo sanitize_email($options['email']); ?>">
					</td>
				</tr>
				<tr>
					<td scope="row" >No. Handphone marketer</td>
					<td >
						<input type="number" class="regular-text"  name="<?php echo $this->plugin_name; ?>_marketer[person_hp]"  value="<?php echo $options['person_hp']; ?>">
						
					</td>
				</tr>
				<tr>
					<td scope="row">No. quo terakhir</td>
					<td>
						<input type="number" class="regular-text"  name="<?php echo $this->plugin_name; ?>_marketer[noquo]"  value="<?php echo $options['noquo']; ?>">
					</td>
				</tr>
				<tr>
					<td scope="row">Nama Toko</td>
					<td>
						<input type="text" class="regular-text" name="<?php echo $this->plugin_name; ?>_marketer[toko_nama]" value="<?php echo $options['toko_nama']; ?>">
					</td>
				</tr>
				<tr>
					<td scope="row">No.Telp Toko</td>
					<td>
						<input type="number" class="regular-text"  name="<?php echo $this->plugin_name; ?>_marketer[toko_notelp]"  value="<?php echo $options['toko_notelp']; ?>">
					</td>
				</tr>
				<tr>
					<td scope="row">No. Fax Toko</td>
					<td>
						<input type="number" class="regular-text"  name="<?php echo $this->plugin_name; ?>_marketer[toko_fax]" value="<?php echo $options['toko_fax']; ?>">
					</td>
				</tr>
				<tr>
					<td scope="row">Email Toko</td>
					<td>
						<input type="email" class="regular-text" name="<?php echo $this->plugin_name; ?>_marketer[toko_email]" value="<?php echo $options['toko_email']; ?>">
					</td>
				</tr>
				<tr>
					<td scope="row">Alamat Toko</td>
					<td>
						<textarea class="regular-text memberzone-width-100" name="<?php echo $this->plugin_name; ?>_marketer[toko_alamat]"><?php echo $options['toko_alamat']; ?></textarea>
					</td>
				</tr>
				<tr>
					<td scope="row">Maximal Kadaluarsa</td>
					<td>
						<input type="number" class="regular-text"  name="<?php echo $this->plugin_name; ?>_marketer[exp_time]" value="<?php echo $options['exp_time']; ?>">
					</td>
				</tr>
			</tbody>
		</table>
		<?php submit_button(); ?>
	</form>
<hr/>
<h3> Daftar Kontak Marketer untuk Lampiran Quotation </h3>
<table class="wp-list-table widefat fixed striped posts">
<thead>
	<tr>
		<?php echo $contacts_thead;?>
	</tr>
</thead>
<tbody>
	<?php echo $contacts_tbody;?>
</tbody>
</table>
