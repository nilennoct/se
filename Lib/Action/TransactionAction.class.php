<?php

class TransactionAction extends PrivilegeAction {
	public function _initialize() {
		parent::_initialize();
	}

	public function index($seller=0){
        $Data_t = D('Transaction'); // 实例化Data数据模型
		$Data_s = D('Seat');
		$Data_r = D('Room');
		$Data_u = D('User');
		$Data_h = D('Hotel');
		$Data_f = D('Flight');
		$this->assign('TITLE', 'Transaction Page');
		//import('ORG.Util.Page');
		if( $_POST )
		{
			$sel_buy = $_POST['sel_buy'];
			$seller = $sel_buy;
			$this->sel_buy = $seller;
			$checkselect = $_POST['checkbox'];
			$starttime = $_POST['starttime'];
			$endtime = $_POST['endtime'];
			$statusnum=count($checkselect);
			for ($i=0; $i<$statusnum; $i++)
			{
				$map=$map.'STATUS = '.$checkselect[$i];
				if($i<$statusnum-1)
					$map=$map.' OR ';
			}

			if($starttime&&$endtime)
				$map2['TIMESTAMP'] = array('between',array(strtotime($starttime),strtotime($endtime)));
			elseif($starttime)
				$map2['TIMESTAMP'] = array('egt',strtotime($starttime));
			elseif($endtime)
				$map2['TIMESTAMP'] = array('elt',strtotime($endtime));
			else
				$map2=array();
			if($seller==0)
				$map2['BUID'] = session('uid');
			elseif($seller==1)
				$map2['SUID'] = session('uid');
			//$count = $Transaction = $Data_t->where($map2)->having($map)->count();
			//$Page = new Page($count,5);
			//$nowPage = isset($_GET['p'])?$_GET['p']:1;
			//$Transaction = $Data_t->where($map2)->having($map)->page($nowPage.','.$Page->listRows)->select();
			$Transaction = $Data_t->where($map2)->having($map)->select();
			//$show = $Page->show();
			//$this->assign('page',$show);
		}
		else
		{
			if($seller==0)
				$Transaction = $Data_t->getBuyerorder(session('uid'));
			elseif($seller==1)
				$Transaction = $Data_t->getSellerorder(session('uid'));
			$this->sel_buy=$seller;
		}
		for($i=0; $i<count($Transaction); $i++)
		{
			$data[$i]['TIME']=$Transaction[$i]['TIMESTAMP'];
			$data[$i]['PRICE']=0;
			if($Transaction[$i]['ROOMORSEAT']==0)
			{
				$Product = $Data_r->where('PID = '.$Transaction[$i]['PID'])->find();
				$Hotel = $Data_h->where('HID = '.$Product['HID'])->find();
				$data[$i]['PRODUCT']=$Hotel['HNAME']." Room";
			}
			elseif($Transaction[$i]['ROOMORSEAT']==1)
			{
				$Product = $Data_s->where('PID = '.$Transaction[$i]['PID'])->find();
				$Flight = $Data_f->where('FID = '.$Product['FID'])->find();
				$data[$i]['PRODUCT']=$Flight['COMPANY']." Flight NO.".$Flight['FNAME'];
			}
			$data[$i]['PRICE'] = $Product['PRICE'];
			$data[$i]['NO']=$Transaction[$i]['TID'];
			$user=$Data_u->getUsername($Transaction[$i]['SUID']);
			$data[$i]['SELLER']=$user;
			$user=$Data_u->getUsername($Transaction[$i]['BUID']);
			$data[$i]['BUYER']=$user;
			switch ($Transaction[$i]['STATUS'])
			{
			case 0:
				$data[$i]['STATUS']='Wait for paying';
				break;
			case 1:
				$data[$i]['STATUS']='Wait for delivering';
				break;
			case 2:
				$data[$i]['STATUS']='Wait for receiving';
				break;
			case 3:
				$data[$i]['STATUS']='Completed';
				break;
			case 4:
				$data[$i]['STATUS']='Canceled';
				break;
			}
		}
		$this->transactions = $data;
		$this->isseller=$Data_u->where('UID = '. session('uid'))->getField('ISSELLER');
		$this->bsswitch=!$seller;
        $this->display();
    }

	public function complaint_result($TID=0){
		$this->TITLE='Complaint_result Page';
		$Data_t = D('Transaction'); // 实例化Data数据模型
		$Data_u = D('User');
		$Data_c = D('Complaint');
		$data=$Data_t->getOrder($TID);
		$buyer=$Data_u->getUsername($data[BUID]);
		$seller=$Data_u->getUsername($data[SUID]);
		$complaint=$Data_c->getComplaint($TID);
		$user=$Data_u->where('UID = '.session('uid'))->find();
		$this->complaint_TID=$TID;
		$this->complaint_BUYER=$buyer;
		$this->complaint_SELLER=$seller;
		$this->complaint_TIME=$complaint['TIMESTAMP'];
		$this->complaint_USER=$user['USERNAME'];
		$this->complaint_REASON=$complaint['REASON'];
		switch ($complaint['STATUS'])
		{
		case 0:
			$this->complaint_STATUS='processing';
			break;
		case 1:
			$this->complaint_STATUS='processed';
			break;
		}
		$this->display();
	}

	public function order($TID=0){
		$this->TITLE='Order Page';
		$Data_t = D('Transaction'); // 实例化Data数据模型
		$Data_u = D('User');
		$Data_c = D('Complaint');
		$Data_s = D('Seat');
		$Data_r = D('Room');
		$Data_h = D('Hotel');
		$Data_f = D('Flight');
		$Transaction=$Data_t->getOrder($TID);
		$Seller=$Data_u->getUsername($Transaction[SUID]);
		$Buyer=$Data_u->getUsername($Transaction[BUID]);
		if($Transaction['ROOMORSEAT']==0)
		{
			$Product = $Data_r->where('PID = '.$Transaction['PID'])->find();
			$Hotel = $Data_h->where('HID = '.$Product['HID'])->find();
			$Name=$Hotel['HNAME']." Room";
		}
		elseif($Transaction['ROOMORSEAT']==1)
		{
			$Product = $Data_s->where('PID = '.$Transaction['PID'])->find();
			$Flight = $Data_f->where('FID = '.$Product['FID'])->find();
			$Name=$Flight['COMPANY']." Flight NO.".$Flight['FNAME'];
		}
		$Price = $Product['PRICE'];
		$time=$Transaction['TIMESTAMP'];
		$State=$Transaction['STATUS'];
		switch ($State)
		{
			case 0:
				$this->Order_STATUS='Wait for paying';
				break;
			case 1:
				$this->Order_STATUS='Wait for delivering';
				break;
			case 2:
				$this->Order_STATUS='Wait for receiving';
				break;
			case 3:
				$this->Order_STATUS='Completed';
				break;
			case 4:
				$this->Order_STATUS='Canceled';
				break;
		}
		if(session('uid')==$Transaction[SUID])
			$this->state=1;
		else if(session('uid')==$Transaction[BUID])
			$this->state=0;
		else
			$this->error('Error!');
		if($Data_c->getComplaint($TID)!=NULL)
			$this->iscomplanit=1;
		else
			$this->iscomplanit=0;
		$this->Order_TIME = $time;
		$this->Order_TID = $TID;
		$this->Order_SELLER = $Seller;
		$this->Order_BUYER = $Buyer;
		$this->Order_PRODUCT = $Name;
		$this->Order_PRICE = $Price;
		$this->order_state = $State;
		$this->display();
	}

	public function complaint($TID=1){
		$this->TITLE='Complaint Page';
		$Data_c = D('Complaint'); // 实例化Data数据模型
		if( $_POST )
		{
			$data = $_POST;
			$data['UID']=session('uid');
			if($Data_c->create($data)){
				$result = $Data_c->add();
				if($result){
					$TID=$_POST['TID'];
					$this->success('Complaint Successed!','__URL__/order/TID/'.$TID);
				}else{
					$this->error('Complaint Error!');
				}
			}
			else {
				$this->error($Data_c->getError());
			}
		}
		else
		{
			$this->OrderNumber=$TID;
			$this->TID=$TID;
			$this->display();
		}
	}

	public function createorder(){
		C('TOKEN_ON',false);
		if($_REQUEST){
			$data=$_REQUEST;
			$data['BUID'] = session('uid');
			$Data_t=D('Transaction');
			if($Data_t->create($data)){
				$result = $Data_t->add();
				if($result){
					$this->success('Book Successed!','__APP__/Transaction/');
				}else{
					$this->error('Book Error!');
				}
			}
			else {
				$this->error($Data_t->getError());
			}
		}
	}

	public function payment(){
		if($_POST)
		{
			$tid=$_POST['TID'];
			$Data_t=D('Transaction');
			$transaction = $Data_t->find($tid);
			// dump($transaction);
			$Data_p=D($transaction[ROOMORSEAT] == 0 ? 'Room' : 'Seat');
			$Data_u=D('User');
			$pid=$Data_t->where('TID = '.$tid)->getfield('PID');
			$price=$Data_p->where('PID = '.$pid)->getfield('PRICE');
			$mymoney=$Data_u->where('UID = '.session('uid'))->getfield('BALANCE');
			// dump($price);
			// dump($mymoney);
			// exit();
			if($price>$mymoney)
				$this->ajaxReturn('','your money is not enough',0);
			else
			{
				$result=$Data_u->balanceToFreeze($price);
				$data['STATUS']=1;
				$data['TID']=$tid;
				$Data_t->save($data);
				$this->ajaxReturn('','Trading success',1);
			}
		}
	}

	public function changestate(){
		if($_POST)
		{
			$tid=$_POST['TID'];
			$Data_t=D('Transaction');
			$Data_p=D($transaction[ROOMORSEAT] == 0 ? 'Room' : 'Seat');
			$Data_u=D('User');
			$transaction = $Data_t->where('TID = '.$tid)->find();
			$state = $transaction['STATUS'];
			if($state==2)
			{
				$pid=$transaction['PID'];
				$price=$Data_p->where('PID = '.$pid)->getfield('PRICE');
				$nowfreeze=$Data_u->where('UID = '.session('uid'))->getfield('FREEZE');
				$nowfreeze=$nowfreeze-$price;
				$data['UID'] = session('uid');
				$data['FREEZE']=$nowfreeze;
				$result=$Data_u->save($data);
				$seller=$transaction['SUID'];
				$money=$Data_u->where('UID = '.$seller)->getfield('BALANCE');
				$data2['UID'] = $seller;
				$data2['BALANCE']=$money+$price;
				$result=$Data_u->save($data2);
				if(!$result)
					$this->ajaxReturn('','Operation failed',0);
			}
			$data3['TID']=$tid;
			$state = $state+1;
			$data3['STATUS']=$state;
			$result=$Data_t->save($data3);
			if($result)
				$this->ajaxReturn('','Operation successed',1);
			else
				$this->ajaxReturn('','Operation failed',0);
		}
	}
}
