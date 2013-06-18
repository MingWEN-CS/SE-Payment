var timeout = 3000;

function showHint(info, obj) {
	var tests = ['.alert', '.alert-info'];
	var i = 0;
	while (obj == undefined || obj.length == 0)
		obj = $(tests[i++]);
	if (info != undefined) {
		obj.text(info).slideDown();
	} else {
		obj.slideDown();
	}
	setTimeout(function() {
		obj.slideUp();
	}, timeout);
}