
jQuery(function($){
	$('#alpnav-get-brands').on('click', function(e){
		e.preventDefault();
		var $btn = $(this);
		$btn.prop('disabled', true).text('Saving...');
		$('#alpnav-brands-list').html('Processing...');
		$.post(alpnavAdmin.ajax_url, {
			action: 'alpnav_get_and_save_brands',
			nonce: alpnavAdmin.nonce
		}, function(resp){
			if(resp.success){
				$('#alpnav-brands-list').html('Saved ' + resp.data.count + ' brands.');
			}else{
				$('#alpnav-brands-list').html('<span style="color:red;">Error: ' + resp.data + '</span>');
			}
			$btn.prop('disabled', false).text('Get Brands');
		}).fail(function(){
			$('#alpnav-brands-list').html('<span style="color:red;">AJAX failed.</span>');
			$btn.prop('disabled', false).text('Get Brands');
		});
	});
});
