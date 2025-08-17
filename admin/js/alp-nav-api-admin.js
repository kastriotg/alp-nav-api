
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
	$('#alpnav-save-brandid').on('click', function(e){
		e.preventDefault();
		var brandId = $('#alpnav-brandid-selector').val();
		if(!brandId){
			$('#alpnav-save-brandid-result').html('<span style="color:red;">Please select a brand.</span>');
			return;
		}
		$('#alpnav-save-brandid-result').html('Saving...');
		$.post(ajaxurl, {
			action: 'alpnav_save_selected_brand',
			brand_id: brandId,
			nonce: alpnavAdmin.nonce_save_brand || ''
		}, function(resp){
			if(resp.success){
				$('#alpnav-save-brandid-result').html('<span style="color:green;">Saved!</span>');
			}else{
				$('#alpnav-save-brandid-result').html('<span style="color:red;">Error: ' + resp.data + '</span>');
			}
		}).fail(function(){
			$('#alpnav-save-brandid-result').html('<span style="color:red;">AJAX failed.</span>');
		});
	});
	$('#alpnav-get-locations').on('click', function(e){
		e.preventDefault();
		var $btn = $(this);
		$btn.prop('disabled', true).text('Saving...');
		$('#alpnav-locations-list').html('Processing...');
		$.post(alpnavAdmin.ajax_url, {
			action: 'alpnav_get_and_save_locations',
			nonce: alpnavAdmin.nonce_get_and_save_locations
		}, function(resp){
			if(resp.success){
				$('#alpnav-locations-list').html('Saved ' + resp.data.count + ' locations.');
			}else{
				$('#alpnav-locations-list').html('<span style="color:red;">Error: ' + resp.data + '</span>');
			}
			$btn.prop('disabled', false).text('Get Locations');
		}).fail(function(){
			$('#alpnav-locations-list').html('<span style="color:red;">AJAX failed.</span>');
			$btn.prop('disabled', false).text('Get Locations');
		});
	});
});
