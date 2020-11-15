<?php

/**
 * Plugin Name: Memberzone
 * Description: Fungsionalitas tambahan untuk member Digital Meter Indonesia.
 * Version: 1.0.0
 * Author: Java Multi Mandiri
 * Author URI: http://jvm.co.id
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* File tidak boleh diakses publik secara langsung. */
if (!defined('ABSPATH')) {
exit();
}

date_default_timezone_set('Asia/Jakarta');
define('MEMBERZONE_AST', plugin_dir_url(__FILE__) . 'ast/');
define('MEMBERZONE_LIB', plugin_dir_path(__FILE__) . 'lib/');
define('MEMBERZONE_MOD', plugin_dir_path(__FILE__) . 'mod/');
define('MEMBERZONE_UI', plugin_dir_path(__FILE__) . 'ui/');
define('MEMBERZONE_HELP', plugin_dir_path(__FILE__) . 'helper/');
define('MEMBERZONE_ATTC_PDF', plugin_dir_path(__FILE__) . 'pdf/');
setlocale(LC_MONETARY, 'id_ID');



function activate_memberzone() {
	require_once MEMBERZONE_LIB . 'memberzone-activator.php';
	Memberzone_Activator::activate();
}

function deactivate_memberzone() {
	require_once MEMBERZONE_LIB . 'memberzone-deactivator.php';
	Memberzone_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_memberzone');
register_deactivation_hook(__FILE__, 'deactivate_memberzone');

require_once MEMBERZONE_LIB . 'memberzone-base.php';

function memberzone_run() {
	$plugin = new Memberzone_Base();
	$plugin->run();
}

memberzone_run();

/* Akhir dari berkas memberzone.php */
