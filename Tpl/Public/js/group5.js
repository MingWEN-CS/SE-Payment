/* =============================================================================
* MaterialGenius/index.js
* ------------------------------------------------------------
* Copyright 2012 Exacloud, Inc.
* http://www.qunhe.cc
* ========================================================================== */



var PARAM_TYPE_ICON_LIST = {
	Texture : 'icon-picture',
	Float : 'icon-resize-horizontal',
	Float3 : 'icon-pencil'
}

var PARAM_ITEM_TPL = '<div class="control-group"><label class="control-label">{0}</label><div class="controls"><div class="input-medium"><input id={0} class="span12"></div></div></div>';
var BUTTON_TPL = '<div class="btn btn-inverse verify_btn">{0}</div>'
var database;
// ================= INIT FUNCTIONS ============================================
$(function() {
	// init viewport height
	success = 0;
	var h = $(window).height();
	$('#operation_list_body').height(h - 190);
	$('#param_list_body').height(h - 486);

	// enable fancybox


	// load material list
	URL_STR = decodeURIComponent(window.location.search);
	//get the info from ModalGenius
	URL_STR = URL_STR.replace('?', '');
	$('.operation_item:first').click();


});
// ================= EVENT BINDING FUNCTIONS ===================================

$('#admin').live("click", function(){
	load_admin();
	database = "Admin";
})
$('#user').live("click", function(){
	load_user();
	database = "User";
})
$('#vip').live("click", function(){
	load_vip();
	database = "Vip";
})
$('#verify').live("click", function(){
	load_verify();
})
$('#blacklist').live("click", function(){
	load_blacklist();
})
$('.operation_item').live("click", function() {
	// toggle material selection
	var w = $('#param_list_container').width();
	$('.operation_item').css({
		'background' : 'white',
		'color' : '#3A88CC'
	});
	$(this).css({
		'background' : '#3A88CC',
		'color' : 'white'
	});
});


$('.editable').on('focus', function() {
	$('#' + SELECTED_MATERIAL._id).css({
		'background' : 'red',
		'color' : 'white'
	});
	UNSAVED_CHANGES = true;
});

$('#add').live('click', function(){
   var name = $('#Name').val();
   var password = $('#Password').val();
   var email = $('#Email').val();
   var type = $('#Type').val();
   var balance = $('#Balance').val();
   var phone = $('#Phone').val();
   var vip = $('#VIP').val();
   var info = $('#Info').val();
   var blacklist = $('#Blacklist').val();
	$.post(ROOT + '/postAdd', {name:name, password:password, email:email, type:type, balance:balance, phone:phone, vip:vip, info:info, blacklist:blacklist, database:database}, function( data ){
		if (!data.status){
			alert("Fail to Add!");
		}
		else {
			alert("Success!");
		}
	},'json');
})

$('#select').live('click', function(){
   var name = $('#Name').val();
   var password = $('#Password').val();
   var email = $('#Email').val();
   var type = $('#Type').val();
   var balance = $('#Balance').val();
   var phone = $('#Phone').val();
   var vip = $('#VIP').val();
   var info = $('#Info').val();
   var blacklist = $('#Blacklist').val();
	$.post(ROOT + '/postSelect', {name:name, password:password, email:email, type:type, balance:balance, phone:phone, vip:vip, info:info, blacklist:blacklist, database:database}, function( data ){
		if (!data.status){
			alert("Fail to Select!");
			return false;
		}
		else {
			alert(data.info);
			return true;
		}
	},'json');
})
$('#delete').live('click', function(){
   var name = $('#Name').val();
   var password = $('#Password').val();
   var email = $('#Email').val();
   var type = $('#Type').val();
   var balance = $('#Balance').val();
   var phone = $('#Phone').val();
   var vip = $('#VIP').val();
   var info = $('#Info').val();
   var blacklist = $('#Blacklist').val();
	$.post(ROOT + '/postDelete', {name:name, password:password, email:email, type:type, balance:balance, phone:phone, vip:vip, info:info, blacklist:blacklist, database:database}, function( data ){
		if (!data.status){
			alert("Fail to Delete!");
			return false;
		}
		else {
			alert("Success!");
			return true;
		}
	},'json');
})


// ================= COMMON FUNCTIONS ==========================================
function load_admin() {
	var param_item = '';
	param_item += PARAM_ITEM_TPL.format("Name");
	param_item += PARAM_ITEM_TPL.format("Password");
	param_item += PARAM_ITEM_TPL.format("Info");
	$('#param_list_body').html(param_item);
	$('#delete_option').show();
	$('.param_list_btn').show();
}
function load_user() {
	var param_item = '';
	param_item += PARAM_ITEM_TPL.format("Name");
	param_item += PARAM_ITEM_TPL.format("Password");
	param_item += PARAM_ITEM_TPL.format("Email");
	param_item += PARAM_ITEM_TPL.format("Type");
	param_item += PARAM_ITEM_TPL.format("Balance");
	param_item += PARAM_ITEM_TPL.format("Phone");
	param_item += PARAM_ITEM_TPL.format("VIP");
	param_item += PARAM_ITEM_TPL.format("Blacklist");
	$('#param_list_body').html(param_item);
	$('#delete_option').show();
	$('.param_list_btn').show();
}

function load_vip() {
	var param_item = '';
	param_item += PARAM_ITEM_TPL.format("Name");
	param_item += PARAM_ITEM_TPL.format("Email");
	param_item += PARAM_ITEM_TPL.format("Type");
	param_item += PARAM_ITEM_TPL.format("Balance");
	param_item += PARAM_ITEM_TPL.format("Phone");
	$('#param_list_body').html(param_item);
	$('#delete_option').show();
	$('.param_list_btn').show();
}
 
function load_verify() {
	var param_item = '';
	param_item += PARAM_ITEM_TPL.format("Name");
	param_item += PARAM_ITEM_TPL.format("ID Number");
	param_item += "<hr><br>";
	param_item += BUTTON_TPL.format("Verify");
	param_item += BUTTON_TPL.format("Report");
	$('#param_list_body').html(param_item);
	$('#delete_option').hide();
	$('.param_list_btn').hide();
}


// string formatter
String.prototype.format = function() {
	var pattern = /\{\d+\}/g;
	var args = arguments;
	return this.replace(pattern, function(capture) {
		return args[capture.match(/\d+/)];
	});
};
