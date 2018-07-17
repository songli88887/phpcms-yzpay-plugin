<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_app_class('yz','yzpay',0);
pc_base::load_app_func('global', 'yzpay');
class index {
  function __construct() {
    
  }
  function init(){
    include template('yzpay', 'index');
  }
  function createQr(){
    $uid=param::get_cookie('_userid');
    if(empty($uid)) fail('请先登录！');
    $config=getcache('config','yzpay');
    $yz=new yz($config);
    $price=(float)safe_replace($_POST['price']);
    if($price<=0)fail('金额不正确！');
    $data=$yz->createQr($price,$config['pay_desc'].'|'.$config['qr_source'],$uid);
    success($data);
  }

  function notify(){
    echo '{"code":0,"msg":"success"}';
    
    $data=file_get_contents('php://input');
    $data=json_decode($data,true);
    $config=getcache('config','yzpay');
    if($data['client_id']==$config['app_id']){
      $start_created=time();
      $end_created=date('Y-m-d H:i:s',$start_created+2*60*60);
      $start_created=date('Y-m-d H:i:s',$start_created-60*5);
      $yz=new yz($config);
      $param=array('start_created'=>$start_created,'end_created'=>$end_created);      
      try{
        $list=$yz->yz_trades_list($param);
        $yz->update_trades($list['response']['qr_trades']);
        $this->addMount($data['id']);
      }catch(Exception $e){
	    }
    }
  }

  function payStatus(){
    $qr_id=safe_replace($_POST['qr_id']);
    $db=pc_base::load_model('yzpay_trades_model');
    $data=$db->get_one(array('qr_id'=>$qr_id,'status'=>'TRADE_RECEIVED'));
    if(empty($data)){
      success(array('error'=>true));
      fail('未支付',-1000);
    }else success('支付成功，订单号：'.$data['tid']);
  }

  function payStatusM(){
    $uid=param::get_cookie('_userid');
    $qr_id=safe_replace($_POST['qr_id']);
    $sql="select c.tid from phpcms_member as a,phpcms_yzpay_create_qr as b,phpcms_yzpay_trades as c where a.userid=b.uid and b.qr_id=c.qr_id and b.qr_id='".$qr_id."' and a.userid=".$uid." and c.status='TRADE_RECEIVED'";    
    $db=pc_base::load_model('yzpay_model');
    $data=$db->fetch_array($db->query($sql));
    if(sizeof($data)>0) success('支付成功，订单号：'.$data[0]['tid']);
    else fail('未支付');
  }

  private function addMount($tid){    
    $sql="select a.tid,a.real_price,b.uid,b.qr_id from phpcms_yzpay_trades as a,phpcms_yzpay_create_qr as b,phpcms_member as c where a.qr_id=b.qr_id and a.tid='".$tid."' and a.status='TRADE_RECEIVED' and a.action=0 and b.uid=c.userid";
    
    $db=pc_base::load_model('yzpay_model');
    $data=$db->fetch_array($db->query($sql));
    
    if(sizeof($data)>0){
      $sql1="update phpcms_member set amount=amount+".$data[0]['real_price']." where userid=".$data[0]['uid'];
      $sql2="update phpcms_yzpay_trades set action=1 where tid='".$data[0]['tid']."'";
      $db->query($sql1);
      $db->query($sql2);
    }
  }

  
}
