<?php 
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_app_func('global');
pc_base::load_app_class('yz','yzpay',0);
pc_base::load_app_func('global', 'yzpay');
class index {
  function __construct() {
    
  }
  function init(){
    $uid=param::get_cookie('_userid');
    include template('yzpay', 'index');
  }
  function createQr(){
    $yz=new yz(getcache('config','yzpay'));
    $price=(float)$_POST['price'];
    if($price<=0)fail('金额不正确！');
    $data=$yz->createQr((float)$_POST['price']);
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
      elog($param);
      try{
        $list=$yz->yz_trades_list($param);
        $yz->update_trades($list['response']['qr_trades']);
        $db=pc_base::load_model('yzpay_notify_trade_order_state_model');
        $db->thenUpdate($data,array('id'=>$data['id']));
      }catch(Exception $e){
	      elog($e->getMessage(),'error');
	    }
    }
  }

  function payStatus(){
    $qr_id=safe_replace($_POST['qr_id']);
    //success($qr_id);
    $db=pc_base::load_model('yzpay_trades_model');
    $data=$db->get_one(array('qr_id'=>$qr_id,'status'=>'TRADE_RECEIVED'));
    //success($data);
    if(empty($data)){
      success(array('error'=>true));
      fail('未支付',-1000);
    }else success('支付成功，订单号：'.$data['tid']);
    //$uid=param::get_cookie('_userid');
    //$qr_id=safe_replace(trim($_POST['qr_id']));    
    //$sql="select tid from phpcms_member as a,phpcms_yzpay_create_qr as b where a.uid=b.uid and b.status='TRADE_RECEIVED' and b.action=1 and and b.qr_id='".$qr_id."'";
    //$sql='select tid from phpcms_yzpay_trades where qr_id="'.$qr_id.'" and status="TRADE_RECEIVED"';

    //$db=pc_base::load_model('yzpay_model');
    //$data=$db->fetch_array($db->query($sql));
    //if(sizeof($data)>0){
      //success(array('tid'=>$data[0]['tid'],'msg'=>''));    
    //}else fail('未支付！');
  }

  private function addMount($tid){
    
    $sql="select a.uid,b.read_price from phpcms_yzpay_trades as a,phpcms_yzpay_create_qr as b where a.qr_id=b.qr_id and a.tid='".$tid."' and a.status='TRADE_RECEIVED' and a.action=0";
    //$sql=safe_replace($sql);
    $db=pc_base::load_model('yzpay_model');
    $data=$db->fetch_array($db->query($sql));
    if(sizeof($data)>0){
      $sql1="update phpcms_member set amount=amount+".$data[0]['real_price']." where uid=".$data[0]['uid'];      
//      $sql1=safe_replace($sql1);
      $db2=pc_base::load_model('yzpay_trades');
      $db2->update(array('action'=>1),array('tid'=>$tid));
      $db->query($sql1);
    }
  }

  
}
