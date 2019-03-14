<?php 
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Allot extends NotORM {
    //获取表名
    protected function getTableName($id)
    {
        return "role_power";
    }
    //分配权限
    public function allot($r_id,$p_id)
    {
        $orm = $this->getORM();
        foreach ($p_id as $key => $val) {
            $orm->insert(array('r_id'=>$r_id,'p_id'=>$val));
            // 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
        }
        return array('seccess'=>'ok');
    }
    //删除角色权限
    public function del($r_id)
    {
       return  $this->getORM()->where('r_id',$r_id)->delete();
    }
}