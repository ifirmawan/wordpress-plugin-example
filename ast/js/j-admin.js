jQuery(document).ready(function () {
	jQuery(document).on('click','.met_dis',function(){
		var id = jQuery(this).attr("id");
		if (jQuery(this).is(':checked')) { 
			jQuery("input[name=brgdiskon]").prop('disabled', false);
		}
	});		
	jQuery(document).on('keyup',"input[name^='brg']",function(){
		var isidis=jQuery("input[name=brgdiskon]").val();
		var brghrg=jQuery("input[name=brghrg]").val();
		var hasildisk;
		if (jQuery('.pers').is(':checked')) {
			hasildisk=parseFloat(brghrg - (brghrg*isidis/100));
			jQuery("input[name=hrgp]").val(hasildisk);
			if (isidis > 100){
				alert("nilai persentase maximal 100");
				jQuery("input[name=brgdiskon]").val(0);
			};
		}else{
			hasildisk=parseInt(brghrg - isidis);
			jQuery("input[name=hrgp]").val(hasildisk);
		}
	});

	jQuery(document).on('keydown','input[type=number]',function(e){
        // Allow: backspace, delete, tab, escape, enter and .
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	jQuery(document).on('click','.btn-act',function(){
		var m_id =jQuery(this).closest('td').find('.meta_id').text();
		var act=jQuery(this).attr('name');
		jQuery.ajax({
				url: "admin-post.php?p=1",
				data:{'id':act,'key':m_id},
				type: "POST",
				success:function(data){jQuery('#page-content').html(data);}
		});
	});
	
	jQuery(document).on('click','.btn-insert',function(){
		var data={ 
			'meta_label' : jQuery('.meta_label').val()
			,'meta_type_id' : jQuery('.meta_type_id').val()
			,'meta_max_val' : jQuery('.meta_max_val').val()
			,'meta_opsional' : jQuery('.meta_opsional').is(':checked')
			,'action' : 'memberzone_penawaran'
			,'memberzone-penawaran-kirim' : 'insertdata'
			,'tabel':'custom_meta_key'
			,'permalink' : jQuery('.permalink').attr('href')
		};
		jQuery.ajax({
				url: "admin-post.php",
				data: data,
				type: "POST",
				success:function(data){
					window.location.href=data;
				}
		});
	});
	
	jQuery(document).on('click','.btn-confrim-pay',function(){
		var dashid=jQuery(this).attr('id');
		var imgurl=jQuery('#imgurl').text();
		m_id=jQuery('#noid-'+dashid).val();
		jQuery('#container-'+dashid).html('<img src="'+imgurl +'" />');
		jQuery.ajax({
				url: "admin-post.php?p=1",
				data:{'id':'9','key':m_id },
				type: "POST",
				success:function(data){jQuery('#container-'+dashid).html(data);}
		});
	});
	jQuery(document).on('click','.btn-terima',function(){
		var dashid=jQuery(this).attr('id');		
		var imgurl=jQuery('#imgurl').text();
		jQuery('.thickbox-info').html('<img src="'+imgurl +'" />');
		jQuery.ajax({
			url: "admin-post.php",
			data:{'id':dashid,'memberzone-penawaran-kirim':'diterima','action':'memberzone_penawaran'},
			type:"POST",
			success:function(data){
				window.location.href = data;
				
				
			}
		});
		
	});

	
	jQuery(document).on('click','.link-edit',function(){
		var id = jQuery(this).attr('id');
		jQuery('.val-'+id).hide();
		jQuery('#txt-'+id).show();
	});
	
	jQuery(document).on('click','.bank-edit',function(){
		var id = jQuery(this).attr('id');
		var nb=jQuery('#nb-'+id).text();
		var anb=jQuery('#anb-'+id).text();
		var nor=jQuery('#norek-'+id).text();
		jQuery(".idbank").val(id);jQuery(".nb").val(nb);
		jQuery(".anb").val(anb);jQuery(".norek").val(nor);
		jQuery('#nb-'+id).hide();jQuery('#anb-'+id).hide();jQuery('#norek-'+id).hide();
	});
	
	jQuery(document).on('click','.bank-del',function(){
		var id = jQuery(this).attr('id');
		var result = confirm("yakin untuk menghapus item ini?");
		if (result) {
			jQuery.ajax({
				url: "admin-post.php",
				data:{'id':id,'tabel':'bank_toko','kolpri':'id','memberzone-penawaran-kirim':'deldata','action':'memberzone_penawaran'},
				type:"POST",
				success:function(data){jQuery('#trow-'+id).html(data);}
		});	
	}
	});
	jQuery(document).on('click','.reset-img',function(){
		var id = jQuery(this).attr('id');
		jQuery.ajax({
			url: "admin-post.php",
			data:{'id_img':id,'del':'y','memberzone-penawaran-kirim':'upload_img','action':'memberzone_penawaran'},
			type:"POST",
			success: function(data) {
				 window.location.href = data;
			}
		});
	});
	jQuery(document).on('click','.addval',function(){
		var btnini=jQuery(this).attr('name');
		jQuery("#txt-"+btnini).show();
		jQuery("#btn-" + btnini).show();
    	jQuery("#ad-"+btnini).hide();
	});
	jQuery(document).on('click','.btn-save-opt',function(){
		var str = jQuery(this).attr("id");
		var txtval =str.substr(4);
		var data={
			'action' : 'memberzone_penawaran'
			,'memberzone-penawaran-kirim' : 'insertdata'
			,'tabel':'custom_meta_opsional'
			,'permalink': jQuery('#kemburl').text()
			,'opsional': jQuery('#txt-'+txtval).val()
			,'meta_key': txtval 
		};
		jQuery.ajax({
			url: "admin-post.php",
			data:data,
			type:"POST",
			success: function(data) {
				 window.location.href = data;
			}
		});
	});
	jQuery(document).on('click','.btn-del-opt',function(){
		var id=jQuery(this).attr('id');
		var result = confirm("yakin untuk menghapus item ini?");
		if(result){
			var data={
				'action' : 'memberzone_penawaran'
				,'memberzone-penawaran-kirim' : 'deldata'
				,'tabel':'custom_meta_key'
				,'permalink': jQuery('#kemburl').text()
				,'kolpri':'id'
				,'id':id
			};
			jQuery.ajax({
				url: "admin-post.php",
				data:data,
				type:"POST",
				success: function(data) {
					window.location.href = data;
				}
			});
		}
	});
	jQuery(document).on('click','.btn-edit-metabox',function(){
		var id = jQuery(this).attr('id');
		var mt=jQuery('.p-mt-'+id).text();
		var ml=jQuery('.p-ml-'+id).text();
		var mmx=jQuery('.p-mmx-'+id).text();
		jQuery('.ml-'+id).attr('style','display:block;');
		jQuery('.mt-'+id).attr('style','display:block;');
		jQuery('.mmx-'+id).attr('style','display:block;');
		jQuery('.btn-'+id).attr('style','display:block;');


		jQuery('.p-ml-'+id).attr('style','display:none;');
		jQuery('.p-mt-'+id).attr('style','display:none;');
		jQuery('.p-mmx-'+id).attr('style','display:none;');
		jQuery('.p-mo-'+id).attr('style','display:none;');

		jQuery('.ml-'+id).val(ml);
		jQuery('.mt-'+id).val(mt);
		jQuery('.mmx-'+id).val(mmx);
		jQuery('.mo-'+id).attr('style','display:block;');
		jQuery(this).attr('style','display:none;');


	});
	jQuery(document).on('click','.btn-upd-metabox',function(){
		var id = jQuery(this).attr('id');
		var data={
			'action' : 'memberzone_penawaran'
			,'memberzone-penawaran-kirim' : 'perbaharui'
			,'tabel':'custom_meta_key'
			,'permalink': 'edit.php?page=opsional-page'
			,'kolid':'id'
			,'id':id
			,'meta_label': jQuery('.ml-'+id).val()
			,'meta_type': jQuery('.mt-'+id).val()
			,'meta_max_val' : jQuery('.mmx-'+id).val()
			,'meta_opsional' : jQuery('.mo-'+id).is(':checked')
		};
		jQuery.ajax({
			url: "admin-post.php",
			data:data,
			type:"POST",
			success: function(data) {
				window.location.href = data;
			}
		});
	});

	
	
	jQuery(document).on('keydown','.norek',function(){
		jQuery(".norek").attr('maxlength','18');
		jQuery(".norek").maxlength();
	});
	
	jQuery(document).on('keydown','.maxval',function(){
		jQuery(".norek").maxlength();
	});	
	

	
	
	jQuery(document).on('keypress','.input',function(e){
		var fsubmit=jQuery('#fsubmit').text();
		if (e.which == 13 ) {
			jQuery('form#'+fsubmit).submit();
			return false;    
		}
	});
	
	
	
	
	jQuery(document).on('click','.btn-tolak',function(){
		var id=jQuery(this).attr('id');
		var data={
			'action':'memberzone_penawaran'
			,'memberzone-penawaran-kirim':'perbaharui'
			,'tabel':'pay'
			,'permalink':'?page=quo-cus-page'
			,'kolid':'noquo'
			,'id':id
			,'status':'7'
		};
		jQuery.ajax({
				url: "admin-post.php",
				data:data,
				type:"POST",
				success: function(data) {
					//alert(data);
					window.location.href = data;
				}
		});
	});
	
	
	jQuery(document).on('click','.btn-req-resi',function(){
		var id=jQuery(this).attr('id');
		var data={
			'action':'memberzone_penawaran'
			,'memberzone-penawaran-kirim':'reqresi'
			,'id':id
		};
		jQuery.ajax({
				url: "admin-post.php",
				data:data,
				type:"POST",
				success: function(data) {				
					window.location.href = data;
				}
		});
	});	
	jQuery(document).on('click','.btn-send-resi',function(){
		var id      	= jQuery(this).attr('id');
		var no_resi 	= jQuery('.noresi-text').val();
		var kurir_name 	= jQuery('.kurir-name-text').val();
		var kurir_url	= jQuery('.kurir-url-text').val();
		var data={
			'action':'memberzone_penawaran'
			,'memberzone-penawaran-kirim' : 'perbaharui'
			,'tabel':'log_noresi'
			,'permalink': 'admin.php?page=quo-cus-page'
			,'kolid':'id'
			,'id':id
 			,'kurir_nama':kurir_name
 			,'kurir_link_web':kurir_url
 			,'no_resi':no_resi
 			,'status':2
		};
		jQuery.ajax({
				url: "admin-post.php",
				data:data,
				type:"POST",
				success: function(data) {				
					window.location.href = data;
				}
		});
	});	
	jQuery(document).on('click','.btn-copy-text',function(){
		var text 	=jQuery(this).text();
		var result	= window.prompt("Copy to clipboard: Ctrl+C, Enter", text);
		if(!result){
			return false;
		}
	});	
	
	jQuery(document).on('click','.btn-recieved-product',function(){
		var id      	= jQuery(this).attr('id');
		var result = confirm("yakin anda sudah menerima barang?");
		if (result) {
			jQuery('.test-form-'+id).attr('style','display:block');
		}
	});	
	
	jQuery(document).on('click','.btn-review-produk',function(){
		var id      		= jQuery(this).attr('id');
		var komen_konten 	= jQuery('.testi-text-'+id).val();
		var komen_produk 	= jQuery('.product-text-'+id).val();
		var data={
			'action':'memberzone_penawaran'
			,'memberzone-penawaran-kirim' :'testiproduk'
			,'comment_content':komen_konten
			,'memberzone-penawaran-produk':komen_produk
			,'id':id
		};
		jQuery.ajax({
				url: "admin-post.php",
				data:data,
				type:"POST",
				success: function(data) {				
					window.location.href = data;
				}
		});
	});	
	
	jQuery(document).on('click','.btn-remove-quo',function(){
		var id      = jQuery(this).attr('id');
		var data={
			'action':'memberzone_penawaran'
			,'memberzone-penawaran-kirim' : 'remove'
			,'id':id
		};
		var result = confirm("yakin anda menghapus data ini?");
		if(result){
			jQuery.ajax({
			url: "admin-post.php",
			data:data,
			type:"POST",
			success: function(data) {				
					window.location.href = data;
				}
			});
		}
	});	
	jQuery(document).on('click','.btn-control-contact',function(){
		var id		=jQuery('.contc-hd-val').val();
		var kirim	=jQuery(this).attr('id');
		var data={
			'action' 						: 'memberzone_penawaran'
			,'memberzone-penawaran-kirim' 	:  kirim
			,'tabel'						: 'sales_contacts'
			,'permalink'					: 'options-general.php?page=memberzone'
			,'sales_contact_id'				: jQuery('.contc-type').val()
			,'sales_contact_val'			: jQuery('.contc-val').val()
			,'kolid'						: []
			,'id'							: []
		};
		if(kirim=='perbaharui'){
			data['kolid']	='id';
			data['id']		=id;
		}
		jQuery.ajax({
			url: "admin-post.php",
			data:data,
			type:"POST",
			success: function(data) {
				 window.location.href = data;
			}
		});
	});	
	
	jQuery(document).on('click','.btn-del-contact',function(){
		var id     	= jQuery(this).attr('id');
		var data	= {
			'action'						: 'memberzone_penawaran'
			,'memberzone-penawaran-kirim' 	: 'deldata'
			,'tabel'						: 'sales_contacts'
			,'id'							: id
			,'kolpri' 						: 'id'
		};
		var result = confirm("yakin anda menghapus data ini?");
		if(result){
			jQuery.ajax({
			url: "admin-post.php",
			data:data,
			type:"POST",
			success: function(data){				
				window.location.href ='options-general.php?page=memberzone';
				}
			});
		}
	});	
	jQuery(document).on('click','.btn-edit-contact',function(){
		var id     	= jQuery(this).attr('id');
		var tombol	= jQuery('.btn-control-contact');
		var idval	= jQuery('.contc-hd-val');
		var data	= {
			'action'						: 'memberzone_penawaran'
			,'memberzone-penawaran-kirim' 	: 'getbyid'
			,'id'							: id
			,'table' 						: 'sales_contacts'
			,'column'						: 'sales_contact_val'
		};
		tombol.attr('id','perbaharui');
		idval.val(id);
		jQuery.ajax({
			url: "admin-post.php",
			data:data,
			type:"POST",
			success: function(data){				
				jQuery('.contc-val').val(data);
			}
		});
	});	
	jQuery(document).on('click','.btn-msg-reject',function(){
		var id     	= jQuery(this).attr('id');
		var iduser 	= jQuery('input[name=iduser'+id+']').val();
		var id_quo 	= jQuery('input[name=id_quo'+id+']').val();
		var pesan	= jQuery('textarea[name=pesan'+id+']').val();
		var data	= {
			'action'						: 'memberzone_penawaran'
			,'memberzone-penawaran-kirim' 	: 'insertdata'
			,'tabel' 						: 'pesan'
			,'id_quo'						: id_quo
			,'iduser'						: iduser
			,'pesan'						: pesan
			,'permalink'					: '?page=quo-cus-page'
		};
		jQuery.ajax({
			url: "admin-post.php",
			data:data,
			type:"POST",
			success: function(data) {
				window.location.href = data;
			}
		});
	});	

	
	
	
	jQuery(document).on('click','.btn-yes-reject',function(){
		var id     	= jQuery(this).attr('id');
		var data	= {
			'action'						: 'memberzone_penawaran'
			,'memberzone-penawaran-kirim' 	: 'rejected'
			,'id'							: id
		};
		jQuery.ajax({
			url: "admin-post.php",
			data:data,
			type:"POST",
			success: function(data) {
				window.location.href = data;
			}
		});
	});	
	jQuery(document).on('click','.ceknotif .notice-dismiss',function(){
		var id =jQuery(this).prev().val();
		var data={
			'action'					  :'memberzone_penawaran'
			,'memberzone-penawaran-kirim' :'deldata'
			,'tabel'					  : 'pesan'
			,'kolpri'					  : 'id'
			,'id'						  : id
		};
		jQuery.ajax({
			url: "admin-post.php",
			data:data,
			type:"POST",
			success: function(data) {
				return false;
			}
		});
	});		
	setInterval(function(){
		var send={
			'action'					  :'memberzone_penawaran'
			,'memberzone-penawaran-kirim' :'getdata'
			,'tabel'					  : 'list_quo'
		};
		jQuery.ajax({
			url: "admin-post.php",
			data:send,
			type:"POST",
			dataType: 'json',
			success: function(obj) {
				var tableStr='<table >';
				var tableBody='';
				jQuery.each(obj,function(key,value){
					var tableRow='';
					jQuery.each(value, function( k, val ) {
						tableRow+='<td>'+val+'</td>';
					});
					tableBody=tableBody+'<tr>'+tableRow+'</tr>';
				});
				jQuery('.listdata').html(tableBody);
			}
		});
	},2000);
	
	
});	

