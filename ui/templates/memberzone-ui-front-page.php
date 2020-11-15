<span id="imgurl" style="display:none;"><?php echo MEMBERZONE_AST.'images/ajax-loader.gif';?></span>
<div id="container-<?php echo $valquo['id'];?>" <?php echo (isset($_GET['prm']) && decrypt_url($_GET['prm'])==$valquo['id'])? 'style="background-color:#fff000;"': '';?>>
<table>
			<tr>
				<td width="200" rowspan="4">
						<input type="hidden" value="<?php echo encrypt_url($noid);?>" id="noid-<?php echo $valquo['id'];?>" />
						<a href="<?php echo esc_url(home_url($post->post_title.'/'.$post->ID));?>">
							<img src="<?php echo $image[0];?>" alt="<?php echo 'featured image-'.$valquo['memberzone-penawaran-produk'];?>" style="width:85%;" />
						</a>
					</td>
					<td colspan="2"><h1><?php echo (isset($list_title) && strlen($list_title)>0 )? $list_title : 'none';?></h1></td>
				</tr>
				<tr>
					<td><?php echo (isset($list_time) && strlen($list_time)>0 )? $list_time : 'Hari ini : '.date('Y-m-d h:i:s');?> </td>
					<td colspan="2"><?php echo (isset($list_status) && strlen($list_status)>0 )? $list_status : 'none';?> </td>
				</tr>
				<tr>
					<td colspan="2">
						<p style="color:#000;background:yellow;">
						<?php 

						echo (isset($list_content) && strlen($list_content)>0 )? $list_content : '';?>
						</p>
					</td>
				</tr>
				
				<tr>
					<td colspan="3"><?php echo (isset($list_action) && strlen($list_action)>0 )? $list_action : '';?> </td>
				</tr>
</table>
<div>
