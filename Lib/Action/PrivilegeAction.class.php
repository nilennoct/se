<?php
class PrivilegeAction extends Action {
	public function _initialize() {
		$uinfo = session('uinfo');
		switch (MODULE_NAME) {
			case 'Admin':
				if ( ! session('aid') && ACTION_NAME != 'login' && ACTION_NAME != 'do_login') {
					if (ACTION_NAME == '' || ACTION_NAME == 'index') {
						redirect(U('/Admin/login'));
					}
					else {
						$this->error('No privilege.', U('/Admin/login'));
					}
				}
				break;
			case 'Transaction':
			case 'Comment':
			case 'User':
				if ( ! session('uid') && ACTION_NAME != 'login' && ACTION_NAME != 'register') {
					if (IS_POST) {
						$this->ajaxReturn('','Session out of date, login again.',0);
						exit();
					}
					else {
						$this->error('Session out of date, login again.', U('/'));
					}
				}
				elseif ($uinfo[role] == 8 && ACTION_NAME != 'logout' && ACTION_NAME != 'changePwd') {
					redirect(U('/Auditor/'));
				}
				break;

			case 'Seller':
				if ($uinfo[seller] != 1) {
					$this->error('Sorry, you are not a qualified seller!',U('/User/'));
				}
				break;
			case 'Auditor':
				if ($uinfo[role] != 8) {
					$this->error('Sorry, you are not an auditor!',U('/'));
				}
			default:
				# code...
				break;
		}
	}
}