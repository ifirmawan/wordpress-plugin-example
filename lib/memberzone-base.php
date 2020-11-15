<?php
/**
 * library dasar untuk menampung aski hook dan memuat dependency
 * 
 * 
 * 
 * */

class Memberzone_Base {
	protected
		$loader,
		$plugin_name,
		$plugin_version;
	
	public function __construct() {
		$this->plugin_name    = 'memberzone';
		$this->plugin_version = '1.0.1';
		
		$this->load_dependencies();
		$this->define_hooks();
	}
	
	private function define_hooks() {
		$admin_hooks = new Memberzone_Admin($this->plugin_name, $this->plugin_version);
		$this->loader->add_action('wp_enqueue_scripts', $this, 'enqueue_styles' );
		$this->loader->add_action('admin_menu', $admin_hooks, 'create_option_menu');
		$this->loader->add_action('admin_init', $admin_hooks, 'register_settings');
		$this->loader->add_action('admin_head','Member_tambahan','hapus_pemberitahuan_update_wp_for_user', 1 );
		$this->loader->add_action('admin_head', 'Member_tambahan', 'admin_styles' );
		$this->loader->add_action('admin_footer', 'Member_tambahan', 'admin_footer_js_script' );
		$this->loader->add_action('admin_notices','Member_tambahan','memberzone_admin_area_notice');
		$this->loader->add_action('widgets_init', 'Memberzone_Module_Penawaran', 'register_widgets');
		$this->loader->add_action('admin_post_memberzone_penawaran', 'Memberzone_Module_Penawaran', 'processing');
		$this->loader->add_action('plugins_laoded','Member_tambahan', 'get_instance');
		$this->loader->add_action('init','Member_tambahan', 'set_permalink');		
		$this->loader->add_action('init','Member_tambahan', 'cetakpdf');		
		$this->loader->add_action('init','Member_tambahan', 'fungsi_init');		
		$this->loader->add_action('save_post','Member_tambahan', 'detailbarang_save');
		$this->loader->add_action('the_content','Member_tambahan', 'the_content_filter');
		$this->loader->add_action('init', 'Member_tambahan', 'add_meta_label');
		$this->loader->add_action('init', 'Member_tambahan', 'allow_html');
		$this->loader->add_action('dashboard_glance_items','Member_tambahan','count_req_quo');	
		$this->loader->add_action('login_message','Member_tambahan', 'pesan_setelahregister');
		$this->loader->add_action('init','Member_tambahan','page_about_quo');
		$this->loader->add_action( 'add_meta_boxes','Member_tambahan','kolom_info_produk' );
		$this->loader->add_action('admin_menu','Member_tambahan','submenu_post_opsional'); 
		$this->loader->add_action('admin_menu','Member_tambahan','admin_list_quo'); 
		$this->loader->add_action('admin_menu','Member_tambahan','admin_info_toko'); 
		$this->loader->add_action('admin_menu','Member_tambahan','memberzone_cus_request'); 
		$this->loader->add_action('admin_menu','Member_tambahan','hapus_menu_dashboard_kecuali_admin'); 
		$this->loader->add_action('admin_menu','Member_tambahan','subscriber_profile_menu'); 
		$this->loader->add_action('show_user_profile','Member_tambahan','identitas_tambahan_akun');
		$this->loader->add_action('edit_user_profile','Member_tambahan','identitas_tambahan_akun');
		$this->loader->add_action('personal_options_update','Member_tambahan','simpan_identitas_tambahan_akun');
		$this->loader->add_action('edit_user_profile_update','Member_tambahan','simpan_identitas_tambahan_akun');
		$this->loader->add_action('admin_bar_menu','Member_tambahan','toolbar_link_to_qoutation', 999 );
		$this->loader->add_filter('comments_open','Member_tambahan','disable_comment_onpage'); 
		   
	}
	
	protected function get_plugin_name() {
		return $this->plugin_name;
	}
	
	protected function get_plugin_version() {
		return $this->plugin_version;
	}
	
	protected function load_dependencies() {
		require_once MEMBERZONE_LIB . 'memberzone-loader.php';
		require_once MEMBERZONE_LIB . 'memberzone-admin.php';
		require_once MEMBERZONE_HELP . 'pdf/fpdf.php';
		require_once MEMBERZONE_MOD . 'memberzone-mod-penawaran.php';
		require_once MEMBERZONE_MOD . 'memberzone-mod-tambahan.php';
		require_once MEMBERZONE_MOD . 'memberzone-mod-fpdf.php';
		require_once MEMBERZONE_MOD . 'memberzone-mod-functions.php';
		$this->loader = new Memberzone_Loader();
	}
	
	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_name, MEMBERZONE_AST . 'css/memberzone.css', array(), $this->plugin_version, 'all');
		
		
	}
	
	
	public function get_member_info() {
		$data = wp_get_current_user();
		return $data;
	}
	

	
	public function get_post_id() {
		global $wp_query;	
		return $wp_query->post->ID;
	}
	function get_content($url){
     	$data = curl_init();
     	curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
     	curl_setopt($data, CURLOPT_URL, $url);
     	$hasil = curl_exec($data);
     	curl_close($data);
     	return $hasil;
	}
	
		
	public function post_email($to, $sender, $email, $subject, $message) {
		$headers  = 'MIME-Version: 1.0' . "\r\n";
    	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: ' . $sender . ' <' . $email . '>' . "\r\n";
		$subject = $subject;
		wp_mail($to, $subject, $message, $headers);
	}

	
	public function post_email_att($to,$subject, $message,$post_id,$doc_id) {
		ob_start();
		$namafile = 'Quotation No.'.$post_id.'.pdf';
		$fileType = "application/x-pdf";
		$infoweb=get_option('memberzone_marketer');
		$headers ='From: ' . $infoweb['email'] . ' <' . bloginfo('name') . '>' . "\r\n";
   		$fileContent = Memberzone_Base::get_content(site_url('/pdf/?di='.$doc_id.'&id='.encrypt_url($post_id)));   
   		$data = chunk_split(base64_encode($fileContent));
		
   		$semi_rand = md5(time());
   		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
   		$headers .= "MIME-Version: 1.0\n" .
              "Content-Type: multipart/mixed;\n" .
              " boundary=\"{$mime_boundary}\"";
   		$pesan = "This is a multi-part message in MIME format.\n\n" .
            "--{$mime_boundary}\n" .
            "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
            "Content-Transfer-Encoding: 7bit\n\n" .
            $message . "\n\n";
   		$data = chunk_split(base64_encode($fileContent));
   		$pesan .= "--{$mime_boundary}\n" .
             "Content-Type: {$fileType};\n" .
             " name=\"{$namafile}\"\n" .
             "Content-Disposition: attachment;\n" .
             " filename=\"{$namafile}\"\n" .
             "Content-Transfer-Encoding: base64\n\n" .
             $data . "\n\n" .
             "--{$mime_boundary}--\n"; 
        mail($to, $subject, $pesan, $headers);
	}
	
	public function run() {
		$this->loader->load();
	}
	
	public function admin_user_ids(){
    //Grab wp DB
    global $wpdb;
    //Get all users in the DB
    $wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");
    //Blank array
    $adminArray = array();
    //Loop through all users
    foreach ( $wp_user_search as $userid ) {
			//Current user ID we are looping through
			$curID = $userid->ID;
			//Grab the user info of current ID
			$curuser = get_userdata($curID);
			//Current user level
			$user_level = $curuser->user_level;
			//Only look for admins
			if($user_level >= 8){//levels 8, 9 and 10 are admin
				//Push user ID into array
				$adminArray[] = $curID;
			}
		}
		return $adminArray;
	}
}

/* Akhir dari berkas base.php */
