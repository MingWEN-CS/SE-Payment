$(function () {
	$( '#login-btn' ).click( function(){
		var adminname = $( 'input[name=adminname]' ).val();
		var password = $( 'input[name=password]' ).val();
		if ( adminname == '' || password == '') {
			$('#loginInfo').text('Information is not complete!').addClass('alert-error').slideDown();
			return false;
		}
		else {
			$.post( ROOT + '/login', {adminname : adminname, password : password}, function( data ){
				if (!data.status){
					$('#loginInfo').text(data.info).addClass('alert-error').slideDown();
					return false;
				}
				else {
					$('#loginInfo').text(data.info).removeClass('alert-error').addClass('alert-success').slideDown();
					setTimeout(function(){
						location.href = ROOT + '/admin';
					},1000);
				}
			},'json');
		}
	});
})