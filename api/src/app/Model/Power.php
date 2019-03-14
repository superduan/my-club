<?php 
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Power extends NotORM {

    //获取表名
    protected function getTableName($id)
    {
        return "power";
    }
    //获取主键
    protected function getTableKey($id)
    {
        return "power_id";
    }

    //查询该用户权限
    public function get_power($uid){
        $sql="select * from kaoqin_power where (power_id in (select p_id from kaoqin_role_power where r_id in (select r_id from kaoqin_user where id=:id)))";
        $params = array(':id' => $uid);
        $data=$this->getORM()->queryAll($sql, $params);
        return $data;
    }
    //添加权限
    public function add_power($data)
    {
        $orm = $this->getORM();
        $orm->insert($data);
        // 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
        return $orm->insert_id();
    }
    //修改权限
    public function upd_power($id,$data)
    {
        return $this->getORM()->where('power_id', "$id")->update($data);
    }
    //检测唯一性
    public function check_name($name)
    {
        return $this->getORM()->where('power_name', "$name")->fetch();
    }
    //权限列表
    public function power()
    {
        return $this->getORM()->select('*')->fetchAll();
    }
}