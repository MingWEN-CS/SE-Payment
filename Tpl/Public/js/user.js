function postRegister(){
	var email = $('#registerEmail').val();
	var name = $('#registerName').val();
	var pwd = $('#registerPwd').val();
	var repwd = $('#registerRepwd').val();
	var pwd2 = $('#registerPwd2').val();
	var repwd2 = $('#registerRepwd2').val();
	var tmp = $('#registerType').val();
	var phone = $('registerPhone').val();
	var type;
	if (tmp == 'Buyer') type = 0;
	else type = 1;

	if (repwd == '' || pwd == '' || email == '' || name == '' ||
		pwd2 == '' || repwd2 == ''){
		$('#registerInfo').text('Information is not complete.').addClass('alert-error').slideDown();
		return false;
	}
	else if (repwd != pwd){
		$('#registerInfo').text('Your passwords for login do not match!').addClass('alert-error').slideDown();
		return false;
	}
	else if (repwd2 != pwd2){
		$('#registerInfo').text('Your passwords for payment or consignment do not match').addClass('alert-error').slideDown();
	}
	else {
		$.post(ROOT + '/User/register',{'name': name, 'pwd':pwd, 'email':email,'type':type,'pwd2':pwd2,'phone':phone}, function(json){	
			if (!json.status){
				$('#registerInfo').text(json.info).addClass('alert-error').slideDown();
				return false;
			}
			else {
				$('#registerInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();				
				setTimeout(function(){
					location.href = ROOT + '/User/home';	
				},1500);
			}
			
		},'json');
	}
}

function postLogin(){
	var name = $('#loginName').val();
	var pwd = $('#loginPassword').val();
	if (name == '' || pwd == ''){
		$('#loginInfo').text('Information is not complete!').addClass('alert-error').slideDown();
		return false;
	}
	else {
		$.post(ROOT + '/User/login',{'name':name, 'pwd':pwd},function(json){
			if (!json.status){
				$('#loginInfo').text(json.info).addClass('alert-error').slideDown();
				return false;
			}
			else {
				$('#loginInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function(){
					location.href = ROOT + '/User/home';
				},1000);
			}
		},'json');
	}
}