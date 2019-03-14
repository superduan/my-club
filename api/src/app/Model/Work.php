<?php 
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Work extends NotORM {
     //获取表名
     protected function getTableName($id)
     {
         return "work";
     }

     //批量添加
     public function add($data){
        return $this->getORM()->insert_multi($data);
     }

    //考勤查询
    public function getInfoItems($data){
        $res = $this->getORM()
            ->select("id,user_id,job_number,name,r_name,section_name,dateline,type,status,sduration,eduration,from_unixtime(sign_stime, '%Y-%m-%d %H:%i:%s') as sign_stime,from_unixtime(sign_etime, '%Y-%m-%d %H:%i:%s') as sign_etime")
            ->where($data['where'])
            ->limit(
                ($data['page']-1)*$data['size'],$data['size']
            )
            ->fetchAll();
        return $res;
    }

    public function getInfoTotol($data){
        $res = $this->getORM()
            ->where($data['where'])
            ->count();
        return $res;
    }
}