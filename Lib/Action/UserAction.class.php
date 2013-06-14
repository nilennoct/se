<?php
class UserAction extends Action {
	public function _initialize() {
		if ( ! session('uid') && ACTION_NAME != 'login' && ACTION_NAME != 'register') {
			if (IS_POST) {
				$this->ajaxReturn('','Session out of date, login again.',0);
				exit();
			}
			else {
				redirect(U('/'));
			}
		}
	}

	public function index() {
		$User = D('User');
		$user = $User->getUser(session('uid'));

		$user[BALANCE] = number_format($user[BALANCE], 2);
		$user[FREEZE] = number_format($user[FREEZE], 2);

		if ($user[ISREALNAME] == 1) {
			$Realname = D('Realname');
			$rname = $Realname->find($user[RID]);
			$user[RNAME] = $rname[NAME];
		}

		C('TOKEN_ON',false);
		$this->assign('user',$user);
		$this->assign('TITLE','User Center');
		$this->display();
	}

	public function transactions() {
		import('ORG.Util.Page');

		$User = D('User');
		$Transaction = D('Transaction');
		$Product = D('Product');

		$count = $Transaction->where('BUID = ' . session('uid'))->count();
		$Page = new Page($count, 1);
		$page = $Page->show();

		$transactions = $Transaction->where('BUID = ' . session('uid'))->order('TIMESTAMP DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($transactions as $key => $value) {
			$product = $Product->find($value[PID]);
			$transactions[$key][PRODUCT] = $product[NAME];
			$transactions[$key][PRICE] = number_format($product[PRICE], 2);
			$transactions[$key][SELLER] = $User->getUsername($value[SUID]);
		}

		$this->assign('transactions', $transactions);
		$this->assign('page', $page);
		$this->assign('TITLE', 'Transaction Record');
		$this->display();
	}

	public function login() {
		// C('TOKEN_ON',false);
		if (IS_POST) {
			$name = $this->_post('name');
			$pwd = $this->_post('pwd');
			if (!empty($name) && !empty($pwd)) {
				// 实例化User类
				$User = D('User');
				$user = $User->where("`USERNAME` = '$name'")->find();
				if (!$user) {
					$this->ajaxReturn('','User not found',0);
				}
				else if (md5($pwd) != $user[PASSWORD]) {
					$this->ajaxReturn('','Password incorrect',0);
				}
				else {
					session('uid',$user[UID]);
					session('username',$user[USERNAME]);
					session('avatar',"http://www.gravatar.com/avatar/" . md5(strtolower(trim($user[EMAIL]))));
					$this->ajaxReturn('','Login success',1);
				}
			}
			else {
				$this->ajaxReturn('','Information not complete',0);
			}
		}
		else {
			// 非POST方式提交时报错
			$this->error('Invalid access');
		}
	}

	public function register() {
		if (IS_POST) {
			// 实例化User类
			$User = D('User');
			if ($User->create()) {
				$uid = $User->add();
				if ($uid) {
					$user = $User->find($uid);
					// 注册成功，写session
					session('uid',$uid);
					session('username',$user[USERNAME]);
					session('avatar',"http://www.gravatar.com/avatar/" . md5(strtolower(trim($user[EMAIL]))));
					$this->ajaxReturn($uid, 'Register success', 1);
				}
				else {
					// 数据库写入失败
					$this->ajaxReturn(0, 'Regiser error', 0);
				}
			}
			else {
				// 表单验证失败
				$this->ajaxReturn('', $User->getError(), 0);
			}
		}
		else {
			// 非POST方式提交时报错
			$this->error('Invalid access');
		}
		// else {
		// 	$this->assign('TITLE','Login - User');
		// 	$this->display('login');
		// }
	}

	public function logout() {
		session('[destroy]');
		redirect(U('/'));
	}

	public function changePwd() {
		if (IS_POST) {
			$User = D('User');
			$user = $User->getUser(session('uid'));
			if (md5($this->_post('oldpwd')) == $user[PASSWORD]) {
				if ($User->setPassword($this->_post('pwd'))) {
					$this->ajaxReturn(0, 'Password changed', 1);
				}
				else {
					$this->ajaxReturn(0, 'Password changing error', 0);
				}
			}
			else {
				$this->ajaxReturn(0, 'Old password is incorrect', 0);
			}
		}
		else {
			// 非POST方式提交时报错
			$this->error('Invalid access');
		}
	}

	public function charge() {
		if (IS_POST) {
			$amount = $this->_post('amount');

			$User = D('User');
			$result = $User->charge($amount);
// dump($result);exit();
			if ($result !== false) {
				$this->ajaxReturn(number_format($result, 2),'Charge success',1);
			}
			else {
				$this->ajaxReturn('','Charge error',0);
			}
		}
		else {
			// 非POST方式提交时报错
			$this->error('Invalid access');
		}
	}

	public function verifyRealname() {
		if (IS_POST) {
			$rid = $this->_post('rid');
			$rname = $this->_post('rname');

			$Realname = D('Realname');
			$User = D('User');

			$user = $User->find(session('uid'));
			if ($user[ISREALNAME] == 0) {
				if ($Realname->verifyRealname($rid, $rname)) {
					$User->where('UID = ' . session('uid'))->setField(array('RID' => $rid, 'ISREALNAME' => 1, 'ISSELLER' => 1));
					$this->ajaxReturn('','Verify success',1);
				}
				else {
					$this->ajaxReturn('','Verify error',0);
				}
			}
			else {
				$this->ajaxReturn('','You\'ve verified',0);
			}
		}
		else {
			// 非POST方式提交时报错
			$this->error('Invalid access');
		}
	}
}