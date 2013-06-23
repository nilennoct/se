<?php
class PrivilegeAction extends Action {
	public function _initialize() {
		switch (MODULE_NAME) {
			case 'Admin':
				if ( ! session('aid') && ACTION_NAME != 'login' && ACTION_NAME != 'do_login') {
					$this->error('Session out of date, login again.', U('/Admin/login'));
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
				break;

			case 'Seller':
				$uinfo = session('uinfo');
				if ($uinfo[seller] != 1) {
					$this->error('Sorry, you are not a qualified seller!',U('/User/'));
				}
				break;

			default:
				# code...
				break;
		}
	}
}