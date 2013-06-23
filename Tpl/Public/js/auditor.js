function showCheckModal() {
	$('#checkModal').modal({
		'keyboard': true,
		'show': true
	});
}
function postCheck() {
	$.post(ROOT + '/Auditor/check', {}, function(json) {
		if (!json.status) {
			$('#infoCheck').text(json.info).addClass('alert-error').slideDown();
			setTimeout(function() {
				$('#infoCheck').slideUp();
			}, 1500);
		}
		else {
			$('#infoCheck').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
			setTimeout(function() {
				location.href = ROOT + '/Auditor/unchecked';
			}, 1500);
		}
	}, 'json');
}

function checkOrModify(id){
	if($("tr#tid"+id).hasClass("warning")){
		//alert("warning");
		$.post(ROOT + '/Auditor/logError',{'tid':id},function(json){
			if(!json.status){
				alert("modify failed");
			}
			else{
				$("tr#tid"+id).removeClass("warning").addClass("success");
				$("i#tid"+id).removeClass("icon-tag").addClass("icon-ok");
				$("td#paid"+id).text($("td#price"+id).text());
			}
		},'json');
	}
	else{
		//alert("no warning");
		$.post(ROOT + '/Auditor/singleCheck',{'tid':id},function(json){
			if(!json.status){
				alert("check failed");
			}
			else{
				$("tr#tid"+id).fadeOut();
			}
		},'json');
	}
}