<?php

class Memberzone_Activator {
	public static function activate() {
		global $wpdb;
		$t1  = $wpdb->prefix.'custom_meta_key';
		$t2	 = $wpdb->prefix.'pesan';
		$t3  = $wpdb->prefix.'custom_meta_opsional';
		$t4  = $wpdb->prefix.'list_quo';
		$t6  = $wpdb->prefix.'pay';
		$t7  = $wpdb->prefix.'bank_toko';
		$t8  = $wpdb->prefix.'gambar';
		$t10 = $wpdb->prefix.'log_noresi';
		$t11 = $wpdb->prefix.'sales_contacts';
		$t12 = $wpdb->prefix.'custom_meta_type';
		
		
		$buattabel=array(
			"CREATE TABLE IF NOT EXISTS `$t1` (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `meta_type_id` BIGINT(20) NOT NULL,
          `meta_key` varchar(50) NOT NULL,
          `meta_label` varchar(125) NOT NULL,
          `meta_type` varchar(15) NOT NULL,
          `meta_max_val` int(9) NOT NULL,
          `meta_opsional` int(2) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
          ,
          "CREATE TABLE IF NOT EXISTS `$t3` (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `meta_key` varchar(50) NOT NULL,
          `opsional` varchar(125) NOT NULL,
          `class` varchar(50) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
          ,
          "CREATE TABLE IF NOT EXISTS `$t4` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`noquo` int(225) NOT NULL,
			`idcus` int(225) NOT NULL,
			`memberzone-penawaran-produk` int(225) NOT NULL,
			`memberzone-penawaran-jumlah` int(125) NOT NULL,
			`memberzone-penawaran-opsional` varchar(225) NOT NULL,
			`memberzone-total-tagihan` int(125) NOT NULL,
			`time` varchar(25) NOT NULL,
			`exp_time` varchar(25) NOT NULL,
			`status` int(2) DEFAULT NULL,
			`review_id` bigint(20) unsigned DEFAULT NULL,
			PRIMARY KEY (`id`)
          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
          ,
		  "CREATE TABLE IF NOT EXISTS `$t2` (
			  `id` bigint(20) NOT NULL AUTO_INCREMENT,
			  `id_quo` bigint(20) NOT NULL,
			  `iduser` int(225) NOT NULL,
			  `from` int(225) NOT NULL,
			  `pesan` varchar(225) NOT NULL,
			  `status` int(5) NOT NULL,
			  `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
		  ,
          "CREATE TABLE IF NOT EXISTS `$t6` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`iduser` int(225) NOT NULL,
			`noquo` varchar(225) NOT NULL,
			`bank` varchar(25) NOT NULL,
			`norek` varchar(25) NOT NULL,
			`an_rek` varchar(125) NOT NULL,
			`total_terbayar` int(125) NOT NULL,
			`nominal` int(75) NOT NULL,
			`berita_acara` varchar(125) NOT NULL,
			`sisa` int(125) NOT NULL,
			`status` int(5) NOT NULL,
			`time` varchar(25) NOT NULL,
			`idrektoko` int(225) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			,
          "CREATE TABLE IF NOT EXISTS `$t7` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`bank_nama` varchar(125) NOT NULL,
			`bank_an` varchar(125) NOT NULL,
			`bank_norek` varchar(25) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			,
          "CREATE TABLE IF NOT EXISTS `$t8` (
			`id` varchar(20) NOT NULL,
			`type` varchar(125) NOT NULL,
			`file` varchar(225) NOT NULL,
			`url` varchar(225) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			,
			"CREATE TABLE IF NOT EXISTS `$t9` (
			`id` bigint(20) NOT NULL AUTO_INCREMENT,
			`idcus` int(225) NOT NULL,
			`pakai_total` int(125) NOT NULL,
			`pakai_sekarang` int(125) NOT NULL,
			`sisa` int(125) NOT NULL,
			`total` int(125) NOT NULL,
			`waktu_masuk` varchar(25) NOT NULL,
			`waktu_pakai` varchar(25) NOT NULL,
			`status` varchar(10) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			,
			"CREATE TABLE IF NOT EXISTS `$t10`(
				`id` bigint(20) NOT NULL AUTO_INCREMENT,
				`noquo` bigint(20) NOT NULL,
				`kurir_nama` varchar(50) NOT NULL,
				`kurir_link_web` varchar(225) NOT NULL,
				`no_resi` varchar(50) NOT NULL,
				`waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`status` int(2) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			,
			"CREATE TABLE IF NOT EXISTS `$t11` (
				`id` bigint(20) NOT NULL AUTO_INCREMENT,
				`sales_user_id` bigint(20) NOT NULL,
				`sales_contact_id` bigint(20) NOT NULL,
				`sales_contact_val` varchar(125) NOT NULL,
				PRIMARY KEY (`id`),
				KEY `sales_user_id` (`sales_user_id`),
				KEY `sales_contact_id` (`sales_contact_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;"
			,
			"CREATE TABLE IF NOT EXISTS `$t12` (
				`id` bigint(20) NOT NULL AUTO_INCREMENT,
				`meta_type_name` varchar(125) NOT NULL,
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
			"
		);
		foreach($buattabel as $sql) {
			$wpdb->query($sql);
		}
		$data_custommeta=array(
			array(
				'meta_key'=>'cekbtnquo'
				,'meta_type_id'=>1
				,'meta_label'=>'Tampilkan Tombol Minta penawaran?'
				,'meta_type'=>'radio'
				,'meta_max_val'=>'5'
				,'meta_opsional'=>0
			)
			,array(
				'meta_key'=>'product_name'
				,'meta_type_id'=>1
				,'meta_label'=>'Nama Produk'
				,'meta_type'=>'text'
				,'meta_max_val'=>'225'
				,'meta_opsional'=>0
			)
			,array(
				'meta_key'=>'brghrg'
				,'meta_type_id'=>1
				,'meta_label'=>'Harga Normal'
				,'meta_type'=>'number'
				,'meta_max_val'=>'25'
				,'meta_opsional'=>0
			)
			,array(
				'meta_key'=>'hrgp'
				,'meta_type_id'=>1
				,'meta_label'=>'Harga promo'
				,'meta_type'=>'number'
				,'meta_max_val'=>'25'
				,'meta_opsional'=>0
			)
			,array(
				'meta_key'=>'brgdiskon'
				,'meta_type_id'=>1
				,'meta_label'=>'Diskon'
				,'meta_type'=>'number'
				,'meta_max_val'=>'25'
				,'meta_opsional'=>0
			)
			,array(
				'meta_key'=>'met_dis'
				,'meta_type_id'=>1
				,'meta_label'=>'Metode Diskon'
				,'meta_type'=>'radio'
				,'meta_max_val'=>'25'
				,'meta_opsional'=>0
			)
			,array(
				'meta_key'=>'sedia'
				,'meta_type_id'=>1
				,'meta_label'=>'Ketersediaan Barang'
				,'meta_type'=>'select'
				,'meta_max_val'=>'25'
				,'meta_opsional'=>0
			)
			,array(
				'meta_key'=>'noteprod'
				,'meta_type_id'=>1
				,'meta_label'=>'Catatan Produk'
				,'meta_type'=>'textarea'
				,'meta_max_val'=>'125'
				,'meta_opsional'=>0
			)
		);
		foreach($data_custommeta as $key => $value) {
			insert_data('custom_meta_key',$value);
		}
		$data_valmeta=array(
			array(
				'meta_key'=>'cekbtnquo'
				,'opsional'=>'ya'
				,'class'=>''
			)
			,array(
				'meta_key'=>'cekbtnquo'
				,'opsional'=>'tidak'
				,'class'=>''
			)
			,array(
				'meta_key'=>'sedia'
				,'opsional'=>'Tersedia'
				,'class'=>''
			)
			,array(
				'meta_key'=>'sedia'
				,'opsional'=>'Terbatas'
				,'class'=>''
			)
			,array(
				'meta_key'=>'sedia'
				,'opsional'=>'Habis'
				,'class'=>''
			)
			,array(
				'meta_key'=>'met_dis'
				,'opsional'=>'Nominal'
				,'class'=>'nom'
			)
			,array(
				'meta_key'=>'met_dis'
				,'opsional'=>'Persentase(%)'
				,'class'=>'pers'
			)
		);
		foreach($data_valmeta as $k => $v){
			insert_data('custom_meta_opsional',$v);		
		}
		$data_gambar=array(
			array(
				'id'=>'g1'
				,'type'=>'Header dokumen penawaran'
			)
			,
			array(
				'id'=>'g2'
				,'type'=>'Cap toko'
			)
			,
			array(
				'id'=>'g3'
				,'type'=>'Logo toko'
			)
			,
			array(
				'id'=>'g4'
				,'type'=>'Tanda tangan'
			)
			
		);
		foreach($data_gambar as $kg=>$valg) {
			insert_data('gambar',$valg);
		}
		$data_meta_type =array(
			array(
				'id'=>1,
				'meta_type_name'=>'produk'
			),
			array(
				'id'=>2,
				'meta_type_name'=>'contact'
			)
		);
		foreach($data_meta_type as $mtype=>$val_mtype) {
			insert_data('custom_meta_type',$val_mtype);
		}
	}
}

/* Akhir dari berkas memberzone-activator.php */
