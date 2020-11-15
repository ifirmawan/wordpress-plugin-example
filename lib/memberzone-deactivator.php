<?php

class Memberzone_Deactivator {
	public static function deactivate() {
		global $wpdb;
		$tabel=array('custom_meta_key','custom_meta_opsional','list_quo','pay','bank_toko','gambar','log_noresi','sales_contacts','custom_meta_type','pesan');
		foreach($tabel as $value) {
			$sql="DROP TABLE ".$wpdb->prefix.$value;
			$wpdb->query($sql);
		}
		if (get_page_by_title('Sistem Penawaran')) {
			$sisquo=get_page_by_title('Sistem Penawaran');
			wp_delete_post($sisquo->ID,true);
		}		
	}
	
}

/* Akhir dari berkas memberzone-activator.php */
