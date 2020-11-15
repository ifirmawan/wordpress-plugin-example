<table >
		<tr> 
            <td><strong>Nama Label </strong></td>
            <td>
				<input type="text" class="regular-text meta_label" placeholder="Label Opsional Ex:ukuran " />
            </td>
        </tr>    
		<tr>
			<td><strong>Type label</strong></td>
            <td>
			<?php $select=array('checkbox'=>'Checkbox','select'=>'Dropdown','radio'=>'Radio Button','text'=>'text','textarea'=>'textarea');?>
            <select class="regular-text meta_type"> 
				<?php 
				foreach($select as $ksel => $valsel) { ?>
					<option value="<?php echo $ksel;?>"><?php echo $valsel;?></option>
				<?php } ?>
            </select>
            </td>
         </tr>
         <tr>
			<td><strong>Max. isi label</strong></td>
            <td><input type="number" class="regular-text meta_max_val" placeholder="contoh panjang maximal 25" /></td>
         </tr>
        <tr>
            <td>                
                <button type="button" class="button button-primary btn-insert">simpan</button>
				<button type="reset" class="memberzone-button">reset</button>
				<a href="<?php echo esc_url(admin_url('options-general.php?page=memberzone'));?>" class="button memberzone-button permalink">kembali</a>
            </td>
        </tr>
    </table>
</form >