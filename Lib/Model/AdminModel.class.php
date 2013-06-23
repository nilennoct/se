<?php
class AdminModel extends Model {

}
/*
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
		array('ROLE','9','User type is wrong, not admin','equal',1)
	);
	protected $_auto = array(
		array('PASSWORD','md5',1,'function')
	);
*/
	/**
	 * get an array of Admin
	 * @param  integer $uid [description]
	 * @return array       user
	 */
/*
	 public function getAdmin($uid = 0) {
		$tmp = $this->where('UID = ' . $uid)->getField('ROLE');
		if ($tmp == 9){
			if ($uid) {
				return $this->find($uid);
			}
			else {
				return NULL;
			}
		}
		else {
			$this->ajaxReturn('','This user is not an admin',0);
		}
	}

	public function getAdminname($uid = 0) {
		$tmp = $this->where('UID = ' . $uid)->getField('ROLE');
		if ($tmp == 9){
			if ($uid) {
				return $this->where('UID = ' . $uid)->getField('USERNAME');
			}
			else {
				return NULL;
			}
		else {
			$this->ajaxReturn('','This user is not an admin',0);
		}
	}
*/
	/**
	 * Set Admin's password
	 * @param String $pwd
	 * @return bool result
	 */
/*
	 public function setPassword($pwd) {
		if (isset($pwd) && !empty($pwd)) {
			return $this->where('UID = ' . session('uid'))->setField('PASSWORD',md5($pwd));
		}
		else {
			return false;
		}
		
	}
*/
