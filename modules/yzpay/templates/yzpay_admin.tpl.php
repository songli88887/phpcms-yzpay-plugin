<?php
defined('IN_ADMIN') or exit('No permission resources.'); 
include $this->admin_tpl('header', 'admin');
$data=getcache('config','yzpay');
?>

<link rel="stylesheet" type="text/css" href="<?PHP echo APP_PATH?>phpcms/modules/yzpay/css/common.css">
<link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
<script>
    window.hash='<?php echo get_hash()?>';
</script>
<div>
  <div id="app"></div>
  <script src="<?php echo APP_PATH?>phpcms/modules/yzpay/js/build.js"></script>
  
</div>
