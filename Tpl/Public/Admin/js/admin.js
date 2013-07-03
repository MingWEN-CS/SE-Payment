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
var BUTTON_TPL = '<div id={0} class="btn btn-inverse verify_btn" style="position:absolute;left:36%">{1}</div>';
var ADMIN_SELECT_RESULT_TPL = '<tr><td>{0}</td><td>{1}</td><td>{2}</td><td><a href="#"><i id={3} class="delete_admin icon-minus"></i></a></td></tr>';
var USER_SELECT_RESULT_TPL = '<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td><td>{6}</td><td><a id={7} class="delete_user" href="#"><i class="icon-minus"></i></a></td></tr>';
var VIP_SELECT_RESULT_TPL = '<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td><a href="#"><i id={5} class="delete_vip icon-minus"></i></a></td></tr>';
var BLACKLIST_SELECT_RESULT_TPL = 
'<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td><td>{4}</td><td>{5}</td>'+
'<td class="dropdown"><a class="dropdown-toggle " data-toggle="dropdown" href="#"><i class="icon-search"/></a>'+
'<ul class="dropdown-menu"  aria-labelledby="dLabel" style="overflow-y:auto; width:200px; height:100px;">{6}</ul></td>'+
'<td class="dropdown"><a class="dropdown-toggle " data-toggle="dropdown" href="#"><i class="icon-search"/></a>'+
'<ul class="dropdown-menu"  aria-labelledby="dLabel" style="overflow-y:auto; width:150px; height:70px;"><li>{7}</li></ul></td>'+
'<td><a href="#"><i id={8} class="delete_blacklist icon-minus"></i></a></td></tr>';
var database;
var total = 0;
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
            else if ( data.status == 2 ) 
              location.href = '/SE-Payment/index.php/Auditor/home';
          },1000);
        }
      },'json');
    }
  });

//Submit appeal
$('#submit').click( function(){
  var name = $('#appeal_name').val();
  var reason = $('#reason').val();
  $.post(ROOT + '/addBLAppeal', {name:name, reason:reason}, function( result ){
    alert(result.info);
    location.href = '/SE-Payment/index.php';
  },'json');
})

});
// ================= EVENT BINDING FUNCTIONS ===================================
//Change background
$('.operation_item').live("click", function() {
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

$('.result_item').live("click", function() {
	var w = $('#param_list_container').width();
	$('.result_item').css({
		'background' : 'white',
		'color' : '#3A88CC'
	});
	$(this).css({
		'background' : '#3A88CC',
		'color' : 'white'
	});
});
//Add admin
$('#Admin_add_btn').live('click', function(){
   var name = $('#Name_add').val();
   var password = $('#Password_add').val();
   var type = $('#Type_add').val();
   $.post(ROOT + '/postAdminAdd', {name:name, password:password, type:type}, function( result ){
       alert(result.info);
   },'json');
})
//Add user
$('#User_add_btn').live('click', function(){
   var name = $('#Name_add_user').val();
   var email = $('#Email_add_user').val();
   var password = $('#Password_add_user').val();
   var phone = $('#Phone_add_user').val();
   var type = $('#Type_add_user').val();
   $.post(ROOT + '/postUserAdd', {name:name, email:email, password:password, phone:phone, type:type}, function( result ){
       alert(result.info);
   },'json');
 })

//Add blacklist
$('#Blacklist_add_btn').live('click', function(){
   $.post(ROOT + '/autoSetBL', {}, function( result ){
       alert(result.info);
   },'json');	
})
//Select admin
$('#Admin_select_btn').live('click', function(){
   var name = $('#Name_select').val();
   var type = $('#Type_select').val();  
   results = ""; 
   $.post(ROOT + '/postAdminSelect', {name:name, type:type}, function( result ){
	    if (result.data!=null)
	    	$.each(result.data, function(index, obj){
          //alert(obj["type"]);
	    		   if (obj["type"] == 1)
                   type = "Auditor";
              else
                   type = "Admistrator";
 		    	results += ADMIN_SELECT_RESULT_TPL.format(index, obj["name"], type, "delete_"+obj["name"]);               
		    })
 		    $('#Admin_tbody').html(results);
	    },'json');
 })
//Select user
$('#User_select_btn').live('click', function(){
   var name = $('#Name_select_user').val();
   var email = $('#Email_select_user').val();
   var phone = $('#Phone_select_user').val();
   var balance = $('#Balance_select_user').val();
   var type = $('#Type_select_user').val();
   results = '';
   $.post(ROOT + '/postUserSelect', {name:name, email:email, phone:phone, balance:balance, type:type}, function( result ){
	if (result.data!=null)
	$.each(result.data, function(index, obj){
        if (obj["BLACKLIST"]=="1")
        	blacklist = "Yes";
        else
        	blacklist = "No";
        if (obj["TYPE"] == "0")
        	type = "Buyer";
        else
        	type = "Seller";
 	   	results += USER_SELECT_RESULT_TPL.format(index, obj["USERNAME"], obj["EMAIL"], obj["PHONE"],  obj["BALANCE"],  type, blacklist, "delete_"+obj["USERNAME"]);               
	})
 	$('#User_tbody').html(results);
   },'json');
})
//Select VIP
$('#VIP_select_btn').live('click', function(){
   var name = "";
   var email = "";
   var phone = "";
   var balance = "";
   var type = "";
   var vip = 1;
   var blacklist = "";
   var results = "";
   $.post(ROOT + '/postSelectVIP', {}, function( result ){
	if (result.data!=null)
	$.each(result.data, function(index, obj){
        if (obj["BLACKLIST"]==1)
        	blacklist = "Yes";
        else
        	blacklist = "No";
 	   	results += VIP_SELECT_RESULT_TPL.format(index, obj["USERNAME"], obj["EMAIL"], obj["PHONE"],  obj["BALANCE"], "delete_"+obj["USERNAME"]);               
	})
 	$('#Vip_tbody').html(results);
   },'json');
})
//Select Blacklist
$('#Blacklist_select_btn').live('click', function(){
   var name = "";
   var email = "";
   var phone = "";
   var balance = "";
   var type = "";
   var vip = "";
   var blacklist = 1;
   var results = "";
   $.post(ROOT + '/postUserSelect', {name:name, email:email, phone:phone, balance:balance, type:type, vip:vip, blacklist:blacklist}, function( result ){
    if (result.data!=null)
      $.each(result.data, function(index, obj){
        if (obj["VIP"]==1)
          vip = "Yes";
        else
        	vip = "No";
      $.post(ROOT + '/getBLReason', {name:obj["USERNAME"]}, function( reason ){

        $.post(ROOT + '/getBLAppeal', {name:obj["USERNAME"]}, function( appeal ){
          var reason_result = "";
          $.each(reason.data, function(index, every){
            reason_result = reason_result + "<li>" + "   ID: "+every["ID"]+" BUYER: "+every["BUYER"]+" SELLER: "+every["SELLER"]+" TOTALPRICE: "+every["TOTALPRICE"]+" ADDRESSID: "+every["ADDRESSID"]+" ISDELETE: "+every["ISDELETE"]+" STATE: "+every["STATE"]+ " ISAUDIT: " + every["ISAUDIT"] + "</li>";
          })
          var appeal_result = appeal.data["reason"];
          if (appeal_result == undefined)
            appeal_result = "N/A";
          results += BLACKLIST_SELECT_RESULT_TPL.format(index, obj["USERNAME"], obj["EMAIL"], obj["PHONE"],  obj["BALANCE"],  vip, reason_result, appeal_result,  "delete_"+obj["USERNAME"]);               
          $('#Blacklist_tbody').html(results);
        },'json');
      },'json');
	})
   },'json');
})

//Realname verification
$('#Realname_check').live('click', function(){
	var name = $('#Name_real').val();
	var id = $('#ID_real').val();
 	$.post(ROOT + '/postVRN', {name:name, id:id}, function( result ){
		alert(result.info);
	},'json'); 	
})
//Card verification
$('#Card_check').live('click', function(){
	var id = $('#ID_card').val();
	var password = $('#Password_card').val();
 	$.post(ROOT + '/postVC', {id:id, password:password}, function( result ){
		alert(result.info);
	},'json'); 	
})
//Delete user
$('.delete_user').live('click', function(){
	var id = this.id;
	name = id.substring(7);
	$.post(ROOT + '/postUserDelete', {name:name}, function( result ){
		alert(result.info);
	},'json');
	$('#User_select_btn').click();
})
//Delete admin
$('.delete_admin').live('click', function(){
	var id = this.id;
	name = id.substring(7);
	$.post(ROOT + '/postAdminDelete', {name:name}, function( result ){
		alert(result.info);
	},'json');
	$('#Admin_select_btn').click();
})
//Delete VIP
$('.delete_vip').live('click', function(){
  var id = this.id;
  name = id.substring(7);
  $.post(ROOT + '/postDeleteVIP', {name:name}, function( result ){
    alert(result.info);
  },'json');
  $('#VIP_select_btn').click();
})
//Delete blacklist
$('.delete_blacklist').live('click', function(){
  var id = this.id;
  name = id.substring(7);
  $.post(ROOT + '/postDeleteBL', {name:name}, function( result ){
    alert(result.info);
  },'json');
  $('#Blacklist_select_btn').click();
})


// ================= COMMON FUNCTIONS ==========================================


// string formatter
String.prototype.format = function() {
  var pattern = /\{\d+\}/g;
  var args = arguments;
  return this.replace(pattern, function(capture) {
    return args[capture.match(/\d+/)];
  });
};
