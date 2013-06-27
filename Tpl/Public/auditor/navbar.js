function setNav(){
	var url = window.location.href;
	var index = url.split("/");
	var navi = document.getElementById("userNavbar");
	var all = navi.querySelectorAll("a");
	var i;
	for(i = 0; i < all.length; i++){
		var u = all[i].href.split("/");
		if(u.length != 1){
			if(u[u.length - 1] == index[index.length - 1]){
				all[i].parentNode.setAttribute("class", "active");
				break;
			}
		}
	}
}
window.onload = setNav;


