<?php
return array(
	//'配置项'=>'配置值'
	'VAR_PAGE'=>'pageNum',
	'NUM_PER_PAGE'=>'20',
	'SITE_NAME'=>'诸暨二手车',

	'CLIENT_ROLE'=>array(
    	'0'=>'经销商',
    	'1'=>'汽车美容',
    	'2'=>'维修商',
    	'3'=>'个人',
	),
	//任务类型属性，筛选时调用这些参数
	'TASKCLASS_CLASS'=>array(
		'0'=> '新用户任务' ,
		'1'=> '日常任务',
		'2'=> '隐藏任务',
	),
	//奖励类型属性，产品筛选时调用这些参数		
	'TASKCLASS_REWARDS_CLASS'=>array(
		'0'=> '金币' ,
		'1'=> '优惠券',
	),

);