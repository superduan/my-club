<?php 
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Leave extends NotORM {
     //获取表名
     protected function getTableName($id)
     {
         return "leave";
     }
     //请假申请
     public function app($data)
     {
         $orm = $this->getORM();
         $orm->insert($data);
        // 返回新增的ID（注意，这里不能使用连贯操作，因为要保持同一个ORM实例）
        return $orm->insert_id();
     }
     //修改权限
     public function upd($id,$data)
     {
        return $this->getORM()->where('id', "$id")->update($data);
     }
     //获取个人请假列表
     public function get_leave($id)
     {
        return $this->getORM()->where('user_id', "$id")->order('id DESC')->fetchAll();
     }
      //已审核
    public function audited()
    {
        return $this->getORM()->where('status', '1')->order('id DESC')->fetchAll();
    }
    //未审核
    public function unaudited()
    {
        return $this->getORM()->where('status', '0')->order('id DESC')->fetchAll();
    }
    //微信
    public function open($open,$time)
    {
        return $this->getORM()->where('start_time <= ?',$time)->where('end_time >= ?',$time)->where('open_id',"$open")->where('status',1)->fetchOne();
    }
    //个人请假列表
    public function info($user_id)
    {
        return $this->getORM()->where('user_id',$user_id)->fetchAll();
    }

    //获取个人请假列表
    public function ifleave($id,$dateline)
    {
       return $this->getORM()
        ->where('user_id', "$id")
        ->where('start_time <=?',$dateline)
        ->where('end_time >=?',$dateline)
        ->where('status','1')
        ->fetchAll(); 
    }
}