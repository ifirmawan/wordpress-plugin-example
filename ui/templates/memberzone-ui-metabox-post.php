<div class="mzone-row">
            	<div class="mzone-col-1">
            	<span class="<?php echo ($value['meta_opsional']==1) ? 'dashicons dashicons-yes' : 'dashicons dashicons-no-alt';?>" data-toggle="tooltip" data-placement="left" title="<?php echo ($value['meta_opsional']==1) ? 'opsional' : 'bukan opsional';?>"></span>
            	<a href="<?php echo $btn_edit_url;?>" class="memberzone-button ">
            	<span class="dashicons dashicons-edit"></span>
            	</a>
            	</div>
            	<div class="mzone-col-2" >
            	<strong><?php echo $value['meta_label'];?> </strong>
            	</div>
            <div class="mzone-col-3"><p>
            	<?php  switch ($value['meta_type']) {
case 'select': 
						?>
						<select name="<?php echo $value['meta_key'];?>" class="memberzone-width-100">
							<?php foreach ($get_opsional as $val_opt) { ?>
								<option value="<?php echo $val_opt['id'];?>" class="<?php echo $val_opt['class'];?>" <?php echo ($val_opt['id']==$input_val)? 'selected': '';?>  maxlength="<?php echo $value['meta_max_value'];?>" ><?php echo $val_opt['opsional'];?></option>
							<?php } ?>
						</select>
			<input type="text" id="<?php echo 'txt-'.$value['meta_key'];?>" style="display:none;" />
			<button type="button"  name="<?php echo $value['meta_key'];?>" id="<?php echo 'ad-'.$value['meta_key'];?>" data-toggle="tooltip" data-placement="left" title="tambah pilihan baru" class="addval" >
				<span class="dashicons dashicons-plus"></span>
			</button>
			<button type="button" class="btn-save-opt" id="<?php echo 'btn-'.$value['meta_key'];?>" style="display:none;" >simpan</button>
						<?php break;
case 'checkbox':
						
						$checkbox=$input_val;
						foreach ($get_opsional as $val_opt) {
							?>
							<input type="<?php echo $value['meta_type'];?>" name="<?php echo $value['meta_key'].'[]';?>" value="<?php echo $val_opt['id'];?>" class="<?php echo $val_opt['class'];?>" <?php echo (!cekosong($checkbox) && in_array($val_opt['id'],$checkbox))? 'checked': '';?>  maxlength="<?php echo $value['meta_max_value'];?>" ><?php echo $val_opt['opsional'];?>
						<?php
						}
						?>
			<input type="text" id="<?php echo 'txt-'.$value['meta_key'];?>" style="display:none;" />
			<button type="button"  name="<?php echo $value['meta_key'];?>" id="<?php echo 'ad-'.$value['meta_key'];?>" data-toggle="tooltip" data-placement="left" title="tambah pilihan baru" class="addval"><span class="dashicons dashicons-plus"></span></button>
			<button type="button" class="btn-save-opt" id="<?php echo 'btn-'.$value['meta_key'];?>" style="display:none;" >simpan</button>
						<?php break;
case 'radio':
						
						foreach ($get_opsional as $val_opt) { ?>
							<input type="<?php echo $value['meta_type'];?>" name="<?php echo $value['meta_key'];?>" value="<?php echo $val_opt['id'];?>" class="<?php echo $val_opt['class'].' '.$value['meta_key'];?> " <?php echo ($val_opt['id']==$input_val)? 'checked': '';?>  maxlength="<?php echo $value['meta_max_value'];?>" >
							<?php echo $val_opt['opsional'];?>
						<?php }?>			
			<input type="text" id="<?php echo 'txt-'.$value['meta_key'];?>" style="display:none;" />
<button type="button"  name="<?php echo $value['meta_key'];?>" id="<?php echo 'ad-'.$value['meta_key'];?>" data-toggle="tooltip" data-placement="left" title="tambah pilihan baru" class="addval" <?php echo ($value['meta_key']==='met_dis' || $value['meta_key']==='cekbtnquo')? 'style="display:none"': '';?>>
	<span class="dashicons dashicons-plus" ></span>
</button>	
						<button type="button" class="btn-save-opt" id="<?php echo 'btn-'.$value['meta_key'];?>" style="display:none;" >simpan</button>
						<?php break;
case 'number': ?>
						<input type="<?php echo $value['meta_type'];?>" name="<?php echo $value['meta_key'];?>" value="<?php echo $input_val;?>"  maxlength="<?php echo $value['meta_max_value'];?>" id="txtboxToFilter" <?php echo ($value['meta_key']==='brgdiskon')? 'disabled': '';?> class="memberzone-width-100 "/>
						<?php break;
case 'text': ?>
						<input type="<?php echo $value['meta_type'];?>" name="<?php echo $value['meta_key'];?>" value="<?php echo $input_val;?>"  maxlength="<?php echo $value['meta_max_value'];?>" class="memberzone-width-100 "/>
					<?php 	break;
case 'textarea': ?>
						<textarea name="<?php echo $value['meta_key'];?>"  maxlength="<?php echo $value['meta_max_value'];?>"  class="memberzone-width-100 "><?php echo $input_val;?></textarea>
						<?php break;
				} ?>
            </p></div> 
</div> 
