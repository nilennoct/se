<?php

class FlightModel extends Model {
    protected $_validate = array(
        array('FNAME','require','Flight number required.',1),
        array('FNAME','/^[A-Z][A-Z][0-9][0-9][0-9][0-9]$/','Flight number format error.',1),
        array('COMPANY','require','Company required.',1),
        array('DEPARTTIME','require', 'Depart time required.',1),
        array('DEPARTTIME','/^([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/','Depart time format error.',1),
        array('ARRIVETIME','require', 'Arrive time required.',1),
        array('ARRIVETIME','/^([0-1][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/','Arrive time format error.',1),
        array('STARTING','require','Starting city required.',1),
        array('DESTINATION','require','Destination city required.',1)
        );
    protected $_auto = array(
    	array('SUID', 'getUserId', 1, 'callback')
    	);
    public function getUserId(){
    	if (session('uid'))
    		return session('uid');
    	else
    		return NULL;
    }
    public function getFlightInfo($uid=0){
    	if ($uid)
    		return $this->where('SUID = '.$uid)->select();
    	else
    		return NULL;
    }
    public function getFlightId($fname=''){
    	if ($fname && ($data=$this->where('FNAME = \''.$fname.'\' and SUID = '.session('uid'))->getField('FID')))
    		return $data;
    	else
    		return NULL;
    }
    public function searchByName($fname='', $forder=''){
        if ($fname){
            switch ($forder){
                case 'Popularity': $order='TOTAL';break;
                case 'Score': $order='AVERAGE';break;
                default: $order='';break;
            }
            $map['FNAME'] = array('like', '%'.$fname.'%');
            return $this->where($map)->order($order.' desc')->select();
        }
        else 
            return NULL;
    }
    public function searchById($fid=0){
        if ($fid){
            return $this->find($fid);
        }
        else 
            return NULL;
    }
    public function addScore($fid=0, $score=0){
        if ($fid){
            $finfo = $this->find($fid);
            $navg = ($finfo['AVERAGE']*$finfo['TOTAL']+$score)/($finfo['TOTAL']+1);
            $this->query("UPDATE __TABLE__ SET `AVERAGE` = ".$navg." WHERE `FID` = ".$fid);
            $this->query("UPDATE __TABLE__ SET `TOTAL` = `TOTAL` + 1 WHERE `FID` = ".$fid);
            return true;
        }
        else 
            return false;
    }
    public function searchByConditions($con=0){
        if ($con){
            if ($con->name)
                $map['FNAME'] = array('like', '%'.$con->name.'%');
            if ($con->company)
                $map['COMPANY'] = array('like', '%'.$con->company.'%');
            if ($con->total)
                $map['TOTAL'] = array('egt',$con->total);
            if ($con->average)
                $map['AVERAGE'] = array('egt',$con->average);
            if ($con->departtime)
                $map['DEPARTTIME'] = array(array('egt', $this->getTimeLower($con->departtime)),array('elt', $this->getTimeUpper($con->departtime)));
            if ($con->arrivetime)
                $map['ARRIVETIME'] = array(array('egt', $this->getTimeLower($con->arrivetime)),array('elt', $this->getTimeUpper($con->arrivetime)));
            if ($con->starting)
                $map['STARTING'] = array('like', '%'.$con->starting.'%');
            if ($con->destination)
                $map['DESTINATION'] = array('like', '%'.$con->destination.'%');
            switch ($con->order){
                case 'Popularity': $order='TOTAL desc';break;
                case 'Score': $order='AVERAGE desc';break;
                default: $order='';break;
            }
            return $this->where($map)->order($order)->select();
        }
        else
            return NULL;
    }
    public function getTimeLower($time){
        $arr = strptime($time, '%H:%M:%S');
        $hour = $arr['tm_hour']-2;
        $min = $arr['tm_min'];
        $sec = $arr['tm_sec'];
        if ($hour<0) {
            return ('00:00:00');
        }
        if (strlen($hour)==1)
            $hour='0'.$hour;
        if (strlen($min)==1)
            $min='0'.$min;
        if (strlen($sec)==1)
            $sec='0'.$sec;
        return $hour.':'.$min.':'.$sec;
    }
    public function getTimeUpper($time){
        $arr = strptime($time, '%H:%M:%S');
        $hour = $arr['tm_hour']+2;
        $min = $arr['tm_min'];
        $sec = $arr['tm_sec'];
        if ($hour>23){
            return ('23:59:59');
        } 
        if (strlen($hour)==1)
            $hour='0'.$hour;
        if (strlen($min)==1)
            $min='0'.$min;
        if (strlen($sec)==1)
            $sec='0'.$sec;
        return $hour.':'.$min.':'.$sec;
    }
}