<?php

class SeatModel extends Model {
    protected $_validate    =   array(
        array('DISCOUNT',array(0,100),'Discount should be between 1 and 100.',2,'between'),
        array('PRICE','require','Price required.',1),
        array('AVAILABLE','require','Available nunmber required.',1)
        );
    protected $_auto = array(
		array('RDESCRIPTION','setDescription', 1, 'callback')
	);
	public function setDescription(){
        if ($_REQUEST['SDESCRIPTION'])
        	return($_REQUEST['SDESCRIPTION']);
        else
    		return('This is a normal seat.');
    }
    public function getSeatInfo($uid=0){
    	if ($uid){
    		$data=$this->join('se_flight ON se_seat.FID = se_flight.FID')->where('se_flight.SUID='.$uid)->select();
    		return $data;
    	}
    	else 
    		return NULL;
    }
    public function getAvailable($pid=0){
    	if ($pid) {
    		return $this->where('PID = '.$pid)->getField('AVAILABLE');
    	}
    	else{
    		return NULL;
    	}
    }
    public function subAvailable($pid=0){
    	echo($pid);
    	if ($pid) {
    		return $this->query("UPDATE __TABLE__ SET `AVAILABLE` = `AVAILABLE` - 1 WHERE `PID` = ".$pid);
    	}
    	else{
    		return NULL;
    	}
    }
    public function addAvailable($pid=0){
    	if ($pid) {
    		return $this->query("UPDATE __TABLE__ SET `AVAILABLE` = `AVAILABLE` + 1 WHERE `PID` = ".$pid);
    	}
    	else{
    		return NULL;
    	}
    }
    public function searchByFlightId($fid=0){
        if ($fid) {
            return $this->where('FID = '.$fid)->select();
        }
        else 
            return NULL;
    }
    public function getProductInfo($pid=0){
        if ($pid) {
            $data=$this->join('se_flight ON se_seat.FID = se_flight.FID')->where('se_seat.PID='.$pid)->select();
            return $data;
        }
        else 
            return NULL;
    }
    public function getDiscount(){
        return $this->join('se_flight ON se_seat.FID = se_flight.FID')->where('se_seat.DISCOUNT > 0')->select();
    }

}