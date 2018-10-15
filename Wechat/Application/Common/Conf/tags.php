<?php
return array(
    'app_begin' => array('Behavior\CheckLangBehavior'),
    'view_filter' => array('Behavior\TokenBuildBehavior'),//'view_filter' => array('Behavior\TokenBuild'),    // 如果是3.2.1版本 需要改成    
);