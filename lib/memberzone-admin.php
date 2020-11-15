<?php

class Memberzone_Admin {
	private
		$plugin_name,
		$plugin_version;
	
	public function __construct($plugin_name, $plugin_version) {
		$this->plugin_name    = $plugin_name;
		$this->plugin_version = $plugin_version;
	}
	
	public function create_option_menu() {
		add_options_page(
			'Administrasi ' . ucwords($this->plugin_name),
			ucwords($this->plugin_name),
			'manage_options',
			$this->plugin_name,array($this, 'create_option_page')
		);
	}
	
	public function create_option_page() {
		$sales_user_id			= get_current_user_id();
		$contacts_larik_data	= array('No','Type Kontak','Data Kontak','aksi');
		$list_contact			= get_data_byid('sales_contacts','sales_user_id',$sales_user_id); 
		$list_contact_type		= get_data_byid('custom_meta_key','meta_type_id',2);
		$contacts_thead 		= '';
		$no						= 0;
		$toshow					='add_metabox';
		$val_meta_type_id		= 2;
		$label					='<span class="dashicons dashicons-plus-alt"></span>';
		$contacts_tbody			= '<tr><td>'.$no.'</td>';
		foreach($contacts_larik_data as $k_ld =>$val_ld ) {
			$contacts_thead .='<td>';
			$contacts_thead .=$val_ld;
			$contacts_thead .='</td>';
		}
		$contacts_tbody .='<td><select class="regular-text contc-type">';
		
		if ($list_contact_type && is_array($list_contact_type) && count($list_contact_type ) > 0) {
			foreach($list_contact_type as $ktype => $vtype) {
				$contacts_tbody .='<option value="'.$vtype['id'].'">'.$vtype['meta_label'].'</option>';
			}
		}else{
			$contacts_tbody .='<option value="0">Belum tersedia</option>';
		}
		$contacts_tbody .='</select>';
		add_thickbox();
		ob_start();				
		include MEMBERZONE_UI.'templates/memberzone-ui-thickbox.php';
		$contacts_tbody	.=ob_get_clean();
		$contacts_tbody .='</td>';
		$contacts_tbody .='<td>';
		$contacts_tbody .='<input type="text" class="regular-text contc-val "/>';

		$contacts_tbody .='<input type="hidden" class="regular-text contc-hd-val" />';
		$contacts_tbody .='</td>';
		$contacts_tbody .='<td colspan="2">';
		$contacts_tbody .='<button type="button" class="memberzone-button btn-control-contact" id="insertdata">Tambahkan </button>';
		$contacts_tbody .='</td>';
		$contacts_tbody .='</tr>';
		if ($list_contact && is_array($list_contact) && count($list_contact)) {
			foreach($list_contact as $kcon => $vcon) {
				$no				 = $no+1;
				$contact_label	 = getdata_bykoland_id('custom_meta_key','meta_label','id',$vcon['sales_contact_id']);
				$contacts_tbody .='<tr>';
				$contacts_tbody .='<td>'.$no.'</td>';
				$contacts_tbody .='<td>'.$contact_label.'</td>';
				$contacts_tbody .='<td>'.$vcon['sales_contact_val'].'</td>';
				$contacts_tbody .='<td>';
				$contacts_tbody .='<button type="button" class="btn-edit-contact" id="'.$vcon['id'].'">';
				$contacts_tbody .='<span class="dashicons dashicons-edit"></span>';
				$contacts_tbody .='</button>';
				$contacts_tbody .='<button type="button" class="btn-del-contact" id="'.$vcon['id'].'">';
				$contacts_tbody .='<span class="dashicons dashicons-trash"></span>';
				$contacts_tbody .='</button>';
				$contacts_tbody .='</td>';
				$contacts_tbody .='</tr>';
				
			}
			
		}
		ob_start();
		include MEMBERZONE_UI . $this->plugin_name . '-ui-admin.php';
		$page = ob_get_clean();
		
		print $page;
	}
	
	public function register_settings() {
		register_setting(
			$this->plugin_name . '_options',
			$this->plugin_name . '_marketer'
		);
	}
}

/* Akhir dari berkas memberzone-admin.php */
