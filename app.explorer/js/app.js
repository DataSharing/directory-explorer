$(document).ready(function() {
	$(".zoneClick").click(function(){
     	window.location=$(this).find("a").attr("href");
     	return false;
	});
	setTimeout(function() {
      $(".notif").remove();
    }, 5000);
});

function cocherOuDecocherTout(cochePrincipale) {
    var coches = document.getElementById('tableau')
                     .getElementsByTagName('input');
    for(var i = 0 ; i < coches.length ; i++) {
        var c = coches[i];
            if(c.type.toUpperCase() == 'CHECKBOX' & c != cochePrincipale) {
                c.checked = cochePrincipale.checked;
            }
        }
     return true;
 }