<?php
class ComplaintModel extends Model {
	protected $_validate = array(
		array('UID','require','UserID is necessary',1),
		array('TID','require','TransactionID is necessary',1),
		array('TID','/^[1-9][0-9]{0,10}$/','TransactionID format error',1),
		array('TID,UID','','This complaint has been existed',1,'unique',1),
		array('REASON','require','Reason is necessary',1),
		array('REASON','/^.{0,200}$/','Reason format error',1)
	);
	protected $_auto = array(
		array('TIMESTAMP','time',1,'function')
	);
	public function getComplaint($tid)
	{
		if($tid)
		{
			return $this->where('TID = ' . $tid.' AND '.'UID = '. session('uid'))->find();
		}
		else
			return NULL;
	}
}
