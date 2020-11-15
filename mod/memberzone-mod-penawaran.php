<?php
class Memberzone_Module_Penawaran extends WP_Widget {
	private
		$plugin_name,
		$plugin_version;
	public function __construct() {

		parent::__construct(
			'memberzone_mod_penawaran',
			'Memberzone: Penawaran',
			array('description' => 'Formulir permintaan penawaran.')
		);
		
	}
	
	
	public function widget($args, $instance) {
		$get_opsi 		= getdata_bykoland_id('custom_meta_key','','meta_opsional',1);
		$post_id 		= Memberzone_Base::get_post_id();
		$post_type 		= get_post_type($post_id);
		$widget 		= $args['before_widget'];
		if (!empty($instance['title'])) {
			$widget .= $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}
		
		ob_start();
		include MEMBERZONE_UI . 'memberzone-ui-widget-penawaran.php';
		$widget .= ob_get_clean();
		
		$widget .= $args['after_widget'];
		
		if ($post_type != 'blog') {
			print $widget;
		}
	}
	
	public function form($instance) {
		$title = !empty($instance['title']) ? $instance['title'] : 'Permintaan Penawaran';
		ob_start();
		include MEMBERZONE_UI . 'memberzone-ui-swidget-penawaran.php';
		$form = ob_get_clean();
		print $form;
	}
	
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
	}
	
	public function display_button() {
		$member_basic = Memberzone_Base::get_member_info();
		$member_extra = Memberzone_Base::get_member_extra();
		$post_id      = Memberzone_Base::get_post_id();
		
		ob_start();
		include MEMBERZONE_UI . 'memberzone-ui-penawaran.php';
		$penawaran = ob_get_clean();
		
		print $penawaran;
	}
	
	public function processing(){		
		global $wpdb;
		$id		= $_POST['id'];
		$data	= get_list_quoby_id($id);
		$allpost =array_map('return_sanitize_text',$_POST);
		switch ($_POST['memberzone-penawaran-kirim']) {
			case 'Kirim':
				$member_basic = Memberzone_Base::get_member_info();
				
				$unset 		  = array('memberzone-penawaran-kirim','action','memberzone-penawaran-opsional');
				$getcusopsi	  = getdata_bykoland_id('custom_meta_key','meta_key','meta_opsional',1);
				if ($getcusopsi) {
					foreach ($getcusopsi as $kco => $valco) {
						array_push($unset, $valco['meta_key']);
						$opsi[]=$valco['meta_key']=$_POST[$valco['meta_key']];
					}
				}
				$listquo	= unset_larik($allpost,$unset);
				if(!cekosong($listquo)){
					$option_mar		= get_option('memberzone_marketer');
					$noquo_fromdb	= get_noquo();					
					if (!cekosong($option_mar['noquo']) || $noquo_fromdb) {

						($option_mar['noquo'] > $noquo_fromdb)? $noquo=$option_mar['noquo']+1 : $noquo=$noquo_fromdb+1;
						
						$updatequo			= get_option('memberzone_marketer');
						$updatequo['noquo']	= $noquo; 
						
						update_option('memberzone_marketer', $updatequo); 
					}else{
						$noquo=1;	
					}
					$push			= array( 'idcus'=>get_current_user_id(), 'noquo'=>$noquo,'time'=>date('Y-m-d'),'status'=>0);
					
					$push['memberzone-penawaran-opsional']	= implode(',',$opsi).' , '.$_POST['memberzone-penawaran-opsional'];	
					$list_quo								= array_merge($listquo,$push);
					$postingan								= get_post($list_quo['memberzone-penawaran-produk']);
					$permalink								= get_permalink($list_quo['memberzone-penawaran-produk']);
					if (check_quoprod($list_quo['idcus'],$list_quo['memberzone-penawaran-produk'])) {
						wp_redirect($permalink.'?e=5');
						exit;
					}else{
						ob_start();
						include MEMBERZONE_UI . 'email/memberzone-ui-penawaran-email.php';
						$email = ob_get_clean();
						Memberzone_Base::post_email(
							get_option('memberzone_marketer'),
							get_option('admin_email'),
							$member_basic->user_email,
							'Permintaan penawaran untuk ' .$postingan->post_title ,
							$email
						);
						insert_data('list_quo',$list_quo);
						wp_redirect($permalink);
						exit;
					}
			}else{
				$permalink=get_permalink($_POST['memberzone-penawaran-produk']);
				wp_redirect($permalink.'?e=1');
				exit;	
			}
			break;
			case 'acc':
			$data=get_list_quoby_id($id);
			$min_email=get_option('admin_email');
			$mimin=get_user_by('email',$min_email);
        	if (cekosong($data['status'])){
				ob_start();	
				include MEMBERZONE_UI . 'email/memberzone-ui-penawaran-email-quo.php';
				$msg = ob_get_clean();
				send_mail_attc(
							$data['email'], 
							$min_email, 
							$mimin->user_login, 
							'PENAWARAN '.$data['produk']. 'terlampir PDF', 
							$msg, 
							$data['noquo']
				);
				$data=array('status'=>'2','exp_time'=>duedate());
				update_data('list_quo',$data,array('id'=>$id));
				$params=array('di'=>'q','id'=>encrypt_url($id));
				$redirect=esc_url(admin_url('?')).http_build_query($params, '', "&");
				wp_redirect($redirect);
				exit;
        	}
        	break;
        	case 'rejected':
				$allpost['id']	= intval(memberzone_dekrip($allpost['id']));
				$list_tabel	= array('list_quo'=>'id','pesan'=>'id_quo');
				foreach ($list_tabel as $ktb => $valtb) {
					delete_data($ktb,array($valtb=>$allpost['id']));
				}
				echo '?page=request-page';
			break;
			case 'remove':
				switch (get_userrole()) {
					case 'subscriber':
						$params		= array('page' =>'request-page');
						update_data('list_quo',array('status'=>4),array('id'=>$id));
						break;
					case 'administrator':
						$params		= array('page' =>'quo-cus-page');
						$inv		= $_SERVER["DOCUMENT_ROOT"].'/pdf/inv/Invoice-'.$data['noquo'].'.pdf';
						$quo		= $_SERVER["DOCUMENT_ROOT"].'/pdf/quo/Quotation-'.$data['noquo'].'.pdf';
						if (file_exists($inv) && file_exists($quo)){
							unlink($inv);unlink($quo);
						}
						if (!(delete_data('list_quo',array('id'=>$id))) && !(delete_data('pay',array('noquo'=>$id.'-'.$data['noquo'])))){
							$params['e']='4';
						}
						break;
				}
				$admin_url	= esc_url(admin_url('?'));
				$redirect 	= $admin_url.http_build_query($params, '', "&");
				echo $redirect;
			break;
			case 'invoice':
				$min_email		= get_option('admin_email');
				$mimin			= get_user_by('email',$min_email);
				$data_listquo	= get_list_quoby_id($id,true);
				
        		if ($data_listquo['status']==3) {
					$data		= array('status'=>1,'exp_time'=>duedate(),'memberzone-total-tagihan'=>$data_listquo['subtotal']);
					$params		= array('di'=>'i','id'=>encrypt_url($id));

					update_data('list_quo',$data,array('id'=>$id));
					$redirect	= esc_url(admin_url('?')).http_build_query($params, '', "&");
        			wp_redirect($redirect);
        			exit;
        		}
				break;
			
			case 'konfirmasi':
				$unset 		  		= array('memberzone-penawaran-kirim','action');
				$pay				= unset_larik($allpost,$unset);
				$hitung_kosong		=array_count_values(array_map('kosong',$pay));
				$params				=array('page'=>'request-page');
				if ($hitung_kosong[1] > 0){
					$params['e']='1';
				}else if ($pay['sisa'] > 0){ 
					$params['e']=7;
				}else{
					$pay['noquo']		= memberzone_dekrip($pay['noquo']);
					$pay_id				= getdata_bykoland_id('pay','id','noquo',$pay['noquo']);
					$pay['idrektoko']	= intval($pay['idrektoko']);
					$id_pecah			= explode('-', $pay['noquo']);
					$total_tagihan		= getdata_bykoland_id('list_quo','memberzone-total-tagihan','id',$id_pecah[0]);
					$total_terbayar		= getdata_bykoland_id('pay','total_terbayar','noquo',$pay['noquo']);
					$pay['time'] 		= date('Y-m-d h:i:s');
					$pay['sisa']		= ($total_terbayar+$pay['nominal'])-$total_tagihan;
					($pay['sisa']==0)? $pay['status']=6 : $pay['status']=5;				
					if ($pay_id) {
						update_data('pay',$pay,array('id'=>$pay_id));
					}else{
						$pay['iduser']	= get_current_user_id();
						update_data('list_quo',array('status'=>5),array('id'=>$id_pecah[0]));
						insert_data('pay',$pay);
					}
				}
				wp_redirect(admin_url('?').http_build_query($params, '', "&"));
				exit();
			break;				
			case 'diterima':
				$data		 =	 get_list_quoby_id($id,'y');
				$noquo		 = $id.'-'.$data['noquo'];
				$pay_nominal = getdata_bykoland_id('pay','nominal','noquo',$noquo);
				$pay_total 	 = getdata_bykoland_id('pay','total_terbayar','noquo',$noquo);
				$pay_total+=$pay_nominal;
				$data 		 = array( 'time'=>date('Y-m-d h:i:s').'y','total_terbayar'=>$pay_total);
				if (update_data('pay',$data,array('noquo'=>$noquo))) {
					echo '?page=quo-cus-page';
				}
				break;
			case 'addbank':
				$unset=array('memberzone-penawaran-kirim','action');
				$data=unset_larik($allpost,$unset);
				if (!cekosong($data)) {
					$kol=get_list_fields('bank_toko');
					$cekdata=get_data_table('bank_toko',$kol,$id,false);
					
					if ($cekdata) {
						update_data('bank_toko',$data,array('id'=>$id));
					}else{
						insert_data('bank_toko',$data);
					}
				}
				wp_redirect(esc_url(admin_url('?page=info-toko-page')));
				exit();
				break;
			case 'deldata':
				switch ($allpost['tabel']) {
					case 'custom_meta_key':
						$meta_key	=getdata_bykoland_id('custom_meta_key','meta_key','id',$allpost['id']);
						delete_data('custom_meta_opsional',array('meta_key'=>$meta_key));
						echo (delete_data($allpost['tabel'],array($allpost['kolpri']=>$allpost['id'])))? $allpost['permalink'] : '?e=4';
					break;						
					default:
						echo (delete_data($allpost['tabel'],array($allpost['kolpri']=>$allpost['id'])))? 'berhasil MENGHAPUS data :)' : 'gagal MENGHAPUS data :(';
						break;
				}
				break;
		case 'upload_img':
				$admin_url=array('page'=>'info-toko-page');
				if ( ! function_exists( 'wp_handle_upload' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				$keyfiles=array_keys($_FILES);
				$tangkap=array();
				if (!cekosong($keyfiles)) {
					foreach($keyfiles as $valkey) {
						$getfiles=$_FILES[$valkey];
						if (!cekosong($getfiles["name"])) {
							$tangkap[]=$getfiles;
							$tangkap[]=$valkey;
						}
					}
				}
				$uploadedfile =$tangkap[0];
				$upload_overrides = array( 'test_form' => false );
				$lentype=strlen($uploadedfile['type']);
				$idimg=substr($tangkap[1],-2,2);
				$extenimage=substr($uploadedfile['type'],6,$lentype);
				if ($extenimage==='jpg' || $extenimage==='jpeg' || $extenimage==='JPG' || $extenimage==='JPEG') {
					$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
					if ( $movefile && !isset( $movefile['error'] )) {
						update_data('gambar',array('file'=>$movefile['file'],'url'=>$movefile["url"]),array('id'=>$idimg));
						wp_redirect(esc_url(admin_url('?')).http_build_query($admin_url, '', "&"));
						exit();
					}else {
						$admin_url['e']='2';
						wp_redirect(esc_url(admin_url('?')).http_build_query($admin_url, '', "&"));
						exit();
					}
				}elseif (!cekosong($_POST['id_img']) && isset($_POST['del']) && !cekosong($_POST['del'])) {
						$file=getdata_bykoland_id('gambar','file','id',$_POST['id_img']);
						if (file_exists($file)) {
						unlink($file);
						update_data('gambar',array('url'=>'','file'=>''),array('id'=>$_POST['id_img']));
						echo esc_url(admin_url('?')).http_build_query($admin_url, '', "&");
					}
				}else{
					$admin_url['e']='3';
					wp_redirect(esc_url(admin_url('?')).http_build_query($admin_url, '', "&"));
					exit();
				}
				break;

			case 'perbaharui':
				if (isset($_POST['tabel']) && !cekosong($_POST['tabel'])) {
					$kolid	= $_POST['kolid'];$id=$_POST['id'];$tabel=$_POST['tabel'];$data=array();
					$unset	= array('memberzone-penawaran-kirim','action','permalink','id','tabel','kolid');
					$data	= unset_larik($allpost,$unset);
					switch ($_POST['tabel']) {
						case 'custom_meta_key':
							($data['meta_opsional']==='true')? $data['meta_opsional']=1 : $data['meta_opsional']=0;
							break;
						case 'pay':
							if ($data['status']==7) {
								$nom_sisa		 = getdata_bykoland_id('pay','sisa','id',$id);
								$pay_nominal	 = getdata_bykoland_id('pay','nominal','id',$id);
								$data['sisa']	 = intval($nom_sisa-$pay_nominal);
								$data['nominal'] = 0;
							}
							break;
					}
					if (!check_url_format($allpost['kurir_link_web']) && $_POST['tabel']=='log_noresi') {
						$params		= array('page'=>'quo-cus-page','e'=>'6');
						$redirect	= admin_url('?').http_build_query($params, '', "&");
						$segarkan	= $redirect;
					}else {
						$segarkan	= $_POST['permalink'];
						update_data($_POST['tabel'],$data,array($kolid=>$id));
					}
				}				
				echo $segarkan;
				break;
			case 'insertdata':
				if (!cekosong($allpost)) {
					$unset=array('memberzone-penawaran-kirim','action','permalink','tabel');
					$data=unset_larik($allpost,$unset);
					switch ($_POST['tabel']) {
						case 'custom_meta_key':
							($data['meta_opsional']==='true')? $data['meta_opsional']=1 : $data['meta_opsional']=0;
							$data['meta_type_id']=intval($data['meta_type_id']);
							$data=array_merge($data,array('meta_key'=>str_replace(' ', '_', $_POST['meta_label'])));
							if ($data['meta_type_id']==2) {
								$_POST['permalink']='options-general.php?page=memberzone';
							}
							break;
						case 'sales_contacts':
							$data['sales_user_id'] =get_current_user_id();
							break;
						case 'pesan':
							$data['from']	= get_current_user_id();
							$data['id_quo']	= memberzone_dekrip($data['id_quo']);
							$data['iduser']	= memberzone_dekrip($data['iduser']);
							update_data('list_quo',array('status'=>11),array('id'=>$data['id_quo']));
							break;
					}
					insert_data($_POST['tabel'],$data);
				}
				echo $_POST['permalink'];
				break;		
			case 'reqresi':
				$data=array(
					'noquo'=>intval(memberzone_dekrip($allpost['id']))
					,'status'=>1
				);
				insert_data('log_noresi',$data);
				echo 'admin.php?page=request-page';
				break;
			case 'testiproduk':
				$user_info	= Memberzone_Base::get_member_info();
				$time 		= current_time('mysql');
				if (!cekosong($allpost['comment_content'])) {
					$data 		= array(
						'comment_post_ID' => $allpost['memberzone-penawaran-produk'],
						'comment_author' => $user_info->user_login,
						'comment_author_email' => $user_info->user_email,
						'comment_author_url' => $user_info->user_url,
						'comment_content' => $allpost['comment_content'],
						//'comment_type' => '',
						'comment_parent' => 0,
						'user_id' => $user_info->ID,
						//'comment_author_IP' => '127.0.0.1',
						//'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
						'comment_date' => $time,
						'comment_approved' => 1,
					);
					wp_insert_comment($data);
					$comment_lastid = $wpdb->insert_id;
					update_data('list_quo',array('status'=>4,'review_id'=>$comment_lastid ),array('id'=>$allpost['id']));
				}
				$params	=array('page'=>'request-page','e'=>'8');
				$redirect=admin_url('?').http_build_query($params, '', "&");
				echo $redirect;
				break;
			case 'getbyid':
				$data =getdata_bykoland_id($allpost['table'],$allpost['column'],'id',$allpost['id']);
				echo $data;
				break;
			case 'getdata':
				$larik =return_json_data($allpost['tabel']);
				echo json_encode($larik);
				break;
		}
	}
	public function register_widgets() {
		register_widget('Memberzone_Module_Penawaran');
	}
	
	public function to_arp($larik=array()) {
		$arp = "http://email.jvm.co.id/a.php/sub/9/c35tv8";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_USERAGENT, 'ARPR');
		curl_setopt($curl, CURLOPT_URL, $arp);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $larik);
		curl_exec($curl);
		curl_close($curl);
	}
}

/* Akhir dari berkas memberzone-mod-penawaran.php */



