<?php 
defined('IN_PHPCMS') or exit('No permission resources.'); 
pc_base::load_app_class('admin','admin',0);
pc_base::load_app_class('yz','yzpay',0);
pc_base::load_app_func('global', 'yzpay');
class yzpay extends admin {
	function __construct() {        
		parent::__construct();
        $this->db = pc_base::load_model('yzpay_model');
    }

    function init(){
        // $yzconfig=getcache('yzpay','config');
        // $yz=new yz($yzconfig);
        //$list=$yz->yz_trades_list();
        //$tree=pc_base::load_sys_class('tree');
//        $db=pc_base::load_model('category_model');
//        $category=$db->select();
//        $cates=$tree->init($category);
//        $cates=$tree->get_tree_array(0);
        //echo "<pre>";
        //print_r($cates);
        //var_dump($list['response']['qr_trades']);
        //echo "</pre>";
        //mysql_query('BEGIN');
	
	// //$res=$this->db->query($sql);
        // $res=mysql_query($sql);
        // if($res){
        //     mysql_query('COMMIT');
        //     var_dump($res);
        // }
        //elog('ini t','init');
        include $this->admin_tpl('yzpay_admin');
    }

    function selectConfig(){
        $config=getcache('config','yzpay');
        if(!empty($config)) success($config);
        else fail('请配置参数！');
    }
    function saveConfig(){
        $app_id=$_POST['app_id'];
        $app_secret=$_POST['app_secret'];
        $kdt_id=$_POST['kdt_id'];
        $qr_source=$_POST['qr_source'];
        $pay_desc=$_POST['pay_desc'];
        $data=array('app_id'=>$app_id,'app_secret'=>$app_secret,'kdt_id'=>$kdt_id,'qr_source'=>$qr_source,'pay_desc'=>$pay_desc);
	try{
        $list=$this->db->get_one(array('id'=>1));
        if(empty($list)){
            $this->db->insert($data);
        }else
            $this->db->update($data,array('id'=>1));
        setcache('config',$data,'yzpay');
        success('已保存！');
	}catch(Exception $e){
	    var_dump($e->getMessage());exit;
	    fail($e->getMessage());
	}
    }
    function freshConfig(){
        $data=$this->db->get_one(array('id'=>1));
        if(empty($data)) fail('您还没有配置参数！');
        setcache('config',$data,'yzpay');
        success('缓存已更新，请测试是否配置成功！');
    }

    function testConfig(){
        try{
            $config=getcache('config','yzpay');
            $yz=new yz($config);
            success($yz->createQr(0.01,$config['pay_desc'].'|'.$config['qr_source']));
        }
        catch(Exception $e){
            fail($e->getMessage(),100);
        }
    }

    function getTrades(){
        $page=(int)$_POST['page'];
        $pagesize=(int)$_POST['pagesize'];
        $status=$_POST['status'];
        $start_create=$_POST['start_create'];
        $end_create=$_POST['end_create'];
        if(empty($page)) $page=1;
        if(empty($pagesize))$pagesize=10;
        $where='';
        if(!empty($status)) $where.=' a.status="'.$status.'" and ';
        if(!empty($start_create)) $where.=' a.created_date>"'.$start_create.'" and ';
        if(!empty($end_create)) $where.=' a.created_date<"'.$end_create.'" and ';
	$config=getcache('config','yzpay');
        if($where!='') $where=' where '.$where.' a.qr_name like "%|'.$config['qr_source'].'"';
        $page_start=$pagesize*($page-1);
        try{
            //$sql='select * from phpcms_yzpay_trades '.$where.'  order by created_date desc limit '.$page_start.','.$pagesize.'';
            $sql='select a.*,c.username,c.amount from phpcms_yzpay_trades as a left join phpcms_yzpay_create_qr as b on a.qr_id=b.qr_id left join phpcms_member as c on b.uid=c.userid '.$where.'  order by a.created_date desc limit '.$page_start.','.$pagesize.'';
            
            $list=$this->db->fetch_array($this->db->query($sql));
            $count=$this->db->fetch_array($this->db->query("select count(*) as count from phpcms_yzpay_trades".$where));
            $res=array('list'=>$list,'count'=>(int)$count[0]['count'],'page'=>$page,'pagesize'=>$pagesize);
            success($res);
        }catch(Exception $e){
            fail($e->getMessage());
        }        
    }

    function qrcode_get(){
        $qr_id=$_POST['qr_id'];
        $yz=$this->Yz();
        success($yz->qrcode_get($qr_id));

    }

    function qrcodes_get(){
        $yz=$this->Yz();
        success($yz->qrcodes_get());
    }

    function checkPay(){
        $start_create=$_POST['start_create'];
        $end_create=$_POST['end_create'];
        $qr_id=$_POST['qr_id'];
        try{
            $yz=$this->Yz();
            $list=$yz->yz_trades_list();
            $yz->update_trades($list['response']['qr_trades']);
            
        }
        catch(Exception $e){            
            fail($e->getMessage());
        }        
    }

    function Yz(){
        return new yz(getcache('config','yzpay'));
    }
}
