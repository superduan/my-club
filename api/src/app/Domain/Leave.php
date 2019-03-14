<?php
namespace App\Domain;

use App\Model\Leave as ModelLeave;

class Leave {
    //请假申请
    public function app($data)
    {
        $model = new ModelLeave;
        $res = $model->app($data);
        return $res;
    }
    //修改请假
    public function upd($id,$data)
    {
        foreach($data as $key => $val){
            if(empty($val)){
                unset($data[$key]);
            }
        }
        $model=new ModelLeave;
        $info=$model->get($id);
        if ($info['status']==1) {
            return array('info'=>"审核通过的请假不能修改");
        }
        $res=$model->upd($id,$data);
        return $res;
    }
    //删除请假记录
    public function del($id)
    {
        $model= new ModelLeave;
        $res=$model->delete($id);
        return $res;
    }
    //获取个人请假列表
    public function get_leave($id)
    {
        $model= new ModelLeave;
        $res=$model->get_leave($id);
        return $res;
    }
    //已审核
    public function audited()
    {
        $dn=new ModelLeave;
        $reg=$dn->audited();
        return $reg;
    }
    //未审核
    public function unaudited()
    {
        $dn=new ModelLeave;
        $reg=$dn->unaudited();
        return $reg;
    }
    //微信
    public function open($open,$time)
    {
        $dn=new ModelLeave;
        $reg=$dn->open($open,$time);
        return $reg;
    }
    //个人请假列表
    public function info($user_id)
    {
        $dn=new ModelLeave;
        $reg=$dn->open($user_id);
        return $reg;
    }
}