<?php
class CommentAction extends Action{
	public function viewComments(){
        $this->assign('TITLE', 'View comments');
        $tid = $_REQUEST['TID'];
	    $Transaction = D('Transaction');
        $tinfo = $Transaction->getTransactionInfo($tid);
        if (($tinfo) && ($tinfo['BUID']==session('uid'))){
            $this->tinfo = $tinfo;
            if ($tinfo['ROOMORSEAT']==0){
                $this->roomorseat = 0;
                $Room = D('Room');
                $pinfo = $Room->getProductInfo($tinfo['PID']);
                $this->pinfo = $pinfo[0];
            }
            else{
                $this->roomorseat = 1;
                $Seat = D('Seat');
                $pinfo = $Seat->getProductInfo($tinfo['PID']);
                $this->pinfo = $pinfo[0];
            }
            $this->display();
        }
        else
            $this->error("This is not your transaction!", "__APP__/User");
    }
    public function editComments(){
        $score = $_POST['score'];
        $comment = $_POST['comment'];
        $tid = $_POST['tid'];
        $Transaction = D('Transaction');
        if($Transaction->setComment($tid, $score, $comment)){
            if ($_POST['roomorseat']==0){
                $Hotel = D('Hotel');
                $hid = $_POST['hid'];
                $Hotel->addScore($hid, $score);
            }
            else{
                $Flight = D('Flight');
                $fid = $_POST['fid'];
                $Flight->addScore($fid, $score);
            }
            $this->success("Comment submitted!", "__APP__/User");
        }
        else
            $this->error("Comment submit failed!", "__APP__/User");
    }
    
}
