<?php
class AdminAction extends PrivilegeAction {

	public function _initialize() {
		parent::_initialize();
	}

	public function index(){
		$Admin = M('Admin');
		$condition[AID] = session('aid');
		$admin = $Admin->where($condition)->find();

		C('TOKEN_ON',false);
		$this->assign('admin',$admin);
		$this->assign('TITLE','Admin Center');
		$this->display();
	}


	//管理员登陆
	public function login() {
		$this->display();
	}

	//管理员登录时验证其账号密码
	public function do_login() {
		// C('TOKEN_ON',false);
			$name = $this->_post('name');
			$pwd = $this->_post('pwd');

			if (!empty($name) && !empty($pwd)) {
				// 实例化User类
				$Admin = D('Admin');
				$user = $Admin->where("`ADMINNAME` = '$name'")->find();
				if (!$user) {
					$this -> error('Admin not found!');
				}
				else if (md5($pwd) != $user[PASSWORD]) {
					$this -> error('Wrong password!');
				}
				else {
					session('aid',$user[AID]);
					session('adminname',$user[ADMINNAME]);
					session('avatar',"http://www.gravatar.com/avatar/" . md5(strtolower(trim($user[EMAIL]))));

					$this -> success('login success!',U('Admin/index'));
				}
			}
			else {
				$this -> error('login failed!');
			}
	}

	//增加一个管理员账户的界面
	public function add_admin(){
		$this->display();
	}

	//增加一个用户的界面
	public function add_user() {
		$this->display();
	}

	//添加一个管理员账户的后台
    public function do_add_admin(){
        $Admin   =   M('Admin');
		$data[ADMINNAME] = $_POST['adminname'];
		$data[PASSWORD] = md5($_POST['password']);
		$data[EMAIL] = $_POST['email'];
        $result =   $Admin->add($data);
        if($result) {
            $this->success('增加管理员成功！');
        }else{
            $this->error('增加管理员失败！');
        }
    }

	//增加一个用户的后台
	public function do_add_user() {
		$this->error('需要接口');
	}


	//列出所有用户的信息
	public function list_user(){
    $user   =   M('User');
    $this->data =   $user->select();
    $this->display();
	}

	//列出所有管理员的信息
	public function list_admin(){
    $admin   =   M('Admin');
    $this->data =   $admin->select();
    $this->display();
	}

	//列出通过实名制认证的所有用户
	public function list_realname() {
		$user = M('User');
		$condition[ISREALNAME] = 1;
		$this->data =   $user->where($condition)->select();
		$this->display();
	}

/*
	//实名制认证
	public function verifyRealname(){
		$real   =   M('Realname');
		$rid = $_POST['rid'];
		$rname = $_POST['rname'];
		$uname = $_POST['uname'];

		$tmpname = $real->where('RID='.$rid)->getField('NAME');
		if($tmpname == $rname){
			$user   =   M('User');
			$condition['USERNAME'] = $uname;
			$user->where($condition)->setField('ISREALNAME',1);
			$this->ajaxReturn($uname, 'Realname verify success', 1);
		}
		else{
			$this->ajaxReturn(0, 'Realname verify failed', 0);
		}

		$this->display();
	}
*/

	//列出所有黑名单中的用户
	public function list_black() {
		$user = M('User');
		$condition[ROLE] = 4;
		$this->data =   $user->where($condition)->select();
		$this->display();
	}

	public function add_blacklist() {	$this->display();	}
	public function sub_blacklist() {	$this->display();	}

	//添加一个用户到黑名单
	public function do_add_blacklist() {
		$user = M('User');
		$uname = $_POST['username'];
		$condition['USERNAME'] = $uname;
		$result = $user->where($condition)->setField('ROLE',4);

		if($result){
			$this->success('该用户加入黑名单成功！');
		}
		else{
			$this->error('该用户已经在黑名单中了！');
		}
	}

	//从黑名单删除一个用户
	public function do_sub_blacklist() {
		$user = M('User');
		$uname = $_POST['username'];
		$condition['USERNAME'] = $uname;
		$result = $user->where($condition)->setField('ROLE',0);

		if($result){
			$this->success('该用户已从黑名单中删除！');
		}
		else{
			$this->error('该用户不在黑名单中！');
		}
	}

	public function add_vip() {	$this->display();	}
	public function sub_vip() {	$this->display();	}

	//设置一个用户为VIP
	public function do_add_vip() {
		$user = M('User');
		$uname = $_POST['username'];
		$condition['USERNAME'] = $uname;
		$result = $user->where($condition)->setField('ROLE',1);

		if($result){
			$this->success('该用户已升级为VIP！');
		}
		else{
			$this->error('该用户已经是VIP了！');
		}
	}

	//取消一个用户的VIP身份
	public function do_sub_vip() {
		$user = M('User');
		$uname = $_POST['username'];
		$condition['USERNAME'] = $uname;
		$result = $user->where($condition)->setField('ROLE',0);

		if($result){
			$this->success('该用户已从降级为普通用户！');
		}
		else{
			$this->error('该用户不是VIP！');
		}
	}

	//登出
	public function logout() {
		session('[destroy]');
		redirect(U('/'));
	}

	//修改密码的前端页面
	public function change_pwd() {
		$this->display();
	}

	//修改密码的后台
	public function do_change_pwd() {

			$opwd = $_POST['opwd'];
			$npwd = $_POST['npwd'];

			$admin = M('Admin');
			$condition['ADMINNAME'] = session('adminname');
			$truepwd = $admin->where($condition)->getField('PASSWORD');

			if (md5($opwd) == $truepwd) {
				if($admin->where($condition)->setField('PASSWORD',md5($npwd))) {
					$this->success('Password changed' ,U('Admin/index'));
				}
				else {
					$this->error('Password changing error');
				}
			}
			else {
				$this->error('Old password is incorrect');
			}
	}

	//列出所有投诉的详情
	public function list_complaint() {
		$com   =   M('Complaint');
		$this->data =   $com->select();
		$this->display();
	}
}