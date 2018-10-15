<?php
/*
 * 验证帐号是否存在
*/
function validateAccountExists($account){
	try{
		if(!$account){
			throw new \Exception('ACCOUNT NULL');
		}
		$exists=0;
		$belong_class='';
		if(D('EmployeeSystem')->getResultByConditions(array('account'=>$account))){
			$exists=1;
			$belong_class='system';
		}elseif(D('EmployeeShop')->getResultByConditions(array('account'=>$account))){
			$exists=1;
			$belong_class='shop';
		}
		$params=array(
			'exists'=>$exists,
			'class'=>$belong_class,
		);
	}catch(\Exception $e){
		$params=array(
			'exists'=>0,
			'class'=>'',
		);
	}
	return $params;
}

/*
 * 验证帐号是否存在其他账户表中
 */
function validateAccountExistsWithoutSelf($account,$employee_class){
	try{
		if(!$account){
			throw new \Exception('ACCOUNT NULL');
		}
		$exists=0;
		$belong_class='';
		if(D('EmployeeSystem')->getResultByConditions(array('account'=>$account))){
			$exists=1;
			$belong_class='system';
		}elseif(D('EmployeeShop')->getResultByConditions(array('account'=>$account))){
			$exists=1;
			$belong_class='shop';
		}
		$params=array(
			'exists'=>$exists,
			'class'=>$belong_class,
		);
	}catch(\Exception $e){
		$params=array(
			'exists'=>0,
			'class'=>'',
		);
	}
	return $params;
}

function validateUnSystemLoginRedirect(){
    if(!(session('employee_system_id') || session('employee_shop_id'))){
        exit('<script type="text/javascript">location.href="'.C('EMPLOYEE_SYSTEM_LOGIN').'";</script>');
    }
}

function department_show($results,$result,$pid,$suojin){
	$suojin++;
	foreach ($results as $key => $value) {
		if ($value['pid']==$pid) {
			$result[$key]=$value;
			$result[$key]['suojin']=$suojin;
			if ($result2=department_show($results,$result,$value['id'],$suojin)) {
				$result=$result2;
			}
		}
	}
	return $result;
}