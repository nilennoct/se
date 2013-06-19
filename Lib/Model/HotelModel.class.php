<?php

class HotelModel extends Model {
    protected $_validate    =   array(
        array('HNAME','require','Hotel name required.',1),
        array('STAR','require','Star level required.',1),
        array('STAR','/^[0-5]/','Star level should be between 0 and 5.',1),
        array('ADDRESS','require','Address required.',1)
        );
    protected $_auto = array(
		array('DESCRIPTION','setDescription', 3, 'callback'),
		array('SUID', 'getUserId', 1, 'callback')
	);
    public function getUserId(){
    	if (session('uid'))
    		return session('uid');
    	else
    		return NULL;
    }
    public function setDescription(){
        if ($_REQUEST['DESCRIPTION'])
        	return($_REQUEST['DESCRIPTION']);
        else
    		return('This is a normal hotel.');
    }
    public function getHotelInfo($uid=0){
    	if ($uid)
    		return $this->where('SUID = '.$uid)->select();
    	else
    		return NULL;
    }
    public function getHotelId($hname=''){
    	if ($hname && ($data=$this->where('HNAME = \''.$hname.'\' and SUID = '.session('uid'))->getField('HID')))
    		return $data;
    	else
    		return NULL;
    }
    public function searchByName($hname='', $horder=''){
        if ($hname){
            switch ($horder){
                case 'Popularity': $order='TOTAL desc';break;
                case 'Star level': $order='STAR desc';break;
                case 'Score': $order='AVERAGE desc';break;
                default: $order='';break;
            }
            $map['HNAME'] = array('like', '%'.$hname.'%');
            return $this->where($map)->order($order)->select();
        }
        else 
            return NULL;
    }
    public function searchById($hid=0){
        if ($hid){
            return $this->find($hid);
        }
        else 
            return NULL;
    }
    public function addScore($hid=0, $score=0){
        if ($hid){
            $hinfo = $this->find($hid);
            $navg = ($hinfo['AVERAGE']*$hinfo['TOTAL']+$score)/($hinfo['TOTAL']+1);
            $this->query("UPDATE __TABLE__ SET `AVERAGE` = ".$navg." WHERE `HID` = ".$hid);
            $this->query("UPDATE __TABLE__ SET `TOTAL` = `TOTAL` + 1 WHERE `HID` = ".$hid);
            return true;
        }
        else 
            return false;
    }
    public function searchByConditions($con=0){
        if ($con){
            if ($con->name)
                $map['HNAME'] = array('like', '%'.$con->name.'%');
            if ($con->star)
                $map['STAR'] = array(array('gt',$con->star-2),array('lt', $con->star+2));
            if ($con->address)
                $map['ADDRESS'] =array('like', '%'.$con->address.'%');
            if ($con->total)
                $map['TOTAL'] = array('egt',$con->total);
            if ($con->average)
                $map['AVERAGE'] = array('egt',$con->average);
            switch ($con->order){
                case 'Popularity': $order='TOTAL desc';break;
                case 'Star level': $order='STAR desc';break;
                case 'Score': $order='AVERAGE desc';break;
                default: $order='';break;
            }
            return $this->where($map)->order($order)->select();
        }
        else
            return NULL;
    }

}