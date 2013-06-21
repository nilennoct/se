<?php
class RealnameModel extends Model {
	public function verifyRealname($rid = 0, $rname = '') {
		$realname = $this->find($rid);

		if (!$realname || $realname[VERIFIED] == 1 || $realname['NAME'] != $rname) {
			return false;
		}
		else {
			$this->where("RID = $rid")->setField('VERIFIED',1);
			return true;
		}
	}
}