<?php
defined('IN_PHPCMS') or exit('No permission resources.');
require_once __DIR__ . '/YZHttpClient.php';
require_once __DIR__.'/YZTokenClient.php';

class yz{
    //private $client_id,$client_secret,$kdt_id;
    public function __construct($data){
        $this->config=$data;
    }

    public function createQr($price,$qr_name='充值',$qr_type='QR_TYPE_NOLIMIT'){
        if($price<=0) throw new Exception('金额不正确！');
        $params=array('qr_name'=>$qr_name,'qr_price'=>(float)$price*100,'qr_type'=>$qr_type,'qr_source'=>$this->config['qr_source']);        
        $client=$this->Yz();
        $data=$client->post('youzan.pay.qrcode.create','3.0.0',$params);        
        if(!empty($data['response'])){
            $db=pc_base::load_model('yzpay_create_qr_model');
            $db->insert($data['response']);
        }else {
            throw new Exception($data['error_response']['msg']);
        }
        return $data['response'];
    }


    public function qrcode_get($qr_id){
        $params=array('qr_id'=>$qr_id);
        $this->Yz()->post('youzan.pay.qrcode.get','3.0.0',$params);
        return $data;        
    }

    public function qrcodes_get($params=array()){
        $data=$this->Yz()->post('youzan.pay.qrcodes.get','3.0.0',$params);
        return $data;
    }

    public function yz_trades_list($params){
        $client=new YZTokenClient($this->getToken());
        $data=$client->post('youzan.trades.qr.get','3.0.0',$params);        
        return $data;

    }

    public function update_trades($list){
        $db=pc_base::load_model('yzpay_trades_model');
        try{
            foreach($list as $i=>$item){
                $data=$db->get_one(array('tid'=>$item->tid));                
                if(empty($data)){
                    $db->insert($item);
                }else{
                    $db->update($item,array('tid'=>$item->tid));
                }
            }
        }catch(Exception $e){
            throw $e;
        }
    }

    

    public function getToken(){
        try{
            $token=getcache('token','yzpay');
            if(empty($token)){
                $token=$this->createToken();
            }else {
                $expires_in=(float)$token['expires_in'];            
                if($expires_in<time()*1000) $token=$this->createToken();
            }
            
            setcache('token',$token,'yzpay');
            
            return $token['access_token'];
        }catch(Exception $e){            
            throw $e;
        }
    }

    public function createToken(){
        $url='https://open.youzan.com/oauth/token';
        $params=array('client_id'=>$this->config['app_id'],'client_secret'=>$this->config['app_secret'],'grant_type'=>'silent','kdt_id'=>$this->config['kdt_id']);
        

        $token=json_decode(YZHttpClient::post($url,$params),true);
        
        
        if(!empty($token['error'])) throw new Exception($token['error_description']);
        
        $token=array('access_token'=>$token['access_token'],'expires_in'=>time()*1000+$token['expires_in']-2*1000*60);        
        
        
        return $token;
    }

    function Yz(){        
        return new YZTokenClient($this->getToken());
    }
}
?>
