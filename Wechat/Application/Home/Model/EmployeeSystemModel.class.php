<?php
namespace Home\Model;
use Home\Abstracts\CommonMAbstract;
// use Home\Interfaces\EmployeeSystemMInterface;

class EmployeeSystemModel extends CommonMAbstract {
	
	protected $connection = 'DB_CONFIG1';
	
	public function login(){
		// var_dump($password);
		// var_dump($account);
		extract(generatePostParamVars());
		//validate data
		if(!preg_match('/^\w{3,20}$/',$account)){
			throw new \Exception("OPERATION_FAILED");
		}
		if(!preg_match('/^.{6,20}$/',$password)){
			throw new \Exception("OPERATION_FAILED");
		}
		//db operate
		$conditions = array();
		$conditions['account']=$account;
		$conditions['password']=passwordEncode($password);
		$conditions['status']=1;
		if(!$result=$this->where($conditions)->find()){
			// echo "44";
			// echo $this->_sql();
			throw new \Exception('OPERATION_FAILED');
		}
		session('employee_system_id',$result['id']);
		session('employee_system_username',$result['username']);
		session('employee_system_account_creator_id',$result['creator_id']);

		//角色权限
		// $conditions = array();
		// $conditions['role_id']=$result['role_id'];
		// $access=D('access')->where($conditions)->select();
		// for($i=1;$i<=count($access);$i++){
		// 	session($access[$i-1][session_name],$access[$i-1]);
		// }

		//department permission
		/*
		$department=D('Department')->getResultById($result['department_id']);
		session('department_permission_action',$department['permission_actions']);
		session('department_permission_keywords',$department['permission_keywords']);
		*/
	}

	//增加系统用户
	public function adds(){
		extract(generatePostParamVars());
		//正整数正则
        $shuziyz='/^[0-9]*$/';
        //验证字段值是否为空是否合法
        if(preg_match($shuziyz, $department_id) && $account && $name && $password && $password_repeat && preg_match($shuziyz, $role_id) && preg_match($shuziyz, $sort)){
            $yzsz=1;
        }
        if($yzsz==1){
		//检查帐号是否已经存在
		$conditions=array();
		$conditions['account']=$account;
		if($this->where($conditions)->find()){
			throw new \Exception(L('帐号已经存在！'));
		}
		$conditions=array();
		$conditions['name']=$name;
		if($this->field('id')->where($conditions)->find()){
			throw new \Exception('员工姓名已经存在！');
		}
		if(!$password){
		    throw new \Exception('密码不能为空');
		}
		if($password_repeat!=$password){
		    throw new \Exception('两次密码输入不一致');
		}
		$data=array();
		$data['account']=$account;
		$data['password']=passwordEncode($password);
		$data['name']=$name;
		$data['sort']=$sort;
		$data['create_time']=time();
		$data['create_id']=session('employee_system_id');
		$data['department_id']=0;
		$data['role_id']=0;
		$data['status_super']=0;
		$data['department_id']=$department_id;
		$data['role_id']=$role_id;
		if(!$this->add($data)){
			throw new \Exception(L('OPERATION_FAILED'));
		}
		}else{
                throw new \Exception(L('， 输入了非法数据！或者没有输入。'));
            }

	}

	public function edit(){
		extract(generatePostParamVars());
		//正整数正则
        $shuziyz='/^[0-9]*$/';
        if(preg_match($shuziyz, $id) && $account && preg_match($shuziyz, $department_id) && $name && $password && preg_match($shuziyz, $role_id) && preg_match($shuziyz, $sort)){
            $yzsz=1;
        }
        //验证字段是否存在值，是否合法。
        if($yzsz==1){
		//验证该条是否存在
		$conditions=array();
		$conditions['id']=$id;
		if(!$result=$this->where($conditions)->find()){
			throw new \Exception(L('NO_DATA'));
		}
		//检查员工帐号是否已经存在
		$conditions=array();
		$conditions['id']=array('neq',$id);
		$conditions['account']=$account;
		$conditions['status_delete']=1;
		if($this->where($conditions)->find()){
			throw new \Exception(L('员工帐号已经存在！'));
		}
		//检查员工姓名是否已经存在
		$conditions=array();
		$conditions['id']=array('neq',$id);
		$conditions['name']=$name;
		$conditions['status_delete']=1;
		if($this->where($conditions)->find()){
			throw new \Exception(L('员工姓名已经存在！'));
		}

		$conditions=array();
		$conditions['id']=$id;
		$data=array();
		$data['account']=$account;
		$data['name']=$name;
		$data['password']=$password;
		$data['sort']=$sort;
		$data['department_id']=$department_id;
		$data['role_id']=$role_id; 
		if($this->where($conditions)->save($data)===false){
		   throw new \Exception(L('OPERATION_FAILED'));

		}
		}else{
                throw new \Exception(L('， 输入了非法数据！或者没有输入。'));
            }
	}

	public function password_reset(){
		extract(generatePostParamVars());
		if(!preg_match('/^.{6,20}$/',$password_n)){
			throw new \Exception("OPERATION_FAILED");
		}
		if($password_n!=$password_repeat){
			throw new \Exception('两次密码输入不一致');
		}
		$conditions=array();
		$conditions['id']=$id;
		$data['password']=passwordEncode($password_n);
		if($this->where($conditions)->save($data)===false){
		   throw new \Exception(L('OPERATION_FAILED'));
		}
	}

	public function password_edit(){
		extract(generatePostParamVars());
		//validate data
		if(!preg_match('/^.{6,20}$/',$password_c)){
			throw new \Exception("OPERATION_FAILED");
		}
		if(!preg_match('/^.{6,20}$/',$password_n)){
			throw new \Exception("OPERATION_FAILED");
		}
		if($password_n!=$password_repeat){
			throw new \Exception('两次密码输入不一致');
		}
		$conditions['id']=session('employee_system_id');
		$conditions['password']=passwordEncode($password_c);
		if(!$member=$this->where($conditions)->find()){
			throw new \Exception('当前密码错误');
		}
		$conditions=array();
		$conditions['id']=session('employee_system_id');
		$data['password']=  passwordEncode($password_n);
		if($this->where($conditions)->save($data)===false){
			throw new \Exception('OPERATION_FAILED');
		}
	}

	public function dele(){
		extract(generateRequestParamVars());
		$conditions=array();
		$conditions['id']=$id;
		$data['status_delete']=0;
		if(!$this->where($conditions)->save($data)){
			throw new \Exception('OPERATION_FAILED');
		}
	}

	public function lists(){
		extract(generateRequestParamVars());
		$conditions=array();
		// $conditions['creator_id']=array('neq',0);
		if($department_id){
			$conditions['department_id']=$department_id;
		}
		
		// $conditions['creator_id']=array('neq',0);
		if($role_id){
			$conditions['role_id']=$role_id;
		}
		// $conditions['creator_id']=session('employee_system_id');
		$conditions['status_delete']=1;
		$count=$this->where($conditions)->count();
		if(!$numPerPage=I('param.numPerPage')){
			$numPerPage=C('NUM_PER_PAGE');
		}

		$page=new \Think\Page($count,$numPerPage);
		$paging=$page->show();
		$results=$this->where($conditions)->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
		return array($paging,$results);
	}

	// public function lists(){
	// 	extract(generateRequestParamVars());
	// 	$count=$this->count();
	// 	if(!$numPerPage=I('param.numPerPage')){
	// 		$numPerPage=C('NUM_PER_PAGE');
	// 	}
	// 	$numPerPage=20;
	// 	$page=new \Think\Page($count,$numPerPage);
	// 	$paging=$page->show();
	// 	if(!$conditions){
	// 		$results=$this->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
	// 	}else{
	// 		$results=$this->where($conditions)->order('create_time desc')->limit($page->firstRow.','.$page->listRows)->select();
	// 	}
	// 	return array($paging,$results);
	// }

	//批量删除
	public function batch_dele(){
		extract(generateRequestParamVars());
		$id=array();
		$id=explode('_',$ids);
		foreach ($id as $key => $value) {
			if ($value) {
				$conditions=array();
				$conditions['id']=$value;
				$data['status_delete']=0;
				if(!$this->where($conditions)->save($data)){
					throw new \Exception('OPERATION_FAILED');
				}
			}
		}
    }


}