
function showLoginModal() {
	$('#loginModal').modal({
		'keyboard': true,
		'show': true
	});
}

function showRegisterModal() {
	$('#registerModal').modal({
		'keyboard': true,
		'show': true
	});
}

function showChangePwdModal() {
	$('#changePwdModal').modal({
		'keyboard': true,
		'show': true
	});
}

function showChargeModal() {
	$('#chargeModal').modal({
		'keyboard': true,
		'show': true
	});
}

function showRealnameModal() {
	$('#realnameModal').modal({
		'keyboard': true,
		'show': true
	});
}

function togglePwVisibility() {
	if ($('#togglePwVisibility i').hasClass('icon-eye-open')) {
		$('.pwd-visible').attr('type', 'password');
		$('#togglePwVisibility i').addClass('icon-eye-close').removeClass('icon-eye-open');
	}
	else {
		$('.pwd-visible').attr('type', 'text');
		$('#togglePwVisibility i').addClass('icon-eye-open').removeClass('icon-eye-close');
	}
}

function postLogin() {
	var name = $('#loginModal #nameLogin').val();
	var pwd = $('#loginModal #pwdLogin').val();
	var hash = $('#hash input').val();

	if (name == '' || pwd == '') {
		$('#infoLogin').text('Infomation not complete').addClass('alert-error').slideDown();
		return false;
	}
	else {
		$.post(ROOT + '/User/login', {'name': name, 'pwd': pwd, '__hash__': hash}, function(json) {
			if (!json.status) {
				$('#infoLogin').text(json.info).addClass('alert-error').slideDown();
			}
			else {
				$('#infoLogin').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function() {
					location.href = json.data;
				}, 1500);
			}
		}, 'json');
	}
}

function postRegister() {
	var name = $('#registerModal #nameRegister').val();
	var pwd = $('#registerModal #pwdRegister').val();
	var email = $('#registerModal #emailRegister').val();
	var hash = $('#hash input').val();

	if (0 && name == '' || pwd == '' || email == '') {
		$('#infoRegister').text('Infomation not complete').addClass('alert-error').slideDown();
		return false;
	}
	else {
		if (0 && !name.match(/^\w{4,20}$/) || !email.match(/^[\w.-]+@\w+\.\w+$/)) {
			$('#infoRegister').text('Format error').addClass('alert-error').slideDown();
		}
		else {
			$.post(ROOT + '/User/register', {'name': name, 'pwd': pwd, 'email': email, '__hash__': hash}, function(json) {
				if (!json.status) {
					$('#infoRegister').text(json.info).addClass('alert-error').slideDown();
				}
				else {
					$('#infoRegister').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
					setTimeout(function() {
						location.href = ROOT + "/User/"
					}, 1500);
				}
			}, 'json');
		}
	}
}

function postChangePwd() {
	var oldpwd = $('#changePwdModal #oldPwd').val();
	var pwd = $('#changePwdModal #newPwd').val();

	if (oldpwd == '' || pwd == '') {
		$('#infoChangePwd').text('Infomation not complete').addClass('alert-error').slideDown();
		return false;
	}
	else {
		$.post(ROOT + '/User/changePwd', {'oldpwd': oldpwd, 'pwd': pwd}, function(json) {
			if (!json.status) {
				$('#infoChangePwd').text(json.info).addClass('alert-error').slideDown();
			}
			else {
				$('#infoChangePwd').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function() {
					$('#changePwdModal').modal('hide');
					$('#infoCharge').hide();
					$('#changePwdModal input').each(function() {
						$(this).val('');
					});
				}, 1500);
			}
		}, 'json');
	}
}

function postCharge() {
	var amount = parseFloat($('#chargeModal #amount').val());

	if (amount == '') {
		$('#infoCharge').text('Infomation not complete').addClass('alert-error').slideDown();
		return false;
	}
	else if (amount < 0.01) {
		$('#infoCharge').text('Amount format error').addClass('alert-error').slideDown();
		return false;
	}
	else {
		$.post(ROOT + '/User/charge', {'amount': amount}, function(json) {
			if (!json.status) {
				$('#infoCharge').text(json.info).addClass('alert-error').slideDown();
			}
			else {
				$('#charge-panel ul#amount li span.balance').text('$ ' + json.data);
				$('#infoCharge').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function() {
					$('#chargeModal').modal('hide');
					$('#infoCharge').hide();
					$('#chargeModal input').each(function() {
						$(this).val('');
					});
				}, 1500);
			}
		}, 'json');
	}
}

function postVerifyRealname() {
	var rid = $('#realnameModal #rid').val();
	var rname = $('#realnameModal #rname').val();

	if (rid == '' || rname == '') {
		$('#infoRealname').text('Infomation not complete').addClass('alert-error').slideDown();
		return false;
	}
	else if (!rid.match(/\d{10}/)) {
		$('#infoRealname').text('StudentID format error').addClass('alert-error').slideDown();
		return false;
	}
	else {
		$.post(ROOT + '/User/verifyRealname', {'rid': rid, 'rname': rname}, function(json) {
			if (!json.status) {
				$('#infoRealname').text(json.info).addClass('alert-error').slideDown();
			}
			else {
				$('#table-rid').text(rid);
				$('#table-rname').text(rname);
				$('#btn-verify').attr('disabled', 'disabled').text('Real name verified');
				$('#infoRealname').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function() {
					$('#realnameModal').modal('hide');
					$('#infoRealname').hide();
					$('#realname-table').slideDown();
					$('#realnameModal input').each(function() {
						$(this).val('');
					});
				}, 1500);
			}
		}, 'json');
	}
}