$(function() {
	var args = {};
    var match = null;
    var search = decodeURIComponent(location.search.substring(1));
    var reg = /(?:([^&;]+)=([^&;]+))/g;
    while((match = reg.exec(search))!==null){
        args[match[1]] = match[2];
    }
	if(args['goods-type'] == 'general-goods') {
		$('#goods-type-tab a[href="#general-goods"]').tab('show');
	}
	else if(args['goods-type'] == 'hotel-room') {
		$('#goods-type-tab a[href="#hotel-room"]').tab('show');
	}
	else if(args['goods-type'] == 'airplane-ticket') {
		$('#goods-type-tab a[href="#airplane-ticket"]').tab('show');
	}
});