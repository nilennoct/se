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

function togglePwVisibility() {
	if ($('#registerModal #togglePwVisibility i').hasClass('icon-eye-open')) {
		$('#registerModal #pwdRegister').attr('type', 'password');
		$('#registerModal #togglePwVisibility i').addClass('icon-eye-close').removeClass('icon-eye-open');
	}
	else {
		$('#registerModal #pwdRegister').attr('type', 'text');
		$('#registerModal #togglePwVisibility i').addClass('icon-eye-open').removeClass('icon-eye-close');
	}
}

function postLogin() {
	var name = $('#loginModal #nameLogin').val();
	var pwd = $('#loginModal #pwdLogin').val();

	if (name == '' || pwd == '') {
		$('#infoLogin').text('Infomation not complete').addClass('alert-error').slideDown();
		return false;
	}
	else {
		$.post('User/login', {'name': name, 'pwd': pwd}, function(json) {
			if (!json.status) {
				$('#infoLogin').text(json.info).addClass('alert-error').slideDown();
			}
			else {
				$('#infoLogin').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
				setTimeout(function() {
					location.reload();
				}, 1500);
			}
		}, 'json');
	}
}

function postRegister() {
	var name = $('#registerModal #nameRegister').val();
	var pwd = $('#registerModal #pwdRegister').val();
	var email = $('#registerModal #emailRegister').val();

	if (name == '' || pwd == '' || email == '') {
		$('#infoRegister').text('Infomation not complete').addClass('alert-error').slideDown();
		return false;
	}
	else {
		if (!name.match(/^\w{4,20}$/) || !email.match(/^[\w.-]+@\w+\.\w+$/)) {
			$('#infoRegister').text('Format error').addClass('alert-error').slideDown();
		}
		else {
			$.post('User/register', {'name': name, 'pwd': pwd, 'email': email}, function(json) {
				if (!json.status) {
					$('#infoRegister').text(json.info).addClass('alert-error').slideDown();
				}
				else {
					$('#infoRegister').text(json.info).removeClass('alert-error').addClass('alert-success').slideDown();
					setTimeout(function() {
						location.reload();
					}, 1500);
				}
			}, 'json');
		}
	}
}