$(document).ready(function(){

	/* remove complete user history */
	$('.uhEntry.removeAll').click(function(){
		var t = 0;
		$.ajax( {
			'url': mw.config.get( 'wgScriptPath' ) + '/api.php',
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'action': 'uh',
				'format': 'json',
				'type': t
			},
			'success': function( data ) {
				if(data.uh.code == '200') {
					$('.uhEntry.i').hide('250', function() {
						$('.uhEntry.i').remove();
						$('ul.userHistory li').hide(250, function(){
							$('ul.userHistory li').remove();
						});
					});
				} else {

				}
			},
			'error': function() {
				// TODO
			}
		} );

	});

	/* remove single entry from user history */
	$('.uhEntry .remove').click(function(){
		$item = $(this).parent();
		var entry = $item.attr('name');
		var t = 1;
		$.ajax( {
			'url': mw.config.get( 'wgScriptPath' ) + '/api.php',
			'type': 'POST',
			'dataType': 'json',
			'data': {
				'action': 'uh',
				'format': 'json',
				'entry': entry,
				'type': t
			},
			'success': function( data ) {
				if(data.uh.code == '200') {
					$item.hide(250, function(){
						$item.remove();
						var $list = $('ul.userHistory li[name="'+entry+'"]');
						$list.hide(250, function(){
							$list.remove();
						});
						if($('.uhEntry').length == 2) {
							$('.uhEntry.removeAll').hide(200);
							$('.uhEntry.none').show(200);
						}
					});
				} else {
					// TODO
					// mw.message('wl_wall_tw_unfavorite').text()
					mw.notify('err', { title: 'err' });
				}
			},
			'error': function() {
				// TODO
				mw.notify('err', { title: 'err' });
			}
		} );
	});
});