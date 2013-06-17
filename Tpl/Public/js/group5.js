/* =============================================================================
* MaterialGenius/index.js
* ------------------------------------------------------------
* Copyright 2012 Exacloud, Inc.
* http://www.qunhe.cc
* ========================================================================== */

// ================= GLOBAL VARIABLES ==========================================
var MATERIALS_URL = 'api/materials';
var SHADERS_URL = 'api/shaders';
var RENDER_URL = 'api/render';
var TEXTURESCAT_URL = 'api/textureCategories';
var TEXTURES_PATH = '/mnt/exaclouddata/texture';

var TEXTURES_URL = '/textures';
var PREVIEW_URL = 'http://10.1.1.10:7080/TextureRepo/?sort=1&file={0}';

var PARAM_TYPE_ICON_LIST = {
	Texture : 'icon-picture',
	Float : 'icon-resize-horizontal',
	Float3 : 'icon-pencil'
}

var PARAM_ITEM_TPL = '<div class="control-group"><label class="control-label">{0}</label><div class="controls"><div class="input-medium"><input id={0} class="span12"></div></div></div>';
var SHADER_ITEM_TPL = '<option id={0} class="shader_item">{1}</option>';
var TEXTURES_ITEM_TPL = '<option class="texture_item">{0}</option>';
var BUTTON_TPL = '<div class="btn btn-inverse verify_btn">{0}</div>'
var SELECTED_MATERIAL;
var SELECTED_PARAM_ID;
var UNSAVED_CHANGES;

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
})
$('#user').live("click", function(){
	load_user();
})
$('#vip').live("click", function(){
	load_vip();
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



// ================= COMMON FUNCTIONS ==========================================
function load_admin() {
	var param_item = '';
	param_item += PARAM_ITEM_TPL.format("ID");
	param_item += PARAM_ITEM_TPL.format("Name");
	param_item += PARAM_ITEM_TPL.format("Email");
	$('#param_list_body').html(param_item);
	$('#delete_option').show();
	$('.param_list_btn').show();
}
function load_user() {
	var param_item = '';
	param_item += PARAM_ITEM_TPL.format("ID");
	param_item += PARAM_ITEM_TPL.format("Name");
	param_item += PARAM_ITEM_TPL.format("Email");
	param_item += PARAM_ITEM_TPL.format("Type");
	param_item += PARAM_ITEM_TPL.format("Balance");
	param_item += PARAM_ITEM_TPL.format("Phone");
	$('#param_list_body').html(param_item);
	$('#delete_option').show();
	$('.param_list_btn').show();
}

function load_vip() {
	var param_item = '';
	param_item += PARAM_ITEM_TPL.format("ID");
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

function pack_material() {
	SELECTED_MATERIAL.textureInfo.name = $('#texture_name').val();
	SELECTED_MATERIAL.textureCategory = $('#texture_category').get(0).selectedIndex;
	SELECTED_MATERIAL.textureInfo.info = $('#texture_info').val();
	SELECTED_MATERIAL.textureInfo.description = $('#texture_description').val();
	SELECTED_MATERIAL.shaderName = $('#shader_name').val();
	SELECTED_MATERIAL.parameters = [];
	$.each($('.param_item'), function(index, item) {
		SELECTED_MATERIAL.parameters.push({
			name : item.id,
			type : $(item).attr('type'),
			value : $(item).attr('value')
		});
	});
}

// string formatter
String.prototype.format = function() {
	var pattern = /\{\d+\}/g;
	var args = arguments;
	return this.replace(pattern, function(capture) {
		return args[capture.match(/\d+/)];
	});
};
