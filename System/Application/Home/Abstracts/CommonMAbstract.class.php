<?php
namespace Home\Abstracts;
use Think\Model;
abstract Class CommonMAbstract extends Model{
	
	public function getResultByConditions(array $conditions){
		if(!$conditions){
			return $this->find();
		}else{
			return $this->where($conditions)->find();
		}
	}
	
	public function getResultsByConditions(array $conditions,$orders){
		if(!$conditions){
			if(!$orders){
				return $this->select();
			}
			return $this->order($orders)->select();
		}else{
			if(!$orders){
				return $this->where($conditions)->order($orders)->select();
			}
			return $this->where($conditions)->select();
		}
	}
	
}