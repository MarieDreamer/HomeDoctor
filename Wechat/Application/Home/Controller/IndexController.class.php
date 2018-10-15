<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	
	public function __construct(){
		// validateUnSystemLoginRedirect();
		parent::__construct();
	}

	public function index(){
        validateUnLoginRedirect();
		$this->display();
	}

	public function view(){
        $conditions=array();
        $client=D('Client')->where($conditions)->select();
        $client_user=array();
        $time=array();
        $time_now=strtotime(date('Y-m-d',time()));
        for ($i=30; $i > 0; $i--) { 
        	$time[$i]=date('Y-m-d',$time_now);
        	$time_now=$time_now-86400;
        }
        foreach ($client as $key => $value) {
        	$client_user[$value['role']][]=$value;
        	$create_time=date('Y-m-d',$value['create_time']);
        	$client_time[$create_time][]=$value;
        }

        $conditions=array();
        $conditions['create_time']=array('between',array(strtotime($time[1]),strtotime($time[30])));
        $car=D('CarInfo')->where($conditions)->select();
        foreach ($car as $key => $value) {
        	$create_time=date('Y-m-d',$value['create_time']);
        	$car_time[$create_time][]=$value;
        }

        $user_id=array();
        foreach ($client_user['3'] as $key => $value) {
        	$user_id[]=$value['id'];
        }
        $conditions=array();
        $conditions['user_id']=array('in',$user_id);
        $car_user=D('CarInfo')->where($conditions)->select();

        $jxs_id=array();
        foreach ($client_user['0'] as $key => $value) {
        	$jxs_id[]=$value['id'];
        }
        $conditions=array();
        $conditions['user_id']=array('in',$jxs_id);
        $car_jxs=D('CarInfo')->where($conditions)->select();

        $time=array_reverse($time);
        $this->assign('car_jxs',$car_jxs);
        $this->assign('car_user',$car_user);
        $this->assign('client_user',$client_user);
        $this->assign('time',$time);
        $this->assign('client_time',$client_time);
        $this->assign('car_time',$car_time);
		$this->display();
	}

        public function sudu(){
            set_time_limit(0);
                $map=array();
                for ($i=0; $i < 9; $i++) { 
                        for ($j=0; $j < 9; $j++) { 
                                $map[$i][$j]='0';
                        }
                }
                $map[0][3]='9';
                $map[0][6]='5';
                $map[1][1]='3';
                $map[1][4]='5';
                $map[1][7]='1';
                $map[2][2]='2';
                $map[2][5]='7';
                $map[3][0]='1';
                $map[3][4]='7';
                $map[4][1]='7';
                $map[4][4]='1';
                $map[4][6]='2';
                $map[4][7]='6';
                $map[5][0]='8';
                $map[5][3]='4';
                $map[5][5]='6';
                $map[6][3]='8';
                $map[6][5]='5';
                $map[6][8]='2';
                $map[7][1]='8';
                $map[7][2]='5';
                $map[7][4]='2';
                $map[7][7]='3';
                $map[8][4]='4';
                $map[8][8]='9';
                    echo "<br>";
                    foreach ($map as $key => $value) {
                        foreach ($value as $m => $n) {
                            echo $n;
                            echo ' ';
                        }
                        echo "<br>";
                    }
                if ($map=$this->tianchong(0,$map)) {
                    echo "<br>";
                    foreach ($map as $key => $value) {
                        foreach ($value as $m => $n) {
                            echo $n;
                            echo ' ';
                        }
                        echo "<br>";
                    }
                }
        }
        public function tianchong($num,$map){
            echo "<br>";
            echo $num;
            $i=floor($num/9);  
            $j=$num%9;
            // echo $num+1;
            if ($num>=81){
                return $map; 
            }
            if ($map[$i][$j]==0){  
                for($n=1;$n<=9;$n++)  
                {
                    $map[$i][$j]=$n;
                    list($map,$flag1)=$this->Judge1($i,$j,$n,$map);
                    if($flag1==1){
                        list($map,$flag2)=$this->Judge2($i,$j,$n,$map);
                    // echo $flag2;
                        if ($flag2==1) {
                            // if($map=$this->tianchong($num+1,$map)){
                            //     return $map;  
                            // }
                            echo "<br>";
                            foreach ($map as $key => $value) {
                                foreach ($value as $m => $n) {
                                    echo $n;
                                    echo ' ';
                                }
                                echo "<br>";
                            }
                            $map=$this->tianchong($num,$map);
                        }else{
                            $map[$i][$j]='0';
                            // $map=$this->tianchong($num+1,$map);
                            // return $map;
                        } 
                    }else{
                        $map[$i][$j]='0';
                        // $map=$this->tianchong($num+1,$map);
                        // return $map;
                    }
                }
            }else{
                $map=$this->tianchong($num+1,$map);
            }
            // return $map;
        }
        public function Judge1($i,$j,$n,$map){
            $flag1=1;
            for($a=0;$a<9;$a++)  
            {  
                //判断 列  
                if(($map[$a][$j]==$n)&& ($a!=$i)){
                    $flag1=0;
                }  
                //判断 行  
                if(($map[$i][$a]==$n)&& ($a!=$j))  {
                    $flag1=0;
                }
            }  
            return array($map,$flag1);
        }
        public function Judge2($i,$j,$n,$map){
            $ii=floor($i/3);  
            $jj=floor($j/3);
            $flag2=-1;
            for($a=$ii*3;$a<$ii*3+3;$a++){
                for($b=$jj*3;$b<$jj*3+3;$b++) {
                    if($map[$a][$b]==$n) {
                        if($a==$i && $b==$j && $flag2==-1){  
                            $flag2=1; 
                        }else{
                            $flag2=0;
                        }
                    } 
                }
            }  
            return array($map,$flag2); 
        }

        public function photogrowth(){
            extract(generateRequestParamVars());
            // validateUnLoginRedirect();
            checkAccess('Photo','view');
            list($paging, $results) = D(self::$PHOTO_MODEL)->lists();
            $this->assign('paging', $paging);
            $this->assign('results', $results);
            $this->display();
        }
}