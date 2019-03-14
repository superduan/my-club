<?php
namespace App\Domain;

use App\Model\Role as ModelRole;

class Role {
    //添加权限
    public function add_Role($data)
    {
        $model= new ModelRole;
        $res = $model->insert($data);
        return $res;
    }
    //删除权限
    public function del_role($power_id)
    {
        $model= new ModelRole;
        $res=$model->delete($power_id);
        return $res;
    }
    //获取数据
    public function get_role($upd_id)
    {
        $model=new ModelRole;
        $res=$model->get_role($upd_id);
        return $res;
    }
    //修改权限
    public function upd_role($id,$data)
    {
        foreach($data as $key =>$val){
            if(empty($val)){
                unset($data[$key]);
            }
        }
        $model=new ModelRole;
        $res=$model->upd_role($id,$data);
        return $res;
    }
    //检测唯一性
    public function check_name($name)
    {
        $model=new ModelRole;
        $res=$model->check_name($name);
        return $res;
    }
    //角色权限
    public function role_power($role_id)
    {
        $model=new ModelRole;
        $res=$model->role_power($role_id);
        return $res;
    }
    //角色列表
    public function role()
    {
        $model=new ModelRole;
        $res=$model->role();
        return $res;
    }
}