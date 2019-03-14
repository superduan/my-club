<?php
namespace App\Domain;

use App\Model\Power as ModelPower;

class Power {
    //查询权限
    public function get_power($uid){
        //模型
        $model = new ModelPower;
        $res = $model->get_power($uid);
        $res=$this->recursion($res);
        return $res;
    }
    //无限极分类
    public function recursion($data,$pid=0)
    {
        static $arr=array();
        foreach($data as $key => $val){
            if($val['power_pid']==$pid){
                $arr[]=$val;
                $this->recursion($data,$val['power_id']);
            }
        }
        return $arr;
    }
    //添加权限
    public function add_power($data)
    {
        $model= new ModelPower;
        $res=$model->add_power($data);
        return $res;
    }
    //删除权限
    public function del_power($power_id)
    {
        $model= new ModelPower;
        $res=$model->delete($power_id);
        return $res;
    }
    //获取修改数据
    public function get_upd($upd_id)
    {
        $model=new ModelPower;
        $res=$model->get($upd_id);
        return $res;
    }
    //修改权限
    public function upd_power($id,$data)
    {
        foreach($data as $key => $val){
            if(empty($val)){
                unset($data[$key]);
            }
        }
        $model=new ModelPower;
        $res=$model->upd_power($id,$data);
        return $res;
    }
    //检测唯一性
    public function check_name($name)
    {
        $model=new ModelPower;
        $res=$model->check_name($name);
        return $res;
    }
    //权限列表
    public function power()
    {
        $model=new ModelPower;
        $res=$model->power();
        return $res;
    }
}