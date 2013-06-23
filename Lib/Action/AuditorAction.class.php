<?php
class AuditorAction extends Action {
	public function _initialize() {
		if ( ! session('uid') && ACTION_NAME != 'login' && ACTION_NAME != 'register') {
			redirect(U('/'));
		}
		$this->assign('TITLE','Auditor Center');
	}
	public function index() {
		$User = D('User');
		$user = $User->getUser(session('uid'));

		C('TOKEN_ON',false);
		$this->assign('user',$user);
		$this->assign('TITLE','Auditor Center');
		$this->display();
	}
	public function unchecked(){
		$Data = M('Transaction');
		$PData = M('Product');
		import('ORG.Util.Page');
		$map['CHECK'] = 0;
		$map['STATUS'] = 3;
		$map['TIMESTAMP'] = array('lt',mktime(0,0,0,date("m"),date("d"),date("Y")));
		$count      = $Data->where($map)->count();
		$Page       = new Page($count);
		$nowPage = isset($_GET['p'])?$_GET['p']:1;
		$list = $Data->where($map)->page($nowPage.','.$Page->listRows)->select();
		foreach($list as $k1=>$v1){
			$product = $PData->find($list[$k1]["PID"]);
			$list[$k1]["PRICE"]=$product["PRICE"];
			$list[$k1]["STATUS"]="Complete";
			$list[$k1]["BUTTON"]="icon-ok";
		}
		$show       = $Page->show();
		$this->assign('page',$show);
		$this->assign('list',$list);		
		$this->display();
	}
	public function logError(){
		if($this->isPost()){
			$Data = M('Transaction');
			$Alog = M('Alog');
			$tid=$_POST['tid'];
			$result=$Data->where("`TID` = '$tid'")->find();
			$log['LID']=null;
			$log['TID']=$tid;
			$log['AUDITOR']=session('uid');
			$log['TIME']=time();
			$log['MONEY']=$result['PAID']-$result['PRICE'];
			$Alog->add($log);
			$result['PAID']=$result['PRICE'];
			if(!$Data->where("`TID`= '$tid'")->save($result)){
				$this->ajaxReturn('','fail',0);
			}
			else{
				$this->ajaxReturn('','success',1);
			}
		}
		else{
			$this->error('Invalid access');
		}
	}
	public function checked(){
		$Data = M('Transaction');
		$PData = M('Product');
		import('ORG.Util.Page');
		$sel_date=$_GET['sel_date'];
		$url="<a href=# class=\"btn\">";
		$map['TIMESTAMP']=array("lt",0);
		$map['CHECK']=1;
		if(!empty($sel_date)){
			sscanf($sel_date,"%d-%d-%d - %d-%d-%d",$month_start,$day_start,$year_start,$month_end,$day_end,$year_end);
			$map['TIMESTAMP']=array('between',array(mktime(0,0,0,$month_start,$day_start,$year_start),mktime(24,0,0,$month_end,$day_end,$year_end)));
			$url="<a href='".str_replace("checked","export",__SELF__)."' class=\"btn\">";
		}		
		$count      = $Data->where($map)->count();
		$Page       = new Page($count);
		$nowPage = isset($_GET['p'])?$_GET['p']:1;
		$list = $Data->where($map)->page($nowPage.','.$Page->listRows)->select();
		foreach($list as $k1=>$v1){
			$product = $PData->find($list[$k1]["PID"]);
			$list[$k1]["STATUS"]="Complete";
			$list[$k1]["PRICE"]=$product["PRICE"];
		}
		$show       = $Page->show();
		$this->assign('page',$show);
		$this->assign('list',$list);
		
		$this->assign('export',$url);
		$this->display();
	}
	public function singleCheck(){
		if($this->isPost()){
			$Data = M('Transaction');
			$tid=$_POST['tid'];
			$data['CHECK']="1";
			$data['AUDITOR']=session('uid');
			//$data['CTIME']=time();
			if(!$Data->where('TID='.$tid)->save($data)){
				$this->ajaxReturn('','fail',0);
			}
			else{
				$this->ajaxReturn('','success',1);
			}
		}
		else{
			$this->error('Invalid access');
		}
	}

	public function check(){
		if(IS_POST){
			$Data = M('Transaction');
			$map['CHECK'] = 0;
			$map['STATUS'] = 3;
			$map['TIMESTAMP'] = array('lt',mktime(0,0,0,date("m"),date("d"),date("Y")));
			$num = $Data->where($map)->count();
			$result=$Data->where($map)->select();
			foreach($result as $k1=>$v1){
				$data['CHECK']="1";
				$data['AUDITOR']=session('uid');
				//$data['CTIME']=time();
				$Data->where('TID='.$result[$k1]["TID"])->save($data);
			}
			$result=$Data->where($map)->find();
			if(!empty($result)){
				$this->ajaxReturn('','There is at least one ERROR transaction!!	Please deal with it!!',0);
			}
			else{				
				$this->ajaxReturn('','Verified Successfully! '.$num." records are Verified.",1);
			}
			
		}
		else{
			$this->error('Invalid access');
		}
	}
	public function export(){
		import ("ORG.PHPExcel.PHPExcel");
		$Data = M('Transaction');
		$sel_date=$_GET['sel_date'];
		sscanf($sel_date,"%d-%d-%d - %d-%d-%d",$month_start,$day_start,$year_start,$month_end,$day_end,$year_end);
		$map['TIMESTAMP']=array('between',array(mktime(0,0,0,$month_start,$day_start,$year_start),mktime(24,0,0,$month_end,$day_end,$year_end)));
		$map['CHECK']=1;
		$list = $Data->where($map)->select();
		$objPHPExcel = new PHPExcel();
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("Auditor xx")
							 ->setLastModifiedBy("Auditor xx")
							 ->setTitle("Transaction outport")
							 ->setSubject("Transaction outport")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
		// Set default font
		$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')
                                          ->setSize(10);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Trading Time')
								->setCellValue('B1', 'Transaction ID')
								->setCellValue('C1', 'Buyer')
								->setCellValue('D1', 'Seller')
								->setCellValue('E1', 'Product')
								->setCellValue('F1', 'PRICE')
								->setCellValue('G1', 'STATUS')
								->setCellValue('H1', 'AUDITOR');
		$j="2";
		foreach($list as $k1=>$v1){
			$objPHPExcel->getActiveSheet()
				->setCellValue('A'.$j, date("m-d-Y H:i:s",$list[$k1]['TIMESTAMP']))
								->setCellValue('B'.$j, $list[$k1]['TID'])
								->setCellValue('C'.$j, $list[$k1]['BUID'])
								->setCellValue('D'.$j, $list[$k1]['SUID'])
								->setCellValue('E'.$j, $list[$k1]['PID'])
								->setCellValue('F'.$j, $list[$k1]['PRICE'])
								->setCellValue('G'.$j, $list[$k1]['STATUS'])
								->setCellValue('H'.$j, $list[$k1]['AUDITOR']);
			$j++;
		}
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->setTitle('Transaction');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save(APP_PATH.'/download/download.xlsx');
		header("Content-type:application/-excel");
		header("Content-Disposition:attachment;filename=download.xlsx");
		ob_end_clean();
		readfile(APP_PATH.'/download/download.xlsx');
	}
	public function log(){
		$Data = M('Alog');
		import('ORG.Util.Page');
		$sel_date=$_GET['sel_date'];
		$map['TIME']=array("lt",0);
		if(!empty($sel_date)){
			sscanf($sel_date,"%d-%d-%d - %d-%d-%d",$month_start,$day_start,$year_start,$month_end,$day_end,$year_end);
			$map['TIME']=array('between',array(mktime(0,0,0,$month_start,$day_start,$year_start),mktime(24,0,0,$month_end,$day_end,$year_end)));
		}		
		$count      = $Data->where($map)->count();
		$Page       = new Page($count);
		$nowPage = isset($_GET['p'])?$_GET['p']:1;
		$list = $Data->where($map)->page($nowPage.','.$Page->listRows)->select();
		$show       = $Page->show();
		$this->assign('page',$show);
		$this->assign('list',$list);		
		$this->display();
	}
}