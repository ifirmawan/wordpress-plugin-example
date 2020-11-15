
<table>
	<tr>
		<td colspan="2"><h3>Kontak personal</h3></td>
	</tr>
	<tr>
		<td><label for="nohp">No. Handphone</label></td>
		<td><input type="number" name="nohp" value="<?php echo esc_attr(get_the_author_meta( 'nohp', $user->ID )); ?>" class="regular-text"/></td>
	</tr>
	<tr>
		<td><label for="fax">No. Fax</label></td>
		<td><input type="number" name="fax" value="<?php echo esc_attr(get_the_author_meta( 'fax', $user->ID )); ?>" class="regular-text" /></td>
	</tr>
	<tr>
	<td colspan="2"><h3>Data Alamat Email</h3></td>
	</tr>
	<tr>
		<td><label for="attn">Attendance</label></td>
		<td><input type="text" name="attn" value="<?php echo esc_attr(get_the_author_meta( 'attn', $user->ID )); ?>" class="regular-text" /></td>
	</tr>
	<tr>
		<td><label for="cc">Cc</label></td>
		<td><input type="text" name="cc" value="<?php echo esc_attr(get_the_author_meta( 'cc', $user->ID )); ?>" class="regular-text" /></td>
	</tr>
	<tr>
		<td colspan="2"><h3>Data Alamat Pribadi</h3></td>
	</tr>
	<tr>
		<td><label for="ancus">Atas nama</label></td>
		<td><input type="text" name="ancus" value="<?php echo esc_attr(get_the_author_meta( 'ancus', $user->ID )); ?>" /></td>
	</tr>
	<tr>
		<td><label for="telpacus">No.Telp</label></td>
		<td><input type="number" name="telpacus" value="<?php echo esc_attr(get_the_author_meta( 'telpacus', $user->ID )); ?>" id="txtboxToFilter"/></td>
	</tr>
	<tr>
		<td><label for="acus">Alamat</label></td>
		<td><textarea name="acus"  class="regular-text"><?php echo esc_attr(get_the_author_meta( 'acus', $user->ID )); ?></textarea></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td colspan="2"><h3>Data Alamat Penagihan</h3></td>
	</tr>
	<tr>
		<td><label for="anbil">Atas nama</label></td>
		<td><input type="text" name="anbil" value="<?php echo esc_attr(get_the_author_meta( 'anbil', $user->ID )); ?>" /></td>
	</tr>
	<tr>
		<td><label for="telpabil">No.Telp</label></td>
		<td><input type="number" name="telpabil" value="<?php echo esc_attr(get_the_author_meta( 'telpabil', $user->ID )); ?>" id="txtboxToFilter"/></td>
	</tr>
	<tr>
		<td><label for="abil">Alamat</label></td>
		<td><textarea name="abil"  class="regular-text"><?php echo esc_attr(get_the_author_meta( 'abil', $user->ID )); ?></textarea></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td colspan="2"><h3>Data Alamat Pengiriman</h3></td>
	</tr>
	<tr>
		<td><label for="anship">Atas nama</label></td>
		<td><input type="text" name="anship" value="<?php echo esc_attr(get_the_author_meta( 'anship', $user->ID )); ?>" /></td>
	</tr>
	<tr>
		<td><label for="telpaship">No.Telp</label></td>
		<td><input type="number" name="telpaship" value="<?php echo esc_attr(get_the_author_meta( 'telpaship', $user->ID )); ?>" id="txtboxToFilter"/></td>
	</tr>
	<tr>
		<td><label for="aship">Alamat</label></td>
		<td><textarea name="aship"  class="regular-text"><?php echo esc_attr(get_the_author_meta( 'aship', $user->ID )); ?></textarea></td>
	</tr>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</table>
