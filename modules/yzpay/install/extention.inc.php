<?php
defined('IN_PHPCMS') or exit('Access Denied');
defined('INSTALL') or exit('Access Denied');

$parentid = $menu_db->insert(array('name'=>'yzpay', 'parentid'=>29, 'm'=>'yzpay', 'c'=>'yzpay', 'a'=>'init', 'data'=>'', 'listorder'=>0, 'display'=>'1'), true);
//$menu_db->insert(array('name'=>'wap_add', 'parentid'=>$parentid, 'm'=>'wap', 'c'=>'wap_admin', 'a'=>'add', 'data'=>'', 'listorder'=>0, 'display'=>'0'));
$language=array('yzpay'=>'个人支付');
?>
