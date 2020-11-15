<form name="registerform" action="<?php echo site_url('wp-login.php?action=register', 'login_post'); ?>" method="post">
    <p>
        <label for="user_login">Username</label>
        <input type="text" name="user_login" value="">
    </p>
    <p>
        <label for="user_email">E-mail</label>
        <input type="text" name="user_email" id="user_email" value="">
    </p>
    <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" value="Register" /></p>
</form>
<p style="color:#000;background:#F5D76E;padding:5px;">
Sudah Memiliki akun? klik <a href="<?php echo wp_login_url();?>" class="button button-primary " >Masuk</a> untuk menjadi anggota <strong><?php bloginfo('name');?></strong>
</p>