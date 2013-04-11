<?php
class UserAction extends Action {
	public function login() {
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
				// 插入数据
				$uid = $User->add();
				if ($uid) {
					// 注册成功，写session
					session('uid',$uid);
					session('username',$User->name);
					session('avatar',"http://www.gravatar.com/avatar/" . md5(strtolower(trim($User->email))));
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
	}
}