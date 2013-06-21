<?php
class PrivilegeAction extends Action {
	public function _initialize() {
		switch (MODULE_NAME) {
			case 'Transaction':
			case 'Comment':
			case 'User':
				if ( ! session('uid') && ACTION_NAME != 'login' && ACTION_NAME != 'register') {
					if (IS_POST) {
						$this->ajaxReturn('','Session out of date, login again.',0);
						exit();
					}
					else {
						redirect(U('/'));
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