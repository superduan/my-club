<?php 
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Role extends NotORM {

    //获取表名
    protected function getTableName($id)
    {
        return "role";
    }

    //获取主键
    protected function getTableKey($id)
    {
        return "role_id";
    }

    //获取角色数据
    public function get_role($id)
    {
        return $this->getORM()->where('role_id', $id)->fetchAll();
    }

    //修改角色
    public function upd_role($id,$data)
    {
        return $this->getORM()->where('role_id', "$id")->update($data);
    }
     //检测唯一性
     public function check_name($name)
     {
         return $this->getORM()->where('role_name', "$name")->fetch();
     }
     //获取用户权限
     public function role_power($role_id)
     {
         $sql="select * from kaoqin_power where (power_id in (select p_id from kaoqin_role_power where (r_id = :role_id)))";
         $params = array(':role_id' => $role_id);
         $data=$this->getORM()->queryAll($sql, $params);
         return $data;
     }
     //角色列表
     public function role()
     {
         $data=$this->getORM()->select('*')->fetchAll();
         return $data;
     }
}