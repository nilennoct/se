<?php
class TransactionModel extends Model {
	protected $_validate = array(
		array('BUID','require','BuyerID is necessary',1),
		array('SUID','require','SellerID is necessary',1),
		array('PID','require','ProductID is necessary',1),
		array('BUID','/^[1-9][0-9]{0,10}$/','BuyerID format error',1),
		array('SUID','/^[1-9][0-9]{0,10}$/','SellerID format error',1),
		array('PID','/^[1-9][0-9]{0,10}$/','ProductID format error',1),
		array('SCORE','/^((\d{0,2})|100)$/','SCORE format error',1),
		array('COMMENT','/^.{0,200}$/','Comment format error',1)
	);
	protected $_auto = array(
		array('TIMESTAMP','time',1,'function')
	);
	public function getBuyerorder($uid)
	{
		if($uid)
		{
			return $this->where('BUID = ' . $uid)->select();
		}
		else
			return NULL;
	}
	public function getSellerorder($uid)
	{
		if($uid)
		{
			return $this->where('SUID = ' . $uid)->select();
		}
		else
			return NULL;
	}
	public function getOrder($tid)
	{
		if($tid)
		{
			return $this->where('TID = ' . $tid)->find();
		}
		else
			return NULL;
	}
	public function searchByHid($hid){
        if ($hid){
            return $this->join('se_room ON se_room.PID = se_transaction.PID')->join('se_hotel ON se_hotel.HID = se_room.HID')->where('se_room.HID = '.$hid.' and se_transaction.ROOMORSEAT = 0')->select();
        }
        else
            return NULL;
    }
    public function searchByFid($fid){
        if ($fid){
            return $this->join('se_seat ON se_seat.PID = se_transaction.PID')->join('se_flight ON se_flight.FID = se_seat.FID')->where('se_seat.FID = '.$fid.' and se_transaction.ROOMORSEAT = 1')->select();
        }
        else
            return NULL;
    }
    public function setComment($tid, $score, $comment){
        if ($tid){
                $this->query("UPDATE __TABLE__ SET `COMMENT` = '".$comment."' WHERE `TID` = ".$tid);
                $this->query("UPDATE __TABLE__ SET `SCORE` = ".$score." WHERE `TID` = ".$tid);
            return true;
        }
        else
            return false;
    }
    public function getTransactionInfo($tid){
        if ($tid){
            return $this->find($tid);
        }
        else
            return NULL;
    }
}
