<?php
class SellerAction extends Action{
    public function index(){
        $this->assign('TITLE','Seller Operations');
        $User = D('User');
        if (!$User->isSeller(session('uid'))){
            $this->error('Sorry, you are not a qualified seller!','__APP__/User');
        }
        else{
            $this->display();
        }
    }
    public function insertHotel(){
        $temp = D('Hotel');
        if ($temp->create()) {
            $result = $temp->add();
            if ($result) {
                $this->success('Operation Succeeded!', '__URL__');
            }
            else {
                $this->error('Operation Failed!', '__URL__/addHotel');
            }
        }else{
            $this->error($temp->getError());
        }
    }
    public function viewHotel(){
        $this->assign('TITLE','Edit hotels');
        $id = session('uid');
        $temp = D('Hotel');
        $this->info = $temp->getHotelInfo($id);
        $this->display();
    }
    public function editHotel(){
    	$temp = D('Hotel');
        if ($temp->create()) {
            $result = $temp->save();
            if ($result) {
                $this->success('Operation Succeeded!', '__URL__');
            }
            else {
                $this->error('Operation Failed!', '__URL__/editHotel');
            }
        }
        else{
            $this->error($temp->getError());
        }
    }
    public function deleteHotel(){
        $temp = M('Hotel');
        $flag = $temp->delete($_GET['HID']);
        if($flag) {
            $this->success('Operation Succeeded!','__URL__');
        }
        else{
            $this->error('Operation Failed!','__URL__/editHotel');
        }
    }
//-----------------------------------------------------------------------------//
    public function insertRoom(){
        $hotel = D('Hotel');
        $hid = $hotel->getHotelId($_POST['HNAME']);
        $temp = D('Room');
        if (!$hid){
            $this->error('This is not your hotel!', '__URL__/addRoom');
        }
        if ($temp->create()) {
            $temp->HID = $hid;
            $result = $temp->add();
            if ($result) {
                $this->success('Operation Succeeded!', '__URL__');
            }
            else {
                $this->error('Operation Failed!', '__URL__/addRoom');
            }
        }else{
            $this->error($temp->getError());
        }
    }
    public function viewRoom(){
        $this->assign('TITLE','Edit rooms');
        $id = session('uid');
        $temp = D('Room');
        $this->info = $temp->getRoomInfo($id);
        $this->display();
    }
    public function editRoom(){
        $temp = D('Room');
        if ($temp->create()) {
            $result = $temp->save();
            if ($result) {
                $this->success('Operation Succeeded!', '__URL__');
            }
            else {
                $this->error('Operation Failed!', '__URL__/editRoom');
            }
        }
        else{
            $this->error($temp->getError());
        }
    }
    public function deleteRoom(){
        $temp = M('Room');
        $flag = $temp->delete($_GET['PID']);
        if($flag) {
            $this->success('Operation Succeeded!','__URL__');
        }
        else{
            $this->error('Operation Failed!','__URL__/editRoom');
        }
    }
//-----------------------------------------------------------------------------//
    public function insertFlight(){
        $temp = D('Flight');
        if ($temp->create()) {
            $result = $temp->add();
            if ($result) {
                $this->success('Operation Succeeded!', '__URL__');
            }
            else {
                $this->error('Operation Failed!', '__URL__/insertFlight');
            }
        }else{
            $this->error($temp->getError());
        }
    }
    public function viewFlight(){
        $this->assign('TITLE','Edit flights');
        $id = session('uid');
        $temp = D('Flight');
        $this->info = $temp->getFlightInfo($id);
        $this->display();
    }
    public function editFlight(){
        $temp = D('Flight');
        if ($temp->create()) {
            $result = $temp->save();
            if ($result) {
                $this->success('Operation Succeeded!', '__URL__');
            }
            else {
                $this->error('Operation Failed!', '__URL__/editFlight');
            }
        }
        else{
            $this->error($temp->getError());
        }
    }
    public function deleteFlight(){
        $temp = M('Flight');
        $flag = $temp->delete($_GET['FID']);
        if($flag) {
            $this->success('Operation Succeeded!','__URL__');
        }
        else{
            $this->error('Operation Failed!','__URL__/editFlight');
        }
    }
//-----------------------------------------------------------------------------//
   public function insertSeat(){
        $Flight = D('Flight');
        $fid = $Flight->getFlightId($_POST['FNAME']);
        $temp = D('Seat');
        if (!$fid){
            $this->error('This is not your flight!', '__URL__/addSeat');
        }
        if ($temp->create()) {
            $temp->FID = $fid;
            $result = $temp->add();
            if ($result) {
                $this->success('Operation Succeeded!', '__URL__');
            }
            else {
                $this->error('Operation Failed!', '__URL__/addSeat');
            }
        }else{
            $this->error($temp->getError());
        }
    }
    public function viewSeat(){
        $this->assign('TITLE','Edit seats');
        $id = session('uid');
        $temp = D('Seat');
        $this->info = $temp->getSeatInfo($id);
        $this->display();
    }
    public function editSeat(){
        $temp = D('Seat');
        if ($temp->create()) {
            $result = $temp->save();
            if ($result) {
                $this->success('Operation Succeeded!', '__URL__');
            }
            else {
                $this->error('Operation Failed!', '__URL__/editSeat');
            }
        }
        else{
            $this->error($temp->getError());
        }
    }
    public function deleteSeat(){
        $temp = M('Seat');
        $flag = $temp->delete($_GET['PID']);
        if($flag) {
            $this->success('Operation Succeeded!','__URL__');
        }
        else{
            $this->error('Operation Failed!','__URL__/editSeat');
        }
    }    
}