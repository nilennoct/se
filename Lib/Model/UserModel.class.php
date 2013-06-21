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

	/**
	 * get an array of User
	 * @param  integer $uid [description]
	 * @return array       user
	 */
	public function getUser($uid = 0) {
		if ($uid) {
			return $this->find($uid);
		}
		else {
			return NULL;
		}
	}

	public function getUsername($uid = 0) {
		if ($uid) {
			return $this->where('UID = ' . $uid)->getField('USERNAME');
		}
		else {
			return NULL;
		}
	}

	/**
	 * Set user's password
	 * @param String $pwd
	 * @return bool result
	 */
	public function setPassword($pwd) {
		if (isset($pwd) && !empty($pwd)) {
			return $this->where('UID = ' . session('uid'))->setField('PASSWORD',md5($pwd));
		}
		else {
			return false;
		}
	}

	/**
	 * charge user's account
	 * @param  float $a Amount to charge
	 * @return float|bool    Charge result
	 */
	public function charge($a) {
		$result = $this->where('UID = ' . session('uid'))->setInc('BALANCE', $a);
		if ($result) {
			return $this->where('UID = ' . session('uid'))->getField('BALANCE');
		}
		else {
			return false;
		}
	}

	/**
	 * Freeze user's money
	 * @param  float $a Amount to freeze
	 * @return array|bool    Freeze result
	 */
	public function balanceToFreeze($a) {
		$result = $this->query("UPDATE __TABLE__ SET `BALANCE` = `BALANCE` - $a, `FREEZE` = `FREEZE` + $a WHERE `uid` = " . session('uid'));
		if ($result) {
			$user = $this->find(session('uid'));
			return array(
				'BALANCE'	=> $user[BALANCE],
				'FREEZE'	=> $user[FREEZE]
			);
		}
		else {
			return false;
		}
	}

	public function isSeller($uid) {
		$user = $this->find($uid);
		return $user[ISSELLER] == 1;
	}
}