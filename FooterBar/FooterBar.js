//The hideShow-function for the FooterBar extension
function hideShowFooterBar(){
	if(document.getElementById("footerBarContent").style.display=="block"){
		document.getElementById("footerBarArrow").innerHTML = "&laquo;";
		document.getElementById("footerBarContent").style.display="none";
		document.getElementById("footerBar").style.width="20px";
	}else{
		document.getElementById("footerBarArrow").innerHTML = "&raquo;";
		document.getElementById("footerBarContent").style.display="block";
		document.getElementById("footerBar").style.width="98%";
	}
}