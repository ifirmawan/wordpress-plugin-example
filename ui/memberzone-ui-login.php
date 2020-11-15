<?php 
	$args = array(
        'echo'           => true,
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
	wp_login_form($args);	
if (get_option( 'users_can_register')): 
?>
<span id="imgurl" style="display:none;"> <?php echo MEMBERZONE_AST.'images/ajax-loader.gif';?> </span>
<p style="color:#000;background:#F5D76E;padding:5px;">
Belum memilki akun? klik <a href="<?php echo wp_registration_url();?>" class="button button-primary " > mendaftar</a> untuk menjadi anggota <strong><?php bloginfo('name');?></strong>
</p>
<?php else: ?>
<p style="color:#000;background:#F5D76E;padding:5px;">
Belum memilki akun? silahkan hubungi email ini <a href="mailto:<?php bloginfo('admin_email');?>"><?php bloginfo('admin_email');?></a> untuk mendaftarkan anda sebagai anggota <strong><?php bloginfo('name');?></strong>
</p>
<?php endif;?>
