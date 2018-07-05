<?php
function success($data){
  echo json_encode(['errno'=>0,'data'=>$data]);
  exit;
}

function fail($msg,$errno=1000){
  echo json_encode(['errno'=>$errno,'errmsg'=>$msg]);
  exit;
}
function success_p($data,$get){
  $data=['errno'=>0,'data'=>$data];
  echo $get['callback'].'('.json_encode($data).')';
  exit;
}
function success_e($data,$get,$errno=1000){
  $data=['errno'=>$errno,'data'=>$data];
  echo $get['callback'].'('.json_encode($data).')';
  exit;
}
function get_hash(){
  $session_storage = 'session_'.pc_base::load_config('system','session_storage');
  pc_base::load_sys_class($session_storage);
  return $_SESSION['pc_hash'];
}
// function get_child($myid){
//   $a = $newarr = array();
//   if(is_array($this->arr)){
//     foreach($this->arr as $id => $a){
//       if($a['parentid'] == $myid) $newarr[$id] = $a;
//     }
//   }
//   return $newarr ? $newarr : false;
// }
// ///by umeng123.com 2018-06-28
// //获取子栏目
// function create_sub_array($myid,$str){
//   $sub_cats = get_child($myid);
//   $n = 0;
//   if(is_array($sub_cats)) foreach($sub_cats as $c) {	
//   $data[$n]['id'] = iconv(CHARSET,'utf-8',$c['catid']);
//   if(get_child($c['catid'])) {
//     $data[$n]['liclass'] = 'hasChildren';
//     $data[$n]['children'] = array(array('text'=>'&nbsp;','classes'=>'placeholder'));
//     $data[$n]['classes'] = 'folder';
//     $data[$n]['text'] = iconv(CHARSET,'utf-8',$c['catname']);
//     $data[$n]['type']=$c['type'];
//   } else {
//     $data[$n]['text'] = iconv(CHARSET,'utf-8',$c['catname']);
//     $data[$n]['type']=$c['type'];
//   }
//   $n++;
//   }
//   return $data;
// }

// ///by umeng123.com 2018-06-28
// function get_tree_array($id){
//   $cates=create_sub_array($id);
//   foreach($cates as $i=>$item){
//   if(!empty($item['children'])){
//     $cates[$i]['children']=get_tree_array((int)$item['id']);	
//   }
//   }	
//   return $cates;
// }
?>