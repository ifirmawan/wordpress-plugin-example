<?php 
$content='';$selector='';$css_class='thickbox';
if (isset($_GET['action']) && $_GET['action']==='edit'):
	$params=array('post'=>$_GET['post'],'action'=>'edit');
	$permalink=esc_url(admin_url('post.php?')).http_build_query($params, '', "&");
else: 
	$permalink=esc_url(admin_url('/post-new.php'));
endif;
if (isset($toshow) && !cekosong($toshow)) {
	switch ($toshow) {
		case 'pay':
			$content 	.='<ul>';
			$pay_labels	= array(
				'Username ',
				'Nama Bank',
				'No. Rekening Pelanggan',
				'Atas Nama Rekening',
				'Nominal Pembayaran Transfer',
				'Berita Acara',
				'Status Tagihan',
				'Waktu Transfer',
				'No. Rekening Tujuan'
				);
			$x=0;
			foreach (get_list_fields('pay') as $kpay => $kolpay) {
				if (!in_array($kolpay, array('id','noquo','total_terbayar','sisa'))) {
					$isinya 		= getdata_bykoland_id('pay',$kolpay,'noquo',$noquopay);
					switch ($kolpay) {
						case 'idrektoko':
							$norektoko	= getdata_bykoland_id('bank_toko','bank_norek','id',$isinya);
							$banktoko 	= getdata_bykoland_id('bank_toko','bank_nama','id',$isinya);
							$view_pay	= $banktoko.' No. Rek. '.$norektoko;
							break;
						case 'status':
							($isinya==6)? $view_pay ='Lunas' : $view_pay='Terhutang';
							break;
						case 'iduser':
							$userdata	= get_userdata($isinya);
							$view_pay 	= $userdata->user_login;
							break;
						default:
							$view_pay 	 = $isinya;
							break;
					}
					$content 	.='<li><span id="memberzone-label">'.$pay_labels[$x].'</span> <strong>'.$view_pay.'</strong></li>';
					$x++;
				}
			}
			$content 	.='</ul>';
			$content 	.='<span id="imgurl" style="display:none;">'.MEMBERZONE_AST.'images/ajax-loader.gif</span>';
			$content 	.='<a href="#" class="memberzone-button btn-terima" id="'.$quo['id'].'">Di Terima</a>';
			$content 	.='<a href="#" class="memberzone-button btn-tolak" id="'.$quo['id'].'-'.$quo['noquo'].'">Tidak Valid</a>';
			$selector	= $quo['id'];
			$label 		= 'Periksa Pembayaran';
			$css_class	='thickbox button button-primary';
			break;
		case 'image':
			$content .='<img src="'.$valimg['url'].'"  />';
			$label=$valimg['url'];
			$selector=$valimg['id'];			
			break;
		case 'add_metabox':
			
			$selector	='1';
			$css_class	=$css_class.' memberzone-button';
			$content .='<table><tr>';
			$content .='<td><strong>Nama Label </strong></td><td>';
			$content .='<input type="hidden" class="regular-text meta_type_id" value="'.$val_meta_type_id.'"/>';
			$content .='<input type="text" class="regular-text meta_label" placeholder="Label Opsional Ex:ukuran " />';
            $content .='</td></tr>';    
			$content .='<tr><td><strong>Type label</strong></td><td>';
			$select	 =array('checkbox'=>'Checkbox','select'=>'Dropdown','radio'=>'Radio Button','text'=>'text','textarea'=>'textarea');
            $content .='<select class="regular-text meta_type">'; 
				foreach($select as $ksel => $valsel) {
					$content .='<option value="'.$ksel.'">'.$valsel.'</option>';
				}
            $content .='</select></td></tr>';
            $content .='<tr><td><strong>Max. isi label</strong></td>';
			$content .='<td><input type="number" class="regular-text meta_max_val" placeholder="isi max. ex:25" />';
			$content .='</td></tr>';
			if (strlen($label)==15) {
				$content .='<tr>';	
				$content .='<td><strong>Tampilkan opsional</strong></td>';
				$content .='<td><input type="checkbox" class="meta_opsional" value="1"/></td>';
				$content .='</tr>';
			}
			
			$content .='<tr><td><button type="button" class="button button-primary btn-insert">simpan</button>';
			$content .='<button type="reset" class="memberzone-button">reset</button>';
			$content .='<a href="'.esc_url(admin_url('edit.php?page=opsional-page')).'" class="button memberzone-button permalink">kembali</a>';
            $content .='</td></tr></table>';
			break;
		case 'login':
			$selector='login';
			
			$css_class=$css_class.' memberzone-button memberzone-button-primary memberzone-width-100';
			$label='Login untuk meminta penawaran ';
			if (get_option( 'users_can_register')): 
				$content .='<span id="imgurl" style="display:none;">'.MEMBERZONE_AST.'images/ajax-loader.gif'.'</span>';
				$content .='<p style="color:#000;background:#F5D76E;padding:5px;">';
				$content .='Belum memilki akun? klik <a href="'.wp_registration_url().'" class="button button-primary " > mendaftar</a> untuk menjadi anggota';
				$content .='</p>';
			else:
				$content .='<p style="color:#000;background:#F5D76E;padding:5px;">';
				$content .='Belum memilki akun? silahkan hubungi email ini <a href="mailto:'.bloginfo("admin_email").'"></a> untuk mendaftarkan anda sebagai anggota';
				$content .='</p>';
			endif;
			break;
		case 'formtawar':
			$selector='tawar-'.$idpost;
			$label='Minta penawaran';
			$css_class=$css_class.' memberzone-button memberzone-button-primary memberzone-width-100';
			$cttnbrg=get_post_meta($idpost,'noteprod', true); 
			(cekosong($cttnbrg))? $none='style="display:none;"' : $none='';
			$content .='<form method="post" action="'.esc_url(admin_url('admin-post.php')).'">';
			$content .='<p '.$none.'><label>Catatan Produk :</label> &nbsp;'.$cttnbrg.'</strong></p>';
			$content .='<p><label>Produk :</label> &nbsp;<strong>'.$postingan->post_title.'</strong>';
			$content .='<input type="hidden" name="memberzone-penawaran-produk" value="'.$idpost.'" /></p>';
			$content .='<p><input class="memberzone-width-100 txt-required" name="memberzone-penawaran-jumlah" type="number" placeholder="Masukkan jumlah barang" id="txtboxToFilter" required/></p>';
			if ($get_opsi) {
				foreach($get_opsi as $kopsi => $valopsi) {
					$content .='<p><label>'.$valopsi["meta_label"].':</label>';
					$opsional =getdata_bykoland_id('custom_meta_opsional','opsional','meta_key',$valopsi['meta_key']);
					if (is_array($opsional) && count($opsional)>1) {
						foreach($opsional as $valop) {
							$content .='&nbsp<input type="radio" name="'.$valopsi['meta_key'].'" value="'.$valop['opsional'].'"/>&nbsp'.$valop['opsional'];
						}
					}
				}
			}
			$content .='<textarea class="form-control" rows="3" placeholder="keterangan Opsional lain" name="memberzone-penawaran-opsional"></textarea>';
			$content .='<input name="action" type="hidden" value="memberzone_penawaran">';
			$content .='<input class="btn-send-quo memberzone-button memberzone-button-primary memberzone-width-100" name="memberzone-penawaran-kirim" type="submit" value="Kirim">';
			$content .='</form >';
			break;
		case 'confirmpay':
			$selector	='cfmpay-'.$valquo['id'];
			$label 		='Bayar';
			$css_class	=$css_class.' memberzone-button memberzone-button-primary';					
			$option_isi	= '';
			if ($listrek) {
				foreach ($listrek as $key => $value) {
					$option_isi .='<option value="'.$value['id'].'">'.$value['bank_an'].'-'.$value['bank_nama'].'-'.$value['bank_norek'].'</option>';
				}
			}else{	$option_isi .='<option value="0">Belum tersedia</option> ';}
			if ($nom_sisa < 0) { 
				$inv_sisa =substr($nom_sisa,1,$len_nom_sisa);
			}elseif ($nom_sisa==0 && $pay_status==7) {
				($total_terbayar>0)? $inv_sisa =$telah_bayar : $inv_sisa=$total_tagihan;
			}elseif ($nom_sisa===false) { $inv_sisa =$total_tagihan;
			}else{ $inv_sisa =$nom_sisa; }
			ob_start();
			include MEMBERZONE_UI.'templates/memberzone-ui-confirm-pay.php';
			$content  =ob_get_clean();

			break;
		case 'msgrjct':
			$selector	='msg-'.$quo['id'];
			$label		='Rejected';
			$css_class  .=' button button-default';
			$content ='<div class="mzone-grid-container mzone-outline">';
			$content .='<span class="mzone-row">';
			$content .='<span class="mzone-col-1" ><label>Kepada </label></span>';
			$content .='<span class="mzone-col-5" >'.$User->user_login.'</span>';
			$content .='</span>';
			$content .='<span class="mzone-row">';
			$content .='<span class="mzone-col-1" >Penawaran produk</span>';
			$content .='<span class="mzone-col-5" ><strong>'.$postingan->post_title.'</strong></span>';
			$content .='</span>';
			$content .='<span class="mzone-row">';
			$content .='<span class="mzone-col-1" >Alasan penolakan</span>';
			$content .='<span class="mzone-col-5" ><textarea name="pesan'.$quo['id'].'" class="regular-text memberzone-width-100" placeholder="berikan alasan yang jelas dan logis terkait penolakan yang dilakukan" ></textarea></span>';
			$content .='</span>';
			$content .='<span class="mzone-row"><input type="hidden" name="iduser'.$quo['id'].'" value="'.memberzone_enkrip($quo['idcus']).'" /><input type="hidden" name="id_quo'.$quo['id'].'" value="'.memberzone_enkrip($quo['id']).'" />';
			$content .='<span class="mzone-col-6" ><button type="button" class="memberzone-button memberzone-button-primary btn-msg-reject" id="'.$quo['id'].'">kirim</button></span>';
			$content .='</span>';
			$content .='</div>';			
			break;
		case 'detailreject':
			$selector	='dmsg-'.$quo['id'];
			$label		='Pesan penolakan';
			$css_class  .=' button button-primary';
			switch ($reject_msg_sts) {
				case 0:
					$msg_sts ='BELUM TERBACA';
					break;
				case 1:
					$msg_sts ='SUDAH TERBACA';
					break;
			}
			$content 	='<div class="mzone-grid-container mzone-outline"><span class="mzone-row">';
			$content 	.='<span class="mzone-col-3" ><strong>'.$User->user_login.'</strong></span>';
			$content 	.='<span class="mzone-col-3" ><h3>'.$msg_sts.'</h3></span></span>';
			$content 	.='<span class="mzone-row">';
			$content 	.='<span class="mzone-col-6" >'.$reject_msg.'</span>';
			$content 	.='</span></div>';
			break;
	}
}
?>
<a href="#TB_inline?width=800&height=300&inlineId=pay-content-<?php echo $selector;?>" class="<?php echo $css_class;?>"><?php echo $label;?></a>
<div id="pay-content-<?php echo $selector;?>" style="display:none;">
	<div class="thickbox-info" >
	<?php 
	if($toshow=='login'):
	$args = array(
				//'echo'           => false,
				'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
				'form_id'        => 'loginform',
				'label_username' => __( 'Username' ),
				'label_password' => __( 'Password' ),
				'label_remember' => __( 'Remember Me' ),
				'label_log_in'   => __( 'Log In' ),
				'id_username'    => 'user_login',
				'id_password'    => 'user_pass',
				'id_remember'    => 'rememberme',
				'id_submit'      => 'wp-submit',
				'remember'       => true,
				'value_username' => '',
				'value_remember' => false
	);
	$content .=wp_login_form($args);
	endif;
	echo $content;?>
	</div>
</div>