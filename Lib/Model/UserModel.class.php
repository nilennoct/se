<?php
class UserModel extends Model {
	protected $_map = array(
		'name' => 'USERNAME',
		'pwd' => 'PASSWORD',
		'email' => 'EMAIL'
	);
	protected $_validate = array(
		array('USERNAME','require','Username is necessary',1),
		array('PASSWORD','require','Password is necessary',1),
		array('EMAIL','require','Email is necessary',1),
		array('USERNAME','','Username has been used',1,'unique',1),
		array('EMAIL','','Email has been used',1,'unique',1),
		array('USERNAME','/^\w{4,20}$/','Username format error',1),
		array('EMAIL','email','Email format error',1)
	);
	protected $_auto = array(
		array('PASSWORD','md5',1,'function')
	);

	public function getUser($uid = 0) {
		if ($uid) {
			return $this->find($uid);
		}
		else {
			return NULL;
		}
	}

	public function setPassword($pwd) {
		if (isset($pwd) && !empty($pwd)) {
			return $this->where('UID = ' . session('uid'))->setField('PASSWORD',md5($pwd));
		}
		else {
			return false;
		}
	}
}