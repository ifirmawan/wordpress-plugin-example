<?php
function getUriSegments() {
    return explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}
function getUriSegment($n) {
    $segs = getUriSegments();
    return count($segs)>0 && count($segs) >= ($n-1 ) ? $segs[$n] : '';
}

function check_quoprod($idu='',$prod=''){
        global $wpdb;
        $t5=$wpdb->prefix.'list_quo';
        $sql="SELECT * FROM `$t5` WHERE `$t5`.`memberzone-penawaran-produk`=$prod AND `$t5`.`idcus`=$idu";
        $query=$wpdb->get_results($sql,ARRAY_A);
        return $query;
}

function cekosong($allpost){
    $empty=array();
    if (is_array($allpost)) {
        foreach ($allpost as $k=>$field) {
        $rtrf=rtrim($field);
            if ((!strlen($field)) || (empty($rtrf)) || ($rtrf==='0') || is_null($rtrf)) {
                $empty[]=$k;
            }
        }
        if (count($empty) > 0){  return $empty;
        }else{ return false; }
    }else{ 
        $rtrf=rtrim($allpost);
        if ((empty($rtrf)) || (!strlen($allpost)) || ($rtrf==='0') || is_null($rtrf)) { return true;
        }else{ return false; }
    }
}


function encrypt_url($string) {
  $key = "MAL_979805"; //key to encrypt and decrypts.
  $result = '';
  $test = "";
   for($i=0; $i<strlen($string); $i++) {
     $char = substr($string, $i, 1);
     $keychar = substr($key, ($i % strlen($key))-1, 1);
     $char = chr(ord($char)+ord($keychar));

     $test[$char]= ord($char)+ord($keychar);
     $result.=$char;
   }

   return urlencode(base64_encode($result));
}

function decrypt_url($string) {
    $key = "MAL_979805"; //key to encrypt and decrypts.
    $result = '';
    $string = base64_decode(urldecode($string));
   for($i=0; $i<strlen($string); $i++) {
     $char = substr($string, $i, 1);
     $keychar = substr($key, ($i % strlen($key))-1, 1);
     $char = chr(ord($char)-ord($keychar));
     $result.=$char;
   }
   return $result;
}

function get_noquo($id=false){
    global $wpdb;
    $t4=$wpdb->prefix.'list_quo';
    if ($id) {
        $get_noquo = $wpdb->get_results("SELECT * FROM $t4 WHERE id='$id'", ARRAY_A );   
    }else{
        $get_noquo = $wpdb->get_results("SELECT * FROM $t4 ORDER BY noquo DESC LIMIT 1", ARRAY_A );   
    }
    if (count($get_noquo) > 0) {
        foreach ($get_noquo as $val) {
            return $val['noquo'];
        }
    }
    return false;
}

function detail_quo_val($item,$key){
    $postid=array();
    switch ($key) {
        case 'noquo':
            $no=array();
            $no['asli']=$item;
            $no['seri']='THC/QUO/'.date('m').'/'.date('d').'/'.$item;
            $new_content =$no;
            break;
        case 'memberzone-penawaran-produk':
            $postid['id']=$item;
            $postingan  =get_post($item);
            $met_dis    =get_post_meta($item,'met_dis', true);
            $brgdiskon  =get_post_meta($item,'brgdiskon', true);
            $hrgp       =get_post_meta($item,'hrgp', true);
            $brghrg     =get_post_meta($item,'brghrg', true);
            $brgnama    =get_post_meta($item,'product_name', true);
            if (strlen($hrgp) > 0 && $brghrg > $hrgp) {
                $price=$hrgp;
            }else{
                $price=$brghrg;
            }
            (strlen($brgnama) > 0)? $postid['title']=$brgnama : $postid['title']=$postingan->post_title;
            $postid['notebrg']=get_post_meta($item,'noteprod', true);
            $postid['hrgd']=$price;
            $postid['hrg']=$price;
            switch ($met_dis) {
                case '6':
                    $postid['diskon']='Rp '.$brgdiskon.',-';
                break;
                case '7':
                    $postid['diskon']=$brgdiskon.' %';
                break;
            }
            $new_content =$postid;
        break;
        case 'idcus':
            $users=array();
            $user=get_user_by('id',$item);
            $users['id']=$item;
            $users['uname']=$user->user_login;
            $users['email']=$user->user_email;
            $new_content =$users;
        break;
        default:
            $new_content .=$item;
        break;
    }
    return $new_content;
}

function get_list_quoby_id($idq='',$foruser=false){
        if (!cekosong($idq)) {
            $user_ID    = get_current_user_id();
            ($foruser && !current_user_can( 'manage_options' ))? $get_lquo=get_where('list_quo',array('id'=>$idq,'idcus'=>$user_ID)) : $get_lquo=get_where('list_quo',array('id'=>$idq));
            foreach ($get_lquo as $key => $value) {
                $getval=array_map('detail_quo_val', $value,array_keys($value));
            }
            $data=array(
                'tgl'=>date('Y-m-d'),
                'attn'=>esc_attr(get_the_author_meta('attn', $getval[2]['id'])),
                'kdquo'=>$getval[1]['seri'],
                'memberzone-penawaran-produk'=>$getval[2]['id'],
                'cc'=>esc_attr(get_the_author_meta('cc', $getval[2]['id'])),
                'telp'=>esc_attr(get_the_author_meta('nohp', $getval[2]['id'])),
                'ancus'=>esc_attr(get_the_author_meta('ancus', $getval[2]['id'])),
                'telpacus'=>esc_attr(get_the_author_meta('telpacus', $getval[2]['id'])),
                'acus'=>esc_attr(get_the_author_meta('acus', $getval[2]['id'])),
                'anbil'=>esc_attr(get_the_author_meta('anbil', $getval[2]['id'])),
                'telpabil'=>esc_attr(get_the_author_meta('telpabil', $getval[2]['id'])),
                'alamat_bill'=>esc_attr(get_the_author_meta('abil', $getval[2]['id'])),
                'anship'=>esc_attr(get_the_author_meta('anship', $getval[2]['id'])),
                'telpaship'=>esc_attr(get_the_author_meta('telpaship', $getval[2]['id'])),
                'alamat_ship'=>esc_attr(get_the_author_meta('aship', $getval[2]['id'])),
                'faks'=>esc_attr(get_the_author_meta('fax', $getval[2]['id'])),
                'id_user'=>$getval[2]['id'],
                'penerima'=>$getval[2]['uname'],
                'email'=>$getval[2]['email'],
                'qty'=>$getval[4],
                'disc'=>$getval[3]['diskon'],
                'price'=>$getval[3]['hrgd'],
                'subtotal'=>intval($getval[3]['hrg']*$getval[4]),
                'produk'=>$getval[3]['title'],
                'notebrg'=>$getval[3]['notebrg'],
                'opsional'=>$getval[5]
                ,'id'=>$getval[0],
                'status'=>$getval[9],
                'exp_time'=>$getval[7],
                'noquo'=>$getval[1]['asli']
            );
            return $data;
        }
        return false;
}

function get_data_table($tabel='',$kol=array(),$id=false,$count=false){
    global $wpdb;
    $tabel=$wpdb->prefix.$tabel;
    if (is_array($kol)) {
        $select=implode(',', $kol);
    }
    $nkol=count($kol);
    ($id)? $sql="SELECT $select FROM $tabel WHERE id=$id" : $sql="SELECT $select FROM $tabel";
    $query=$wpdb->get_results($sql,ARRAY_A);
    if (count($query) > 0 && !cekosong($kol)) {
        if ($count) {
            return count($query);
        }else{
            $send=array();$wew=array();
            foreach ($query as $value) {
                for ($x=0; $x < $nkol; $x++) { 
                    $send[$kol[$x]]=$value[$kol[$x]];
                }
                if (count($kol)==1) {
                    $wew[]=$value[$kol[0]];
                }else{
                    $wew[]=$send;
                }
            }
            if (count($kol)==1 && count($query)==1) {
                return implode('',$send);
            }else{
                return $wew;
            }
        }
    }
    return false;
}
function return_md5($val){
    return md5($val);
}

function return_sanitize_text($val){
    return sanitize_text_field($val);
}

function send_mail_attc($to, $from, $name, $subject, $msg, $attachment = FALSE) {
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: '.$name.'<'. $from .'>' . "\r\n";
    if (file_exists($attachment)) {
        $attachments=array($attachment);
        wp_mail($to, $subject, $msg, $headers, $attachments);
        return true;
    }
    return false;
}

function unset_larik($larik,$unset) {
	foreach($unset as $value) {
		unset($larik[$value]);
	}
	return $larik;
}

function get_alldata($tabel=''){  
    if (!cekosong($tabel)) {
        global $wpdb;   
        $tabel=$wpdb->prefix.$tabel;
        if ($result=$wpdb->get_results("SELECT * FROM $tabel",ARRAY_A)) {
            return $result;
        }
    }
    return false;
    
}
function insert_data($tabel='',$data=array()){	
	if (!cekosong($tabel)) {
		global $wpdb;	
		$tabel=$wpdb->prefix.$tabel;
		$wpdb->insert($tabel,$data);
	}
	
}
function update_data($tabel='',$data=array(),$id=array()) {
		global $wpdb;	
		$tabel=$wpdb->prefix.$tabel;
		return ($wpdb->update($tabel,$data,$id))? true : false;
}
function delete_data($tabel='',$id=array()) {
	global $wpdb;	
	$tabel=$wpdb->prefix.$tabel;
	return $wpdb->delete($tabel,$id);
}

function duedate(){
	$duedate=date_create(date('Y-m-d'));
	$getmemberops=get_option('memberzone_marketer');
	$exp_time=$getmemberops['exp_time'];
	(cekosong($exp_time))? $exptime='3 days': $exptime=$exp_time.' days';
	date_add($duedate,date_interval_create_from_date_string($exptime));
	return date_format($duedate,'Y-m-d');
}

function cekalmat() {
	$user = wp_get_current_user();
	$cek=array('nohp','fax','attn','cc','ancus','telpacus','acus','anbil','telpabil','abil','anship','telpaship','aship');
	$count=0;
	foreach($cek as $value) {
		if (cekosong(get_the_author_meta($value, $user->ID))) {
			$count++;
		}
	}
	if ($count > 0) {
		return true;
	}
	return false;
}
function get_userrole() {
	$user = wp_get_current_user();
	$user = new WP_User($user->ID);
	if (is_user_logged_in()) {
		if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
			foreach ( $user->roles as $role ){
				return $role;
			}
		}
	}
	
}
    function get_list_fields($tabel='')
    {
        if (!cekosong($tabel)) {
            global $wpdb;
            $tabel=$wpdb->prefix.$tabel;
            $kolom=array();
            $result=$wpdb->get_results("SHOW COLUMNS FROM $tabel",ARRAY_A);
            $result2=$wpdb->get_col( "DESC " . $tabel, 0 );
            if ($result) {
                foreach ($result as $column_name) {
                    $kolom[]=$column_name['Field'];
                }
                return $kolom;
            }elseif ($result2) {
				foreach($result2 as $col) {
					$kolm[]=$col;
				}
				return $kolm;
            }
        }
        return false;
    }
	function  getdata_bykoland_id($tabel='',$kol='',$kolid='',$id=''){
		if (!cekosong(array($tabel,$kolid,$id))) {
			global $wpdb;
            $tabel = $wpdb->prefix.$tabel;
            $hasil =$wpdb->get_results("SELECT * FROM $tabel WHERE $kolid='$id'",ARRAY_A);
            if ($hasil) {
                if (count($hasil)==1 && !cekosong($kol)) {
                    foreach ($hasil as $key => $value) {
                        return $value[$kol];
                    }
                }else{
                    return $hasil;
                }
            }
            return false;
        }	
	}
	function get_data_byid($tabel,$kolid,$id) {
		global $wpdb;
		$tabel	= $wpdb->prefix.$tabel;
		$hasil	= $wpdb->get_results("SELECT * FROM $tabel WHERE $kolid='$id'",ARRAY_A);
		return $hasil;
	}	
    function get_where($tabel='',$where=array(),$noadmin=false)
    {
        global $wpdb;
        $tabel = $wpdb->prefix.$tabel;
        $larik_pertama  = key($where);
        $larik_terakhir = end($where);
        $query ="SELECT * FROM $tabel WHERE ";
        foreach ($where as $key => $value) {
            ($key==$larik_pertama || $larik_pertama==$larik_terakhir || $key==$larik_terakhir)? $query .=$key.'='.$value.' ' : $query .='AND '.$key.'='.$value.' ';
        }
        if ($tabel==$wpdb->prefix.'list_quo' && $noadmin){
            $query .=' AND status NOT IN(4)';
        }
        $hasil  = $wpdb->get_results($query,ARRAY_A);
        return $hasil;
    }

	function memberzone_enkrip( $q ) {
		$cryptKey  = 'Iw$nF1rm4w@n';
		$qEncoded      = base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), $q, MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ) );
		return( $qEncoded );
	}
	function memberzone_dekrip( $q ) {
		$cryptKey  = 'Iw$nF1rm4w@n';
		$qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");
		return( $qDecoded );
	}
	function check_url_format($url) {
		return (!filter_var($url, FILTER_VALIDATE_URL) === false)? true : false;	
	}	
    function get_calculate_date($duedate){
         $now = time(); // or your date as well
         $duedate = strtotime($duedate);
         $datediff = $now - $duedate;
         return floor($datediff/(60*60*24));
    }
    function IsNullOrEmptyString($question){
		return (!isset($question) || trim($question)==='');
	}
	function load_thickbox($toshow='') {
		if (!IsNullOrEmptyString($toshow)) {
			add_thickbox();
			ob_start();				
			include MEMBERZONE_UI.'templates/memberzone-ui-thickbox.php';
			$send =ob_get_clean();
			return $send;
		}
	}
	function kosong($value) {
		return (IsNullOrEmptyString($value) || $value=='0')? 1 : 0;
	}
	
	
	
    
    
//------------------------------------------------ fungsi antarmuka tabel beserta aksi yang disediakan ---------------------------------------------------------------------------
/**
 * 
 * sekumpulan fungsi yang akan digunakan array_map untuk mengembalikan isi larik
 * untuk dikembalikan sebagai data yang akan ditampilkan pada antarmuka pengguna
 * */

function return_json_data($table=''){
	$newdir=$_SERVER["DOCUMENT_ROOT"].'/pdf/quo/';
	if (!IsNullOrEmptyString($table) && $data=get_alldata($table)) {
		foreach($data as $key => $value) {
			$produk				= new Member_tambahan();
			$noquopay			= $value['id'].'-'.$value['noquo'];
			$sisane				= getdata_bykoland_id('pay','sisa','noquo',$noquopay);
			$tglkonfirm			= getdata_bykoland_id('pay','time','noquo',$noquopay);
			$stspay				= getdata_bykoland_id('pay','status','noquo',$noquopay);
			$pay_nominal		= getdata_bykoland_id('pay','nominal','noquo',$noquopay);
			$status_reqresi		= getdata_bykoland_id('log_noresi','status','noquo',$value['id']);
			$id_reqresi			= getdata_bykoland_id('log_noresi','id','noquo',$value['id']);
			$no_reqresi			= getdata_bykoland_id('log_noresi','no_resi','noquo',$value['id']);
			$kurir_link_web		= getdata_bykoland_id('log_noresi','kurir_link_web','noquo',$value['id']);
			$kurir_name			= getdata_bykoland_id('log_noresi','kurir_nama','noquo',$value['id']);		
			$reject_msg			= getdata_bykoland_id('pesan','pesan','id_quo',$value['id']);
			$reject_msg_sts		= getdata_bykoland_id('pesan','status','id_quo',$value['id']);
			$lentgl				= intval(strlen($tglkonfirm)-1);
			$user				= get_user_by('id',$value['idcus']);
			if($value['status']==0 && IsNullOrEmptyString($value['exp_time'])){
				$button ='<input class="button button-primary" name="memberzone-penawaran-kirim" type="submit" value="acc">';
				$button .=load_thickbox('msgrjct');	
			}else if($value['status']==3){
				$button ='<input type="hidden" name="memberzone-penawaran-produk" value="'.$value['memberzone-penawaran-produk'].'" />';  
				$button .='<input class="button button-primary" name="memberzone-penawaran-kirim" type="submit" value="invoice">';
			}else if($value['status']==4){
				$button ='<button type="button" class="memberzone-button error btn-remove-quo" id="'.$value['id'].'">Hapus</button>';
				if(IsNullOrEmptyString($value['review_id'])){
					$button .='<a class="memberzone-button" href="'.admin_url('comment.php?action=editcomment&c='.$value['review_id']).'" >Periksa Review</a>';
				}
				$button .='<a class="memberzone-button warning " href="'.'?ac='.md5($value['id']).'&s='.$snya.'" >Pulihkan</a>';
			}else if($value['status']==5 && $pay_nominal){
				$button =load_thickbox('pay');
			}else if($value['status']==11){
				$button =load_thickbox('detailreject');
			}else if($status_reqresi && $status_reqresi==1){
				ob_start();
				include MEMBERZONE_UI.'memberzone-ui-resi-form-input.php';
				$button =ob_get_clean();
			}
			$aksi ='<form method="post" action="'.esc_url(admin_url('admin-post.php')).'">';
			$aksi .=$button;
			$aksi .='<input type="hidden" name="id" value="'.$value['id'].'" /><input name="action" type="hidden" value="memberzone_penawaran"></form>';
			(IsNullOrEmptyString($value['exp_time']))? $exp_time='-' : $exp_time =date("j F Y", strtotime($value['exp_time']));
			(in_array($value['status'],array('1','4','5')))? $docid='i' : $docid='q';
			(file_exists($newdir.'Quotation-'.$value['noquo'].'.pdf'))? $pdf_view='<a href="'.esc_url(admin_url('?di='.$docid.'&id='.encrypt_url($value['id']))).'">'.$value['noquo'].'</a>' : $pdf_view='belum tersedia'; 
			$send[] = array(
					'pdf'=>$pdf_view
					,'catatan'=>'belum ada'
					,'produk'=>$produk->Product_name($value['memberzone-penawaran-produk'])
					,'uname'=>$user->user_login
					,'time'=>date("j F Y", strtotime($value['time']))
					,'exp_time'=>$exp_time
					,'aksi' =>$aksi
				);
		}
		return $send;		
	}else{
		return false;	
	}
}


