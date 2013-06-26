$(function () {
	$( '#login-btn' ).click( function(){
		var adminname = $( 'input[name=adminname]' ).val();
		var password = $( 'input[name=password]' ).val();
		if ( adminname == '' ) {
			$('#loginInfo').text('Please Input Admin Name').addClass('alert-error').slideDown();
			return false;
		}
		if ( password == '') {
			$('#loginInfo').text('Please Input Password').addClass('alert-error').slideDown();
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
						if( data.status == 1 )
							location.href = ROOT + '/index';
						else
							;
					},1000);
				}
			},'json');
		}
	});
})