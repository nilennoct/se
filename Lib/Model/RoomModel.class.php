<?php

class RoomModel extends Model {
    protected $_validate    =   array(
        array('DISCOUNT',array(0,100),'Discount should be between 1 and 100.',2,'between'),
        array('PRICE','require','Price required.',1),
        array('AVAILABLE','require','Available nunmber required.',1)
        );
    protected $_auto = array(
		array('RDESCRIPTION','setDescription', 1, 'callback')
	);
	public function setDescription(){
        if ($_REQUEST['RDESCRIPTION'])
        	return($_REQUEST['RDESCRIPTION']);
        else
    		return('This is a normal room.');
    }
    public function getRoomInfo($uid=0){
    	if ($uid){
    		$data=$this->join('se_hotel ON se_room.HID = se_hotel.HID')->where('se_hotel.SUID='.$uid)->select();
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
    public function searchByHotelId($hid=0){
        if ($hid) {
            return $this->where('HID = '.$hid)->select();
        }
        else 
            return NULL;
    }
    public function getProductInfo($pid=0){
        if ($pid) {
            $data=$this->join('se_hotel ON se_room.HID = se_hotel.HID')->where('se_room.PID='.$pid)->select();
            return $data;
        }
        else 
            return NULL;
    }
    public function getDiscount(){
        return $this->join('se_hotel ON se_room.HID = se_hotel.HID')->where('se_room.DISCOUNT > 0')->select();
    }
}