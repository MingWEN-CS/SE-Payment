function postRegister(){
	var email = $('#registerEmail').val();
	var name = $('#registerName').val();
	var pwd = $('#registerPwd').val();
	var repwd = $('#registerRepwd').val();
	var pwd2 = $('#registerPwd2').val();
	var repwd2 = $('#registerRepwd2').val();
	var tmp = $('#registerType').val();
	var phone = $('#registerPhone').val();
	var cellPhone = /^1[3|4|5|8][0-9]\d{4,8}$/;
	var type;
	if (tmp == 'Buyer') type = 0;
	else type = 1;
	var reg = /^\w+$/;
	var emailReg = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;

	if (repwd == '' || pwd == '' || email == '' || name == '' ||
		pwd2 == '' || repwd2 == ''){
		$('#registerInfo').text('Information is not complete.').addClass('alert-error').slideDown();
		return false;
	}
	else if (!name.match(reg)){
		$('#registerInfo').text('Make sure that your money only contains characters,digits or underline!').addClass('alert-error').slideDown();
		return false;
	}
	else if (email.length > 40){
		$('#registerInfo').text('Your email is too long, Please control it within 40 characters!!').addClass('alert-error').slideDown();
		return false;

	}
	else if (name.length < 6 || name.length > 20){
		$('#registerInfo').text('Name is too short or too long!').addClass('alert-error').slideDown();
		return false;
	}
	else if (!email.match(emailReg)){
		$('#registerInfo').text('Your email format is wrong!').addClass('alert-error').slideDown();
		return false;
	
	}
	else if (repwd != pwd){
		$('#registerInfo').text('Your passwords for login do not match!').addClass('alert-error').slideDown();
		return false;
	}
	else if (repwd2 != pwd2){
		$('#registerInfo').text('Your passwords for payment or consignment do not match').addClass('alert-error').slideDown();
		return false;
	}
	else if (phone != ''){
		if (!(phone.match(cellPhone))){
			$('#registerInfo').text('The number you filled is not a phone number').addClass('alert-error').slideDown();
			return false;
		}
	}
	else if (pwd.length > 40 || pwd2.length > 40)
	{
		$('#registerInfo').text('Your passwords is too long, Please control it within 40 characters!!').addClass('alert-error').slideDown();
		return false;

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
	/*
	var buyer = $('#type1').attr("checked");
	var seller = $('#type2').attr("checked");
	var type;

	if (seller == "checked") type = 1;
	else if (buyer == "checked") type = 0;
	*/

	if (name == '' || pwd == ''){
		$('#loginInfo').text('Information is not complete!').addClass('alert-error').slideDown();
		return false;
	}
	else {	
		$.post(ROOT + '/User/login',{'name':name, 'pwd':pwd},function(json){
			if (json.status == 0){
				$('#loginInfo').text(json.info).addClass('alert-error').slideDown();
				return false;
			}
			else if (json.status == 2){
				$('#loginInfo').text(json.info).addClass('alert-error').slideDown();
				setTimeout(function(){
					location.href = ROOT + '/Admin/appeal';
				},1000);

			}
			else if (json.status == 1){
				$('#loginInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function(){
					location.href = ROOT + '/User/home';
				},1000);
			}
		},'json');
	}
}

function changeLoginPwd(){
	var oldLoginPwd = $('#oldLoginPwd').val();
	var newLoginPwd = $('#newLoginPwd').val();
	var newLoginRepwd = $('#newLoginRepwd').val();

	if (oldLoginPwd == '' || newLoginPwd == '' || newLoginRepwd == ''){
		$('#changeLoginPwdInfo').text('Information is not complete!').addClass('alert-error').slideDown();
		return false;
	}
	else if (newLoginPwd != newLoginRepwd){
		$('#changeLoginPwdInfo').text('New passwords do not match!').addClass('alert-error').slideDown();
		return false;
	}
	else if (newLoginPwd.length > 40){
		$('#changeLoginPwdInfo').text('Your email is too long, Please control it within 40 characters!!').addClass('alert-error').slideDown();
		return false;
	}
	else{
		$.post(ROOT + '/User/changeLoginPwd',{'pwd':newLoginRepwd,'oldpwd':oldLoginPwd},function(json){
			if (!json.status){
				$('#changeLoginPwdInfo').text(json.info).addClass('alert-error').slideDown();
				return false;
			}
			else {
				$('#changeLoginPwdInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function(){
					$("#loginPassword").modal('hide');
					$('#loginPassword input').each(function() {
						$(this).val('');
					});
				},800)
			}
		},'json');		
	}
}

function changePaymentPwd(){
	var oldLoginPwd = $('#oldPaymentPwd').val();
	var newLoginPwd = $('#newPaymentPwd').val();
	var newLoginRepwd = $('#newPaymentRepwd').val();

	if (oldLoginPwd == '' || newLoginPwd == '' || newLoginRepwd == ''){
		$('#changePaymentPwdInfo').text('Information is not complete!').addClass('alert-error').slideDown();
		return false;
	}
	else if (newLoginPwd != newLoginRepwd){
		$('#changePaymentPwdInfo').text('New passwords do not match!').addClass('alert-error').slideDown();
		return false;
	}
	else if (newLoginPwd.length > 40){
		$('#changePaymentPwdInfo').text('Your email is too long, Please control it within 40 characters!!').addClass('alert-error').slideDown();
		return false;
	}
	else{
		$.post(ROOT + '/User/changePaymentPwd',{'pwd':newLoginRepwd,'oldpwd':oldLoginPwd},function(json){
			if (!json.status){
				$('#changePaymentPwdInfo').text(json.info).addClass('alert-error').slideDown();
				return false;
			}
			else {
				$('#changePaymentPwdInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function(){
					$("#paymentPassword").modal('hide');
					$('#paymentPassword input').each(function() {
						$(this).val('');
					});
				},800)
			}
		},'json');		
	}
}


function setPhone(){
	var phone = $('#setPhoneNumber').val();
	var cellPhone = /^1[3|4|5|8][0-9]\d{4,8}$/;
	if (phone == ''){
		$('#setPhoneInfo').text('Information is not complete').addClass('alert-error').slideDown();
		return false;
	}
	else if (!(phone.match(cellPhone))){
		$('#setPhoneInfo').text('The number you filled is not a phone number').addClass('alert-error').slideDown();
		return false;
	}
	else{
		$.post(ROOT+'/User/setPhone',{'phone':phone},function(json){
			if (!json.status){
				$('#setPhoneInfo').text(json.info).addClass('alert-error').slideDown();
				return false;				
			}
			else {
				$('#setPhoneInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function(){
										$('#modifyPhone input').each(function() {
						$(this).val('');
					});

					location.href = ROOT + '/User/home';
				},1000)
			}
		},'json');
	}
}

function modifyPhone(){
	var phone = $('#modifyPhoneNumber').val();
	var cellPhone = /^1[3|4|5|8][0-9]\d{4,8}$/;
	var fixedPhone = /^0(([1-9]d)|([3-9]d{2}))d{8}$/;
	if (phone == ''){
		$('#modifyPhoneInfo').text('Information is not complete').addClass('alert-error').slideDown();
		return false;
	}
	else if (!(phone.match(cellPhone))){
		$('#modifyPhoneInfo').text('The number you filled is not a phone number').addClass('alert-error').slideDown();
		return false;
	}
	else{
		$.post(ROOT+'/User/modifyPhone',{'phone':phone},function(json){
			if (!json.status){
				$('#modifyPhoneInfo').text(json.info).addClass('alert-error').slideDown();
				return false;				
			}
			else {
				$('#modifyPhoneInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function(){
					$('#modifyPhone input').each(function() {
						$(this).val('');
					});
					location.href = ROOT + '/User/home';
				},1000)
			}
		},'json');
	}
}

function addAddress(){
	var province = $('#addrProvince').val();
	var city = $('#addrCity').val();
	var district = $('#addrDistrict').val();
	var street = $('#addrStreet').val();
	if (province.length > 20 || city.length > 20 || district.length > 20 || district.length > 40 ){
		$('#addAddressInfo').text('Your Information is too long!').addClass('alert-error').slideDown();
		return false;
	}
	else 
	{
		$.post(ROOT + '/User/addAddress',
			{'province':province,'city':city,'district':district,'street':street},
			function(json){
				if (!json.status){
					$('#addAddressInfo').text(json.info).addClass('alert-error').slideDown();
					return false;
				}
				else {
					$('#addAddressInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
					setTimeout(function(){
						location.href = ROOT + '/User/home';
					},1000)
				}
			},'json')
	}
}

function authenticate(){
	var realName = $('#realName').val();
	var idNumber = $('#idNumber').val();

	if (realName == '' || idNumber == ''){
		$('#authenticateInfo').text('Information is not complete!').addClass('alert-error').slideDown();
		return false;
	}
	else{
		$.post(ROOT+'/User/authenticate',{'realName':realName, 'idNumber':idNumber},function(json){
			if (!json.status){
				$('#authenticateInfo').text(json.info).addClass('alert-error').slideDown();
				return false;
			}
			else {
				$('#authenticateInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function(){
					location.href = ROOT + '/User/home';
				},1000)
			}
		},'json')
	}
}


function modifyOther(){
	var newEmail = $('#modifyNewEmail').val();
	var realName = $('#modifyRealName').val();
	var idNumber = $('#modifyIdNumber').val();
	var emailReg = /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
	/*
	if (!newEmail.match(emailReg)){
		$('#modifyInfo').text('Your email format is wrong').addClass('alert-error').slideDown();
		return false;
	}
	*/
	if (newEmail.length > 40){
		$('#modifyInfo').text('Your email is too long, Please control it within 40 characters!!').addClass('alert-error').slideDown();
		return false;

	}
	else {
		$.post(ROOT+'/User/modifyOther',{'email':newEmail,'realName':realName, 'idNumber':idNumber},function(json){
			if (!json.status){
				$('#modifyInfo').text(json.info).addClass('alert-error').slideDown();
				return false;
			}
			else {
				$('#modifyInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function(){
					location.href = ROOT + '/User/home';
				},1000)
			}
		},'json')
	}
}


function chargeMoney(){
	var cardId = $('#chargeCardId').val();
	var money = $('#chargeAmount').val();
	var cardPwd = $('#chargeCardPwd').val();
	if (cardId == null){
		$('#chargeMoneyInfo').text('You do not have a card!').addClass('alert-error').slideDown();
		return false;
	}
	else if (cardId == '' || cardPwd == '' || money == ''){
		$('#chargeMoneyInfo').text('Information is not complete').addClass('alert-error').slideDown();
		return false;
	}
	else{
		var reg = /^\d+(\.\d+)?$/;
		if (!money.match(reg)){
			$('#chargeMoneyInfo').text('Please make sure that the number you input is >= 0').addClass('alert-error').slideDown();
			return false;
		}
		else if (parseFloat(money) < 0){
			$('#chargeMoneyInfo').text('The money you fill is less than zero').addClass('alert-error').slideDown();
			return false;
		}
		else{
			$.post(ROOT + '/User/chargeMoney',{'money':parseFloat(money), 'cardId': cardId, 'cardPwd':cardPwd},function(json){
				if (!json.status){
					$('#chargeMoneyInfo').text(json.info).addClass('alert-error').slideDown();
					return false;
				}
				else {
					$('#chargeMoneyInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
					setTimeout(function(){
						location.href = ROOT + '/User/account';
					},1000)
				}
			},'json')
		}
	}

}

function addBankCard(){
	var cardNo = $('#addCardNumber').val();
	var cardPwd = $('#addCardPwd').val();
	if (cardNo == '' || cardPwd == ''){
		$('#addBankCardInfo').text('Information is not complete').addClass('alert-error').slideDown();
		return false;
	}
	else{
		$.post(ROOT + '/User/addBankCard',{'cardNo':cardNo, 'cardPwd':cardPwd},function(json){
			if (!json.status){
				$('#addBankCardInfo').text(json.info).addClass('alert-error').slideDown();
				return false;
			}
			else {
				$('#addBankCardInfo').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function(){
					location.href = ROOT + '/User/account';
				},1000)
			}
		},'json')
	}
}

function deleteAddress(addressId){
	var id = '#deleteAddress'+addressId;
	//alert(id);
	$(id).show('fade');
}

function deleteAddressCancel(addressId){
	var id = '#deleteAddress'+addressId;
	$(id).hide('fade');
}

function deleteAddressConfirm(addressId){
	var id = '#deleteAddress'+addressId;
	$.post(ROOT + '/User/deleteAddress',{'aid':addressId},function(json){
		if (!json.status){
			$('#deleteAddressInfo').text(json.info);
		}
		else {
			$('#deleteAddressInfo').text(json.info);
			setTimeout(function(){
				$(id).hide('fade');
				location.href = ROOT + '/User/home';
			},1000)
		}
	},'json')
}

function deleteCard(Id){
	var id = '#deleteCard'+Id;
	//alert(id);
	$(id).show('fade');
}

function deleteCardCancel(Id){
	var id = '#deleteCard'+Id;
	$(id).hide('fade');
}

function deleteCardConfirm(Id){
	var id = '#deleteAddress'+Id;
	$.post(ROOT + '/User/deleteCard',{'cid':Id},function(json){
		if (!json.status){
			$('#deleteCardInfo').text(json.info);
		}
		else {
			$('#deleteCardInfo').text(json.info);
			setTimeout(function(){
				$(id).hide('fade');
				location.href = ROOT + '/User/account';
			},1000)
		}
	},'json')
}