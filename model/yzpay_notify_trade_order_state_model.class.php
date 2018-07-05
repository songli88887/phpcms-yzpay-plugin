<?php
defined('IN_PHPCMS') or exit('No permission resources.');
pc_base::load_sys_class('model', '', 0);
class yzpay_notify_trade_order_state_model extends model{
  public function __construct(){
    $this->db_config = pc_base::load_config('database');
    $this->db_setting = 'default';
    
    $this->table_name = 'yzpay_notify_trade_order_state';
    parent::__construct();
  }

  public function thenUpdate($data,$where){
    $data=$this->get_one($where);
    if(empty($data)){
      $this->insert($data);
    }else {
      $this->update($data,$where);
    }
  }



}
?>