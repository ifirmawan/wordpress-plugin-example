<?php
/**
* modul tambahan merupakan modul pengembangan yang 
* membangun sistem penawaran yang menempatkan pengguna didalam Dashboard wordpress.
* 
**/

class Member_tambahan {
    /**
	 * @since 1.0.1
	 * @package memberzone
	 * @author Iwan firamawan <firmawaneiwan@gmail.com> 
	 * */

	
    function Product_name($idpost=''){
    	$postingan	= get_post($idpost);
    	$brgnama    = get_post_meta($idpost,'product_name', true);
    	(strlen($brgnama)>0)? $name =$brgnama : $name =$postingan->post_title;
    	return $name;

    }

	/**
	 * @count_req_quo fungsi untuk menampilkan jumlah penawaran yang masuk, dijalankan dengan
	 * action "dashboard_glance_items"
	 **/
    function count_req_quo(){
        $num=get_data_table('list_quo',array('id'),false,'y');
        $text='Permintaan Quotation'; ?>
        <li class="comment-count"> <a href="<?php echo(admin_url('?page=quo-cus-page'));?>"> <?php echo($num.' '.$text);?></a></li>
        <?php 
    }
	/**
	 * @fungsi_init fungsi untuk mengeksekusi semua kondisi yang setiap saat bisa saja bernilai benar
	 * karena pengecekan kondisi dilakukan selama program berjalan atau setiap saat mengeksekusi pengecekan kondisi.
	 * 
	 **/
    
function fungsi_init(){
		/**
		 * fungsi yang memeriksa jika pengguna berkedudukan subscriber mengakses halaman beranda dashboard admin
		 * maka sistem akan mengembalikan ke halaman profilnya sendiri.
		 **/
		$larik_url=getUriSegments();
    	if (in_array('post-new.php',$larik_url) && get_userrole()==='subscriber') {
    		wp_redirect(esc_url(admin_url('?page=request-page')));
    		exit();
    	}
		$user 		= Memberzone_Base::get_member_info();
		$admin_data	= Memberzone_Base::admin_user_ids();
		$params		= array('page'=>'quo-cus-page');
		/* kondisi untuk memeriksa konfirmasi persetujuan penawaran yang dikirim melalui email */
        if (isset($_GET['ac']) || isset($_GET['s'])) {
			
            $larik=get_data_table('list_quo',array('id'),false,false); //variable yang menampung data tabel daftar permintaan penawaran
            
            /*
			 * jika variable get 's' terdaftar maka id_status bernilai isi variable get tersebut,
			 * selain itu maka bernilai 3 yang menandakan status sudah terkonfirmasi Pelanggan yang menerima penawaran/
			 */
            (isset($_GET['s']))? $ids=decrypt_url($_GET['s']) : $ids=3; 
			
			if (current_user_can( 'manage_options' )) {
				/* benar jika pengguna saat ini yang sedang aktif merupakan administrator */
				$redirect='?page=quo-cus-page'; 
			}else{
				/* jika pengguna saat ini yang sedang aktif bukantpd administrator */
				$redirect='admin.php?page=request-page'; 
			}
			$data=array('status'=>$ids ,'exp_time'=>duedate()); //mengisikan data pada kolom status, exp_time pada tabel wp_list_quo
			
            if (is_array($larik) && count($larik) > 1) { //jika variable $data berbentuk larik dan isi larik dihitung lebih dari 1 maka
				
            	$cekid=array_map("return_md5",$larik); // fungsi untuk mengkonversi dari setiap isi larik variable $data menjadi enkripsi md5
            	
				if (array_search($_GET['ac'], $cekid)!==false) { //jika anggota larik varible $cek_id menemukan isi variable GET 'ac' maka
					/**
					 * $k menampung hasil dari pengecekan sebelumnya.berisi data bertype integer sebagai kunci larik pada variable 
					 * $larik, sehingga diperoleh variable berisi primary key untuk memperbaharui record tabel wp_list_quo
					 **/
					$k				= array_search($_GET['ac'], $cekid);
					$idq			= $larik[$k];
					$params['prm']	= encrypt_url($idq);
					$noquo			= getdata_bykoland_id('list_quo','noquo','id',$idq);
					if (is_null($_GET['s'])) {
						$notif =array('iduser'=>$admin_data[0],'from'=>$user->ID,'pesan'=>'<a href="'.admin_url('user-edit.php?user_id='.$user->ID).'" ><h3>'.$user->user_login.'</h3></a> Telah Menyetujui penawaran dengan No. <a href="'.esc_url(admin_url('?').http_build_query($params, '', "&")).'">'.$noquo.'</a>','status'=>0);
						update_data('list_quo',$data,array('id'=>$idq)); 
						insert_data('pesan',$notif);
					}
					
					
					/**
					 * redirect kehalaman dashboard admin sesuai dengan link yang sudah tervalidasi kondisi variable get
					 * 
					 **/
					wp_redirect(esc_url(admin_url($redirect))); 
					exit();
					
				}
			
			/**
			 * jika selain itu jumlah panjang karakter string variable lebih dari nol,
			 * dan variable GET 'ac' sama dengan enkripsi md5 pada variable $larik
			 * $larik menampung data string bukan larik.
			**/
            }elseif (strlen($larik) > 0 && $_GET['ac']==md5($larik)) { 
					
					update_data('list_quo',$data,array('id'=>intval($larik))); //fungsi update data
					
					wp_redirect(esc_url(admin_url($redirect))); //fungsi redirect halaman admin
					exit;
            }
        }
        /**
		 * pemeriksaan kondisi apakah isi varible GET 'id' merupakan salah satu anggota/ akun pengguna/ user/ subscriber wordpress 
		 * yang sudah terdaftar dan melibatkan variable GET 'pf' 
		 **/
        if (isset($_GET['pf']) && isset($_GET['id']) && decrypt_url($_GET['id'])==$user->ID) {
			$allpost	= array_map('return_sanitize_text', $_POST); //menampung semua data post
			foreach($allpost as $key=> $value) { // menampilkan semua isi larik variable $allpost
				$value 	= rtrim($value);
				/**
				 * @wp_update_user fungsi yang disediakan wordpress untuk memperbaharui
				 * data meta user sesuai dengan parameter yang diterima.
				 * berdasarkan kunci larik 'ID'
				 **/
				if (!empty($value)) {
					wp_update_user(array( 'ID' => $user->ID, $key => $value )); 
					/**
				 	* @update_user_meta menyimpan perubahan data meta pengguna
				 	* */
					update_user_meta($user->ID,$key,$value); 
				}	
			}
			/**
			 *mengembalikan kehalaman dashboard admin yang hanya diakses 
			 * oleh pengguna yang berkedudukan subscriber.
			 **/
			wp_redirect(esc_url(admin_url('admin.php?page=profil-page'))); 
			exit();
        }
        
        
        /**
		 * pemeriksaan variable GET 'e' untuk menampilkan box dialog 
		 * pesan yang akan disampaikan setelah proses selesai dijalankan.
		 * berdasarkan penempatan kondisi switch
		 **/
        if (isset($_GET['e']) && !cekosong($_GET['e'])) {
        	$error_id		=htmlspecialchars($_GET['e']);
			$larik_error	=array(
				'1'=>'inputan tidak boleh kosong !'
				,'2'=>'gagal upload'
				,'3'=>'format gambar tidak sesuai, silahkan upload ulang'
				,'4'=>'gagal menghapus data :('
				,'5'=>'Anda sudah pernah melakukan permintaan harga produk ini'
				,'6'=>'Link url kurir tidak valid'
				,'7'=>'Nominal pembayaran melebihi tagihan !, silahkan konfirmasi ulang'
				,'8'=>'Terima kasih atas review yang anda berikan'
				,'9'=>'Maaf dokumen sudah tidak berlaku :('
			);
			foreach ($larik_error as $kerror => $valerror) {
				if ($kerror==$error_id) {
					echo '<script>var confirmsg = confirm("'.$valerror.'");if(confirmsg){history.back();}</script>'; //click(); 
				}
			}
        }
        if (isset($_GET['rq']) && $result=get_where('list_quo',array('id'=>decrypt_url($_GET['rq'])))) {
        	$data=array(
        		'noquo'=>$result[0]['noquo']+=1
        		,'time'=>date('Y-m-d')
        		,'exp_time'=>''
        		,'status'=>''
        	);
        	update_data('list_quo',$data,array('id'=>$result[0]['id']));
        	wp_redirect(esc_url(admin_url('?page=request-page')));
        	exit();
        }
    }    


    //set allow html
    /**
	 * @allow_html fungsi untuk mendukung tag html yang akan disisipkan pada deskripsi kategori.
	 * 
	 **/
    public function allow_html(){
         /**
		  * Disables Kses only for textarea saves
		  * menonaktifkan kses textarea
		  * 
		  **/
        foreach (array('pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description') as $filter) {
			
            remove_filter($filter, 'wp_filter_kses');
        }
        // Disables Kses only for textarea admin displays
        
        foreach (array('term_description', 'link_description', 'link_notes', 'user_description') as $filter) {
            remove_filter($filter, 'wp_kses_data');
        }
    }


	/**
	 * @set_permalink untuk pengaturan permalink pada wordpress
	 * secara otomatis selama plugin dinyalakan
	 * berjalan pada action hook 'init'
	 **/
    public function set_permalink()
    {
        global $wp_rewrite; //memuat library penulisan 
        /**
		 * tetapkan kondisi link dengan segment
		 * pertama 	: nama postingan dengan pengganti spasi menjadi dash
		 * kedua	: id postingan , primary key yang dapat dimanfaat menjadi banyak query yang dibutuhkan pengembang
		 * 
		 **/
        $wp_rewrite->set_permalink_structure('/%postname%/%post_id%'); 
        
        /* method flush_rules untuk menyimpan pengaturan otomatis melalui perintah tersebut */
		
        $wp_rewrite->flush_rules();
    }
    
    /**
	 * @cetakpdf fungsi untuk membuat dokumen pdf penawaran (Quotation) dan penagihan (invoice)
	 * proses pembuatan dokumen pdf dibantu oleh library tambahan fpdf.php. terdapat pada
	 * ./memberzone/helper/pdf/fpdf.php
	 **/
	
	function cetakpdf(){
		/**
		 * pemeriksaan kondisi apakah isi variable 
		 * GET 'di' bernilai kode dokumen penawaran atau penagihan
		 * GET 'di' bernilai record id tabel wp_list_quo
		 **/
	if (isset($_GET['di']) && isset($_GET['id'])) {
			
			/**
			 * @$idq
			 * mengkonversi variable GET 'id' yang terenkripsi 
			 * menjadi data yang dapat terbaca satu karakter 
			 * untuk pemeriksaan proses percetakan dokumen pdf
			**/
			$idq=decrypt_url($_GET['id']); 
			
			/**
			 * @$data variable yang menampung data larik dengan parameter
			 * pertama 	: record id tabel wp_list_quo
			 * kedua	: jika terisi maka menandakan data terkait dengan pengguna yang sedang aktif saat ini
			 * 			  jika kosong maka tidak terkait, artinya hal tersebut
			 * 			  diberikan untuk administrator yang membutuhkan semua data penawaran yang masuk,
			 * 			  tanpa ada filter siapa yang sedang melakukan penawaran.
			 **/
			$status	= getdata_bykoland_id('list_quo','status','id',$idq);
			if($status==11){
				$params	=array('page'=>'quo-cus-page','e'=>'9');
				wp_redirect(esc_url(admin_url('?').http_build_query($params, '', "&")));
				exit();
			}else{
				$data	= get_list_quoby_id($idq,'y');
				$pdf	=new PDf();
				/** 
				* @$pdf mendeklarasikan object baru dari class PDF
				* agar semua method class PDF dapat diakses dari luar.
				* 
				**/
				/**
				* mengkondisikan variable GET 'di'
				* untuk memisahkan apakah data saat ini merupakan golongan penawaran atau penagihan.
				**/
				$admin_ID			= Memberzone_Base::admin_user_ids();
				$data['admin_id']	= $admin_ID[0];
				switch ($_GET['di']) {
					case 'q': // simbol penawaran
						$pdf->masbondan_quo($data); // memanfaatkan method umum_quo untuk mencetak pdf penawaran
						break;
					case 'i': // simbol penagihan
						$data['subtotal']=getdata_bykoland_id('list_quo','memberzone-total-tagihan','id',$idq);
						$pdf->umum_inv($data); // memanfaatkan method umum_quo untuk mencetak pdf penagihan
						break;
				}
			}
			
		}
	}

	/**
	 * @detailbarang_save fungsi custom wordrpess untuk menyimpan postingan
	 * dijalankan pada action hook save_post.
	 * ketika penyimpanan postingan diproses maka fungsi ini akan dijalankan wordpress
	 * yang akan mengeksekusi setiap baris perintah detailbarang_save tersebut.
	 **/
	function detailbarang_save($post_id)
	{
        global $wpdb; //memuat library kontrol database
        $t1		     = $wpdb->prefix.'custom_meta_key'; // penggabungan nama tabel dengan prefix wordpress
		$query	     = "SELECT meta_key FROM $t1"; // perintah query sql menampilkan data tabel nama variable $t1
		
 		/**
		 * mengeksekusi query yang sudah dibuat pada baris sebelumnya,
		 * memberikan output hasil eksekusi menjadi data larik,
		 * yang ditampung pada varible $get_metakey .
		 **/
		$get_metakey = $wpdb->get_results($query, ARRAY_A ); 
        
        if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return; // pemeriksaan DOING_AUTOSAVE 
		
        if(!isset($_POST['detailbrg_nonce']) || !wp_verify_nonce($_POST['detailbrg_nonce'],'detailbrg_box_nonce')) return;
		
        if(!current_user_can('edit_post')) return; // jika pengguna saat ini tidak memiliki wewenang edit postingan.
		
        if (is_array($get_metakey)) { //jika isi variable $get_metakey merupakan larik agar tidak menyebabkan error ketika di foreach
			
            foreach ($get_metakey as $value) {
				
                if(isset($_POST[$value['meta_key']])){ // jika kunci variable post terdaftar dengan nama menggunakan record kolom 'meta_key'
					
					/**
					* menyimpan perubahan postingan yang sudah ditambahkan dengan custom field metabox
					**/
					update_post_meta($post_id,$value['meta_key'],wp_kses($_POST[$value['meta_key']],$allowed)); 
                }
            }
            
       }
	}
	
	/**
	 * @the_content_filter fungsi yang berjalan pada filter hook dengan parameter pertama 'the_content'
	 * setiap data postingan akan bertransformasi dengan perintah fungsi custom ini.
	 * 
	 **/
    
    function the_content_filter(){ 
		/**
		 * @$idpost menampung nilai method get_post_id yang diambil dari class Memberzone_Base
		 * yang akan menampilkan ID postingan. 
		 **/
		$idpost=Memberzone_Base::get_post_id();
		
		/**
		 * @$post_thumbnail_id menampung nilai dari fungsi get_post_thumbnail_id yang membutuhkan parameter
		 * ID postingan yang dapat diperoleh dari variable $idpost
		 **/
		$post_thumbnail_id 	= get_post_thumbnail_id($idpost);
		$postingan			= get_post($idpost);
		$product_name 		= Member_tambahan::Product_name($idpost);
		$cekbtnquo			= get_post_meta($idpost,'cekbtnquo',true);
		$get_opsi 			= getdata_bykoland_id('custom_meta_key','meta_key','meta_opsional',1);
		$new_content='';		
		(strlen($postingan->post_content)>255 && !is_single() && !is_page())? $new_content .=substr($postingan->post_content,0,255).'<a href="'.get_permalink().'" class="memberzone-button">Baca Selengkapnya</a>'  : $new_content .=$postingan->post_content;
		if (!is_page('Sistem Penawaran') || !is_page()) {       	
			if ($cekbtnquo==1):
				if (is_user_logged_in()):
					if (cekalmat()){
						$new_content .='<p style="color:#000;background:yellow;"><a href="'.esc_url(admin_url('/admin.php?page=profil-page')).'">';
						$new_content .='<strong>Lengkapi Data pribadi</strong>';
						$new_content .='</a> untuk meminta penawaran produk <i>'.$product_name.'</i></p>';
					}else{
						add_thickbox();
						$toshow='formtawar';
						ob_start();				
						include MEMBERZONE_UI.'templates/memberzone-ui-thickbox.php';
						$new_content .=ob_get_clean();		
					}
				else:
					add_thickbox();
					$toshow='login';
					ob_start();				
					include MEMBERZONE_UI.'templates/memberzone-ui-thickbox.php';
					$new_content .=ob_get_clean();
				endif;
			$new_content .='<br/>';
			endif;
        }
        return $new_content;
    }

    function add_meta_label(){
        $allpost=$_POST;
        $t4='custom_meta_opsional';
        $t1='custom_meta_key';
        if (isset($_GET['b']) && !cekosong($allpost)) {
            switch ($_GET['b']) {
                case '1':
                    $dataopsi=array('meta_key'=>str_replace(' ', '_', $_POST['label']),'meta_label'=>$_POST['label'],'meta_type'=>$_POST['type'],'meta_max_val'=>25,'meta_opsional'=>$_POST['meta_opsional']);
                    insert_data($t1,$dataopsi);
                    break;
                case '2':
                    $dataopsi=array('meta_key'=>$_POST['mkey'],'opsional'=>$_POST['opsi']);
                    insert_data($t4,$dataopsi);
                    break;
                case '3':
                    delete_data($t1,array('id'=>$_POST['id']));
                    break;
                default:
                    return false;
                    break;
            }
            
        }else if (isset($_GET['d']) && !cekosong($_GET['d'])) {
            $idne=sanitize_text_field($_GET['d']);
            $val=explode('-', $idne);
            delete_data($t1,array('id'=>$val[0]));
            delete_data($t4,array('meta_key'=>$val[1]));
        }
    }
	
	//remove wp admin bar
	function remove_admin_bar() {
	if (!current_user_can('administrator') && !current_user_can( 'manage_options' )) {
		show_admin_bar(false);
		}
	}
	
	function pesan_setelahregister($message){
		if (strpos($message, 'Register') !== FALSE) {
			$newMessage = "Hallo! Terima kasih telah mencoba bergabung bersama kami di<strong>".get_bloginfo('name').'</strong>';
			return '<p class="message register">' . $newMessage . '</p>';
		}
		else {
			return $message;
		}
	}
	
	function page_about_quo(){
		 ob_start();         
        include MEMBERZONE_UI . 'templates/memberzone-ui-about-quo.php';
        $profilpage = ob_get_clean();
        $post = array(          
                'post_content' =>$profilpage,
                'post_name' =>'Tentang Penawaran',
                'post_status' => 'publish',
                'post_title' => 'Sistem Penawaran',
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
                'post_type' => 'page'
        );
        if (!get_page_by_title('Sistem Penawaran')) {
			wp_insert_post($post);   
        }else{
            $page = get_page_by_title( 'Sistem Penawaran' );
            $post['ID']=$page->ID;
            wp_update_post($post);
        }
	}
	
    function submenu_post_opsional(){
            add_submenu_page('edit.php','Buat Opsional','Buat Opsional','manage_options','opsional-page',array(__CLASS__,'submenu_post_opsional_callback'));        //
    }
    function submenu_post_opsional_callback(){

		$thead		= array('Jadikan Opsional?','ID label','Label','Jenis Opsional','Panjang Karakter','Aksi');
		$tbody		= get_data_byid('custom_meta_key','meta_type_id',1);
		$select		= array('checkbox'=>'Checkbox','select'=>'Dropdown','number'=>'Number','radio'=>'Radio Button','text'=>'text','textarea'=>'textarea');
		$checked	='checked';
		$op_status	='Ya';
		$val_meta_type_id=1;
		$output	='';
		wp_nonce_field('detailbrg_box_nonce','detailbrg_nonce');
		add_thickbox();
		$toshow		='add_metabox';
		$label		='Tambal Opsional';
		
		ob_start();				
		include MEMBERZONE_UI.'templates/memberzone-ui-thickbox.php';
		$addmetabox	= ob_get_clean();
		$output	.=$addmetabox;
		$output	.='<table class="wp-list-table widefat fixed striped posts">';
		$output	.='<thead><tr>';
		foreach ($thead as $head) {
			$output .='<th>'.$head.'</th>';
		}
		$output .='</tr></thead><tbody>';
		$output .='<tr>';
		foreach($tbody as $key =>  $value) {
			$larik_metakey	=array('cekbtnquo','brghrg','brgdiskon','met_dis','hrgp','product_name');
			
			(in_array($value['meta_key'],$larik_metakey))? $style ='style="display:none"' : $style ='';
			
			($value['meta_type']===$ksel)? $dpilih= 'selected': $dpilih='';
			(isset($_GET['cm']) && $value['id']==decrypt_url($_GET['cm']))? $yellow_edit='style="background-color:#ffff00;"' : $yellow_edit='';
			
			if ($value['meta_opsional']==0) {
				$op_status ='Tidak'; $checked='';
			}
			$output .='<td><p class="p-mo-'.$value['id'].'">'.$op_status.'</p><input type="checkbox" class="mo-'.$value['id'].'" style="display:none;" '.$checked.'/></td>';
			$output .='<td>'.$value['meta_key'].'</td>';
			$output .='<td><p class="p-ml-'.$value['id'].'" '.$yellow_edit.'>'.$value['meta_label'].'</p>';
			$output .='<input type="text" style="display:none;" class="ml-'.$value['id'].'"/></td>';
			$output .='<td><p class="p-mt-'.$value['id'].'">'.$value['meta_type'].'</p>';
            $output .='<select style="display:none;" class="mt-'.$value['id'].'">';
			foreach($select as $ksel => $valsel) {
					$output .='<option value="'.$ksel.'" '.$dpilih.'>'.$valsel.'</option>';
			}
			$output .='</select></td>';
			$output .='<td><p class="p-mmx-'.$value['id'].'">'.$value['meta_max_val'].'</p>';
			$output .='<input type="number" style="display:none;" class="mmx-'.$value['id'].'" id="txtboxToFilter"/></td>';
			$output .='<td>';
			if (strlen($style)>0) {
				$output .='<span class="memberzone-alert notice">Tidak dapat diubah :(</span>';
			}
			$output	.='<button type="button" class="btn-edit-metabox" id="'.$value['id'].'" '.$style.'>';
			$output .='<span class="dashicons dashicons-edit"></span ></button>';
			$output .='<button type="button" class="btn-'.$value['id'].' btn-upd-metabox" id="'.$value['id'].'" style="display:none;">';
			$output .='<span class="dashicons dashicons-yes"></span ></button>';
			$output .='<button type="button" class="btn-del-opt" id="'.$value['id'].'" '.$style.'><span class="dashicons dashicons-trash"></span ></button>';
			$output .='</td></tr>';
		}
		echo $output;
	}
	function hapus_menu_dashboard_kecuali_admin() {
		if (current_user_can('level_10')) {
			return;
		}else{
			global $menu, $submenu, $user_ID;
			$the_user = new WP_User($user_ID);
			reset($menu); $page = key($menu);
			while ((__('Dashboard') != $menu[$page][0]) && next($menu))
				$page = key($menu);
				if (__('Dashboard') == $menu[$page][0]) unset($menu[$page]);
					reset($menu); $page = key($menu);			
					while (!$the_user->has_cap($menu[$page][1]) && next($menu))
						$page = key($menu);
					if (preg_match('#wp-admin/?(index.php)?$#',$_SERVER['REQUEST_URI']) && ('index.php' != $menu[$page][2]))
						wp_redirect(get_option('siteurl') . '/wp-admin/post-new.php');
		}
	}
	//menambahkan menu bar penawaran
	function toolbar_link_to_qoutation( $wp_admin_bar ) {
		if (get_userrole()==='subscriber') {
			$args = array(
				'id'    => 'Penawaranku',
				'title' => 'Daftar Penawaranku',
				'href'  =>esc_url(admin_url('admin.php?page=request-page')) ,
				'meta'  => array( 'class' => 'my-req-quo' )
			);
			$wp_admin_bar->add_node( $args );
		}
	}
	
	function memberzone_cus_request(){
		add_menu_page('Penawaranku', 'Data Penawaran', 'subscriber', 'request-page', array(__CLASS__,'memberzone_cus_request_page'),'dashicons-list-view');
	}

	function memberzone_cus_request_page(){ 
		$user_ID 	= get_current_user_id();
		$listquo 	= get_where('list_quo',array('idcus'=>$user_ID),'y');
		if ($listquo) {
			$new_content ='<table>';
			foreach($listquo as $valquo):
				echo load_thickbox('confirmpay');
				
				
				$noequo				= $valquo['id'].'-'.$valquo['noquo'];
				$noid				= $valquo['id'].'-'.$valquo['noquo'];
				$product_name 		= Member_tambahan::Product_name($valquo['memberzone-penawaran-produk']);

				$sisane				= getdata_bykoland_id('pay','sisa','noquo',$noequo);
				$tglkonfirm			= getdata_bykoland_id('pay','time','noquo',$noequo);
				$nom				= getdata_bykoland_id('pay','nominal','noquo',$noequo);
				$stspay				= getdata_bykoland_id('pay','status','noquo',$noequo);
				$recent_norek		= getdata_bykoland_id('pay','norek','noquo',$noequo);
				$recent_nabank		= getdata_bykoland_id('pay','bank','noquo',$noequo);
				$recent_anbank		= getdata_bykoland_id('pay','an_rek','noquo',$noequo);
				$nom_sisa			= getdata_bykoland_id('pay','sisa','noquo',$noequo);
				$pay_status			= getdata_bykoland_id('pay','status','noquo',$noequo);
				$pay_nominal		= getdata_bykoland_id('pay','nominal','noquo',$noequo);
				$total_terbayar		= getdata_bykoland_id('pay','total_terbayar','noquo',$noequo);

				$no_resi			= getdata_bykoland_id('log_noresi','no_resi','noquo',$valquo['id']);
				$status_reqresi		= getdata_bykoland_id('log_noresi','status','noquo',$valquo['id']);
				$kurir_nama			= getdata_bykoland_id('log_noresi','kurir_nama','noquo',$valquo['id']);
				$kurir_link_web		= getdata_bykoland_id('log_noresi','kurir_link_web','noquo',$valquo['id']);
				$msg_rejected		= getdata_bykoland_id('pesan','pesan','id_quo',$valquo['id']);
				$exid				= explode('-',$valquo['id']);
				$total_tagihan		= $valquo['memberzone-total-tagihan'];
				$lengthbill			= strlen($total_tagihan);

				$telah_bayar 		= intval($total_tagihan-$total_terbayar);
				$len_nom_sisa		= strlen($nom_sisa);
				$listrek			= get_alldata('bank_toko');
				$len				= strlen($sisane); 
				$lentgl				= strlen($tglkonfirm)-1;
				$ceksudahpay		= getdata_bykoland_id('pay','id','noquo',$noequo);
				($sisane >0)? $sisaview=$sisane : $sisaview=substr($sisane,1,$len);
				$image = wp_get_attachment_image_src( get_post_thumbnail_id($valquo['memberzone-penawaran-produk']), 'single-post-thumbnail' );

				switch ($valquo['status']) {

					case 0:
						$list_title		=$product_name;
						$list_time		='';
						$list_status	='Admin belum acc';
						$list_content 	='<h3>informasi penawaran  :  <br></h3>';
						$list_content 	.='Jumlah Permintaan   = '.$valquo['memberzone-penawaran-jumlah'].' <br>';
						$list_content 	.='Keterangan Opsional = '.$valquo['memberzone-penawaran-opsional'];
						$list_action	='';
						break;					
					case 1:
						$list_title	 	='No Invoice  '.$valquo['noquo'];
						$list_time	 	='Batas pelunasan sampai dengan '.$valquo['exp_time'];
						$list_status 	='BELUM terbayar';
						$list_content	='Terima kasih telah bersedia menggunakan produk kami <br/>';
						$list_content	.='Kami telah mengirimkan tagihan melalui email, silahkan diperiksa dengan seksama.<br/>';
						$list_content	.='anda bisa mengunduh dokumen tagihan dengan menekan tombol unduh dibawah ini <br/>';
						$list_content	.='Untuk informasi lebih lanjut silahkan Hub. <a href="mailto:'.get_option( 'admin_email' ).'"><strong>email: '.get_option( 'admin_email' ).'</strong></a>';				
						$list_action 	='<ul id="memberzone-list-menu-userpage">';
						$list_action 	.='<li><a href="'.esc_url(admin_url('?di=i&id='.encrypt_url($valquo['id']))).'&u=1" class="button button-primary">unduh</a></li>';
						$list_action	.='<li>';
										add_thickbox();
										$toshow='confirmpay';
										ob_start();				
										include MEMBERZONE_UI.'templates/memberzone-ui-thickbox.php';
						$list_action 	.=ob_get_clean();
						$list_action	.='</li>';
						$list_action 	.='</ul>';
						break;
					case 2:
						$list_title		 ='No Quotation #'.$valquo['noquo'];
						$list_time		 ='Batas Penawaran sampai dengan '.$valquo['exp_time'];
						$list_status	 ='Menyetujui';
						$list_content 	 ='Kami telah mengirimkan penawaran melalui email, silahkan diperiksa dengan seksama.<br/> jika tidak memungkin untuk memeriksa email, ';
						$list_content   .='anda bisa mengunduh dokumen penawaran dengan menekan tombol unduh dibawah ini';
						$list_content 	.='<i><u>Catatan : jika anda menekan tombol Menyetujui maka anda telah menerima produk yang kami tawaran dan akan diproses lebih lanjut untuk kamu berikan bukti tagihan</u></i>';
						$list_action 	 ='<ul id="memberzone-list-menu-userpage">';
						$list_action 	.='<li><a href="?ac='.md5($valquo['id']).'" class="button button-primary">Menyetujui</a></li>';
						$list_action 	.='<li><a href="'.esc_url(admin_url('?di=q&id='.encrypt_url($valquo['id']))).'&u=1" class="memberzone-button ">unduh</a></li>';
						$list_action 	.='</ul>';
						break;
					case 3:
						$list_title='No Quotation #'.$valquo['noquo'];
						$list_time='';
						$list_status='Menunggu konfirmasi';
						$list_content ='Terima kasih atas kepercayaan anda untuk menggunakan produk kami.Tunggu beberapa saat konfirmasi dari admin';
						$list_action ='';
						break;
					/*case 4:
						$list_status	='Finish';
						$list_title		='No Quotation #'.$valquo['noquo'];
						$list_content 	='PROSES Penawaran sudah selesai sampai tahap akhir, secara otomatis jika anda sudah memberikan review maka data dihapus sementara, jika hendak memulihkan data, silahkan hubungi email admin ini <a href="mailto:'.get_option('admin_email').'" class="memberzone-button-primary memberzone-width-100">'.get_option('admin_email').'</a>';
						break;*/
					case 5:
						$list_title		='No Invoice  '.$valquo['noquo'];
						if ($stspay) {
							switch ($stspay) {
								case 5:
									$list_status .='terhutang';
									if(substr($tglkonfirm,-1,1)=='y'):
										$list_content ='pembayaran anda sebesar Rp <strong>'.$nom.'</strong> telah dikonfirmasi pada '.substr($tglkonfirm,0,$lentgl);
									else:
										$list_content ='Terima kasih atas pembayaran yang anda lakukan, Mohon kesabarannya.<br/>untuk menunggu konfirmasi penerimaan pembayaran dari admin.';
									endif;
										$list_content .='<div class="memberzone-alert-box warning">';
										$list_content .='<span id="memberzone-label">Total tagihan</span>&nbspRp<h3><strong>'.$valquo['memberzone-total-tagihan'].'</strong></h3><br/>';
										$list_content .='<span id="memberzone-label">Total pembayaran</span>&nbspRp<h3><strong>'.intval($valquo['memberzone-total-tagihan']-$sisaview).'</strong></h3><br/>';
										$list_content .='<span id="memberzone-label">Sisa tagihan</span>&nbspRp <h3><strong>'.$sisaview.'</strong></h3><br/>';
										$list_content .='</div>';
										$list_action ='';
								break;
								case 6:
									$list_status='LUNAS [belum terkonfirmasi]';
									if(substr($tglkonfirm,-1,1)=='y'){
										$list_status='LUNAS [terkonfirmasi]';
										$list_content ='Selamat pelunasan anda telah dikonfirmasi admin, anda bisa mengunduh invoice pelunasan dengan cara mengklik tombol <strong>unduh</strong> dibawah ini.<br/>';
										$list_action ='<a href="'.esc_url(admin_url('?di=i&id='.encrypt_url($valquo['id']))).'&u=1" class="button button-primary">unduh</a>';
										if ($status_reqresi) {
											switch ($status_reqresi) {
											case 1:
												$list_content .='<h4>[PENDING] Permintaan No. Resi</h4>';
											break;
											case 2:
												$list_content .='<h3>[SUKSES] Agen Kurir : <strong style="color:#000;background:yellow;">'.$kurir_nama.'</strong> No. Resi Anda : <a href="'.$kurir_link_web.'" class="memberzone-button btn-copy-text" target="blank">'.$no_resi.'</a></h3>';
												$list_action  ='<div class="test-form-'.$valquo['id'].'" style="display:none;">';
												$list_action  .='<textarea class="regular-text testi-text-'.$valquo['id'].'" rows="4" cols="50" placeholder="Tuliskan review terkait produk yang diterima"></textarea>';
												$list_action  .='<input type="hidden" class="regular-text product-text-'.$valquo['id'].'" value="'.$valquo['memberzone-penawaran-produk'].'" />';
												
												$list_action  .='<button type="button" class="memberzone-button memberzone-button-primary btn-review-produk" id="'.$valquo['id'].'">Submit review</button>';
												$list_action  .='</div>';			
												if (cekosong($valquo['review_id'])) {
													$list_action  .='<a href="#" class="memberzone-button memberzone-primary btn-recieved-product" id="'.$valquo['id'].'">konfirmasi penerimaan barang</a>';
												}else{
													$list_action  .='<button type="button" class="memberzone-button error btn-remove-quo" id="'.$valquo['id'].'" >hapus dari riwayat penawaran</button>';
												}
											break;													
											}
										}else{
											$list_action .='<a href="#" class="memberzone-button btn-req-resi" id="'.memberzone_enkrip($valquo['id']).'">Minta No. Resi</a>';
										}
									}else{
										$list_content .='Terima kasih atas pelunasan yang anda lakukan, Mohon kesabarannya.<br/>untuk menunggu konfirmasi penerimaan pelunasan dari admin.<br/>';
										$list_content .='<u>ketika sudah terkonfirmasi maka anda akan mendapatkan invoice pelunasan </u><br/>';
									}
								break;
								case 7:
									$list_status	='pembayaran ditolak';
									$list_content 	='konfirmasi pembayaran tidak valid, silahkan tekan tombol dibawah ini untuk konfirmasi ulang pembayaran';
										add_thickbox();
										$toshow='confirmpay';
										ob_start();				
										include MEMBERZONE_UI.'templates/memberzone-ui-thickbox.php';
									$list_action 	=ob_get_clean();
								break;
							}
							if ((substr($tglkonfirm,-1,1)=='y') && ($ceksudahpay) && ($sisane < 0)) {
								add_thickbox();
										$toshow='confirmpay';
										ob_start();				
										include MEMBERZONE_UI.'templates/memberzone-ui-thickbox.php';
								$list_action 	.=ob_get_clean();
							}
						}else{
							$params 		= array('page'=>'request-page','rq'=>encrypt_url($valquo['id']));
							$list_content 	='Maaf, Transaksi tidak dapat dilanjutkan. silahkan tekan tombol dibawah ini untuk memuat ulang data penawaran sebelumnya';
							$list_content	.='<br/><strong>setelah proses memuat ulang data penawaran berhasil anda akan mendapatkan No. Quotation baru</strong>';
							$list_action	='<a href="'.esc_url(admin_url('?').http_build_query($params, '', "&")).'" class="memberzone-button memberzone-button-primary memberzone-width-100">Muat ulang data penawaran</a>';
						}
					break;
					case 11:
						$list_status	='DITOLAK';
						$list_title		='[GAGAL] Pengajuan Penawaran produk '.$product_name;
						$list_content	='<strong> Pesan penolakan penawaran dari Admin : </strong>';
						$list_content	.= '<i>"'.$msg_rejected.'"</i>';
						$list_action	='<a href="#" class="memberzone-button memberzone-button-primary memberzone-width-50 btn-yes-reject" id="'.memberzone_enkrip($valquo['id']).'">Dimengerti</a>';
						break;
				}
				$new_content .='<tr>';
				
				ob_start();		
				include MEMBERZONE_UI.'templates/memberzone-ui-front-page.php';
				$new_content .='<td>';
				
				$new_content .= ob_get_clean();		
				$new_content .='</td>';
				$new_content .='</tr>';
			endforeach;
		$new_content .='</table>';
	}else{ 
			$new_content  = '<h1>Hello <strong>'.get_userdata($user_ID)->user_login.'</strong></h1>';
			$new_content .='<h3>Saat ini anda belum memiliki daftar produk penawaran</h3>';
			$new_content .='<a href="'.home_url('/').'" class="memberzone-button memberzone-button-primary memberzone-width-100"> Ayo Mulai jelajahi produk penawaran sekarang</a>';
	}
		echo $new_content;	
	}
	

	function admin_list_quo() {
		add_menu_page('Data penawaran', 'Data penawaran Pelanggan', 'manage_options', 'quo-cus-page', array(__class__,'quo_cus_call_back'),
		'dashicons-pressthis');
	}
	
	
	function quo_cus_call_back(){?>
		<h3>Daftar Penawaran Masuk</h3>
		<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<td>No. Quo</td>
				<td>Catatan</td>
				<td>Nama Produk</td>
				<td>Nama Pengguna</td>
				<td>Tanggal Dimulai</td>
				<td>Tanggal Berakhir</td>
				<td>Aksi</td>
			</tr>
		</thead>
		<tbody class="listdata">
		</tbody>
		</table>
	<?php }
	
	function admin_info_toko() {
		add_menu_page('kelengkapan surat', 'kelengkapan surat', 'manage_options', 'info-toko-page', array(__class__,'info_toko_call_back'),
		'dashicons-media-text');
	}
	function info_toko_call_back(){
		ob_start();
		include MEMBERZONE_UI.'templates/memberzone-ui-data-doc.php';
		$content=ob_get_clean();
		echo $content;
	}
	
	/**
	* @hapus_pemberitahuan_update_wp_for_user  untuk menghapus pemberitahuan perbaharuan versi wordpress.
	**/
	function hapus_pemberitahuan_update_wp_for_user(){
    	if (!current_user_can('update_core')) {
    		remove_action( 'admin_notices', 'update_nag', 3 );
    	}
    }
    /**
	 * @kolom_info_produk menambahkan field baru pada postingan sebagai detail informasi postingan produk.
	 * seperti data harga, diskon dan opsional untuk Pelanggan terkait spesifikasi produk yang tersedia.
	 **/
	function kolom_info_produk(){
		add_meta_box( 'detailbrg-box-id', 'Data Produk Penawaran (informasi produk yang akan ditawarkan)', array(__class__,'form_pengisian_info_produk'), 'post', 'normal', 'high' );
	}
	
	function form_pengisian_info_produk($post){    
		$get_metakey	= get_data_byid('custom_meta_key','meta_type_id',1); 
		(isset($_GET['action']) && isset($_GET['post']) && $_GET['action']=='edit' )? $kemburl='post.php?post='.$_GET['post'].'&action='.$_GET['action'] : $kemburl='post-new.php'; 
		wp_nonce_field('detailbrg_box_nonce','detailbrg_nonce');
		if ($get_metakey):
			$info_produk_page ='<p id="kemburl" style="display:none;">'.$kemburl.'</p>';
			$info_produk_page.='<div class="mzone-grid-container mzone-outline">';
			foreach($get_metakey as $value) {	
				$param_urls		= array('page'=>'opsional-page','cm'=>encrypt_url($value['id']));
				$get_opsional 	= get_data_byid('custom_meta_opsional','meta_key',$value['meta_key']);
				$input_val 		= get_post_meta($post->ID,$value['meta_key'],true);	
				$btn_edit_url	= esc_url(admin_url('/edit.php?')).http_build_query($param_urls, '', "&");
				ob_start();
				include MEMBERZONE_UI.'templates/memberzone-ui-metabox-post.php';
				$info_produk_page .=ob_get_clean();
			}
			$info_produk_page .='</div>';
		else:
			$info_produk_page ='<div class="memberzone-alert error">Error #001, Tidak dapat menampilkan custom detail produk :(</div>';
		endif;
		echo $info_produk_page;

	}

    function subscriber_profile_menu()
    {
    	add_menu_page('Profilku', 'Data Pribadi', 'subscriber', 'profil-page', array(__CLASS__,'identitas_tambahan_akun'));
	}

    /**
	 * @identitas_tambahan_akun menambahkan kolom pengisian identitas baru, pada informasi akun untuk kelengkapan
	 * dokumen penawaran dan penagihan.
	 **/
	function identitas_tambahan_akun(){
		$akun 			= wp_get_current_user();
		$userdata 		= get_userdata($akun->ID);
		$metakey_user	= array('nohp','fax','attn','cc','ancus','telpacus','acus','anbil','telpabil','abil','anship','telpaship','aship');
		$label 			= array('No. Telp/Handphone','No. Fax','Attendance','cc','Nama Penerima','No. Telp/Hp Pribadi','Alamat Pribadi','Atas Nama Penagihan','No. Telp/Hp Penagihan','Alamat Penagihan','Atas Nama Pengiriman','No. Telp/Hp Pengiriman','Alamat Pengiriman');
		$profile_page 	='<h3>Tekan tombol Enter untuk menyimpan perubahan</h3>';
		$profile_page  .='<form id="userakun" method="post" action="'.esc_url(home_url('?pf=ak&id='.encrypt_url($akun->ID))).'">';
		$profile_page	.='<div class="mzone-grid-container" >';
		foreach($metakey_user as $key => $val) {
			(cekosong(get_the_author_meta($val, $akun->ID)))? $txt_style =array('txtin'=>'style="display:block;"','txtp'=>'style="display:none;"') : $txt_style =array('txtin'=>'style="display:none;"','txtp'=>'style="display:block;"');
			$profile_page .='<div class="mzone-row">';

			$profile_page .='<div class="mzone-col-1">';
			$profile_page .='<strong>'.$label[$key].'</strong>';
			$profile_page .='</div>';

			$profile_page .='<div class="mzone-col-3" >';
				
					$profile_page .='<input type="text" name="'.$val.'" placeholder="Isikan '.$label[$key].'" id="txt-'.$val.'" class="regular-text input" '.$txt_style['txtin'].' />';
					$profile_page .='<p class="val-'.$val.'" '.$txt_style['txtp'].' >'.get_the_author_meta($val, $akun->ID).'</p>';				

			$profile_page .='</div>'; //mzone-half
			$profile_page .='<div class="mzone-col-1">';
			$profile_page .='<a href="#" class="link-edit"  id="'.$val.'"><i class="dashicons dashicons-edit"></i></a>';
			$profile_page .='</div>'; //mzone-row endded 
		}
		$profile_page .='</div>';
		$profile_page .='<span style="display:none;" id="fsubmit">userakun</span><input type="submit" style="display:none;"/></form>';
		echo $profile_page;
		
	}
	
	/**
	 * @simpan_identitas_tambahan_akun menyimpan kolom pengisian identitas tambahan,
	 * baik menambah dan memperbaharui data.
	 **/
	
	function simpan_identitas_tambahan_akun(){
        $user_ID =get_current_user_id();
        $larik=array('nohp','fax','attn','cc','ancus','telpacus','acus','anbil','telpabil','abil','anship','telpaship','aship');
        foreach ($larik as $key => $value) {
            update_user_meta( $user_ID,$value, sanitize_text_field( $_POST[$value] ) );
        }
	}
	/**
	 * @dashboard_instant_daftar_penawaran membuat tampilan instant pada menu beranda dashboard
	 * sebagai jalan pintas yang mudah.
	 **/
	function dashboard_instant_daftar_penawaran() {
		if ( is_user_logged_in() ) {
			$user_ID =get_current_user_id();
			$user = new WP_User( $user_ID );
			if ($user->caps['administrator']=='administrator') {
				global $wp_meta_boxes;
				wp_add_dashboard_widget('quotation_act_widget', 'Quotation Activity', array(__class__,'quo_dashboard_act'),'normal','high');
			}
		}
	}

	function quo_dashboard_act() {
		include MEMBERZONE_UI.'templates/memberzone-ui-listquo.php';
	}
    
    
	/**
	 * @pemberitahuan_sistem_penawaran informasi penting yang harus disampaikan kepada pengguna
	 * untuk mendukung kelancaran sistem penawaran. 
	 **/

	function pemberitahuan_sistem_penawaran() {
		if ( is_user_logged_in() ) {
			global $wp_meta_boxes;
			wp_add_dashboard_widget('memberzone_dash_notif', 'Memberzone Remainder', array(__class__,'dialog_pemberitahuan_sistem_penawaran'),'normal','high');
		}
	}
	
	/**
	 * @dialog_pemberitahuan_sistem_penawaran antarmuka untuk menyajikan informasi penting yang dibutuhkan sistem penawaran
	 * kepada pengguna.
	 **/
	
	function dialog_pemberitahuan_sistem_penawaran() {
		$msg ='<span class="memberzone-alert-box notice" >';
		switch (get_userrole()) {
			case 'subscriber':
				if (cekalmat()) { 	
					$msg .='<a href="'.esc_url(admin_url('/admin.php?page=profil-page')).'"><h1>Perbaharui Data Pribadi</h1></a>';
				}
				break;
			case 'administrator':
				if ( !get_option( 'users_can_register' ) ) { 	
					$msg .='<p>Checklist membership dimenu <strong>Settings->General</strong> untuk memudahkan pengunjung mendaftar sebagai MEMBER '.get_bloginfo('name').'</p>';
				}
				break;
		}
		$msg .='</span>';
		echo $msg;
	}

	function memberzone_admin_area_notice() {  
		$user 			= wp_get_current_user();
		$list_notif		= get_where('pesan',array('iduser'=>$user->ID));
		if ($list_notif) {
			foreach($list_notif  as $knotif => $valnotif) {
					$alert_msg  ='<div id="message" class="updated notice ceknotif is-dismissible">';
					$alert_msg	.='<p>'.$valnotif['pesan'].'</p><input type="hidden" class="notid" value="'.$valnotif['id'].'">';
					$alert_msg	.='</div>';
					echo $alert_msg;
			}
		}
		if(current_user_can('manage_options')){ 
			$listquo 	= get_alldata('list_quo');
			$param_urls	= array('page'=>'quo-cus-page');
			$who		='yang diajukan username =<strong>'.$user->user_login.'</strong>';
		}else{
			$listquo 	= get_where('list_quo',array('idcus'=>$user->ID));
			$param_urls = array('page'=>'request-page');
			$who		='yang anda ajukan';
		}
		if ($listquo) {
			foreach ($listquo as $key => $value) {
				$param_urls['prm']=encrypt_url($value['id']);
				if (!cekosong($value['exp_time']) && get_calculate_date($value['exp_time'])>=0 && $value['status']!=4) {
					$alert_msg  ='<div id="message" class="updated notice is-dismissible">';
					$alert_msg	.='<p>Penawaran No. '.$value['noquo'].' '.$who.' Sudah kadaluarsa . <a href="'.esc_url(admin_url('?')).http_build_query($param_urls, '', "&").'">Lihat Selengkapnya</a><button type="button" class="memberzone-button error btn-remove-quo" id="'.$value['id'].'">Hapus</button></p>';
					$alert_msg	.='<button type="button" class="notice-dismiss"><span class="screen-reader-text">Tutup pemberitahuan ini.</span></button></div>';
					echo $alert_msg;
				}
			}
			
		}
		
		
	}
	function admin_styles()
	{
		$enqueue ='<link rel="stylesheet" type="text/css" href="'.MEMBERZONE_AST . 'css/memberzone.css'.'" media="all"/>';
		echo $enqueue;
	}
	function admin_footer_js_script() { ?>
		<script src="<?php echo MEMBERZONE_AST . 'js/jquery.maxlength.js';?>"></script>
		<script src="<?php echo MEMBERZONE_AST . 'js/jquery.notification.js';?>"></script>
		<script src="<?php echo MEMBERZONE_AST . 'js/j-admin.js';?>"></script>
	<?php 
	}
}




