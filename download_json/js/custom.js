$(function(){
	
});

function voltar(qt) {
	if (!isNaN(qt) && qt > 0) {
		var qtt = -1 * Math.abs(qt)
		history.go(qtt);
	} else {
		history.back();
	}
}