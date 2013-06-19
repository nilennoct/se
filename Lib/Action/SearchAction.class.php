<?php
class SearchAction extends Action{
	public function index(){
		$this->assign('TITLE','Search System');
		$this->display();
	}
    public function showResults(){
        $this->assign('TITLE','Search');
    	$hname = $_POST['hname'];
        $hdetail = $_POST['hdetail'];
    	$fname = $_POST['fname'];
        $fdetail = $_POST['fdetail'];
    	if ($hname){
            $horder = $_POST['horder'];
    		$Hotel = D('Hotel');
    		$this->hinfo = $Hotel->searchByName($hname, $horder);
    		$this->hview = 1;
    	}
        if ($hdetail){
            $con->name = $_POST['name'];
            $con->star = $_POST['star'];
            $con->address = $_POST['address'];
            $con->total = $_POST['total'];
            $con->average = $_POST['average'];
            $con->order = $_POST['order'];
            $Hotel = D('Hotel');
            $this->hinfo = $Hotel->searchByConditions($con);
            $this->hview = 1;
        }
        if ($hname || $hdetail){
            $Room = D('Room');
            $this->dinfo = $Room->getDiscount();
        }
        if ($fdetail){
            $con->name = $_POST['name'];
            $con->company = $_POST['company'];
            $con->total = $_POST['total'];
            $con->average = $_POST['average'];
            $con->departtime = $_POST['departtime'];
            $con->arrivetime = $_POST['arrivetime'];
            $con->starting = $_POST['starting'];
            $con->destination = $_POST['destination'];
            $con->order = $_POST['order'];
            $Flight = D('Flight');
            $this->finfo = $Flight->searchByConditions($con);
            $this->fview = 1;
        }
    	if ($fname){
            $forder = $_POST['forder'];
    		$Flight = D('Flight');
    		$this->finfo = $Flight->searchByName($fname, $forder);
    		$this->fview = 1;
    	}
        if ($fname || $fdetail){
            $Seat = D('Seat');
            $this->dinfo = $Seat->getDiscount();
        }
		$this->display();
    }
    public function showDetails(){  
        $this->assign('TITLE','Search');              
    	$hid = $_GET['hid'];
    	$fid = $_GET['fid'];
    	if ($hid){
    		$Room = D('Room');
    		$this->rinfo = $Room->searchByHotelId($hid);
    		$Hotel = D('Hotel');
    		$this->hinfo = $Hotel->searchById($hid);
    		$Transaction = D('Transaction');
    		$tinfo = $Transaction->searchByHid($hid);
            $count=0;
            foreach ($tinfo as $i){
                $tinfo[$count]['DATETIME'] = date('Y-m-d H:i:s', $tinfo[$count]['TIMESTAMP']);
                $count++;
            }
            $this->tinfo = $tinfo;
    		$this->roomorseat = 0;
    	}
    	if ($fid){
    		$Seat = D('Seat');
    		$this->sinfo = $Seat->searchByFlightId($fid);
    		$Flight = D('Flight');
    		$this->finfo = $Flight->searchById($fid);
    		$Transaction = D('Transaction');
    		$tinfo = $Transaction->searchByHid($hid);
            $count=0;
            foreach ($tinfo as $i){
                $tinfo[$count]['DATETIME'] = date('Y-m-d H:i:s', $tinfo[$count]['TIMESTAMP']);
                $count++;
            }
            $this->tinfo = $tinfo;
    		$this->roomorseat = 1;
    	}
    	$this->display();
    }
}
