$(function() {
	var args = {};
    var match = null;
	// format of url is &key=value
    var search = decodeURIComponent(location.search.substring(1));
    var reg = /(?:([^&;]+)=([^&;]+))/g;
    while((match = reg.exec(search))!==null){
        args[match[1]] = match[2];
    }
	// format of url is /key/value
	if(!args['goods-type']) {
		search = location.pathname.substring(location.pathname.indexOf('search') + 6);
		reg = /([^/]+)/g;
	    while((match = reg.exec(search))!==null) {
	    	console.log(match[1]);
			var value = reg.exec(search);
			if(value) {
				args[match[1]] = value[1];
			}
			else break;
		}
	}
	if(!args['goods-type']) {
		args['goods-type'] = args['type']
	}
	var goodDiv;
	if(args['goods-type'] == 'general-goods') {
		$('#goods-type-tab a[href="#general-goods"]').tab('show');
		goodDiv = '#general-goods';
	}
	else if(args['goods-type'] == 'hotel-room') {
		$('#goods-type-tab a[href="#hotel-room"]').tab('show');
		goodDiv = '#hotel-room';
	}
	else if(args['goods-type'] == 'airplane-ticket') {
		$('#goods-type-tab a[href="#airplane-ticket"]').tab('show');
		goodDiv = '#airplane-ticket';
	}
	// if(args['sort_field']) {
	// 	$('.aa').val(args['sort_field']);
	// }

    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
        showMeridian: 1
    });
});