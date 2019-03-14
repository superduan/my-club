<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Login extends NotORM {
    //获取表名
    protected function getTableName($id)
    {
        return "user";
    }

    //查询名和密码
    public function checkNamePwd($data){
        $sql = "select id,job_number,r_id,section_id,sex,age,img,name,role_name,open_id,pwd"
        . " from kaoqin_user inner join kaoqin_role on kaoqin_user.r_id = kaoqin_role.role_id"
        . " where job_number =:job_number and pwd =:pwd ";
        $where = [
            ':job_number' =>$data['job_number'],
            ':pwd' =>$data['pwd'],
        ];
        $res = $this->getORM()->queryAll($sql,$where);
        return $res;
    }

    //获取信息
    public function getInfo($data){
        $sql = "select id,job_number,r_id,section_id,sex,age,img,name,role_name,open_id,pwd"
        . " from kaoqin_user inner join kaoqin_role on kaoqin_user.r_id = kaoqin_role.role_id"
        . " where job_number =:job_number";
        $where = [
            ':job_number' =>$data['job_number'],
        ];
        $res = $this->getORM()->queryAll($sql,$where);
        return $res;
    }

    public function getInfoByOpen($where){
        $res = $this->getORM()
            ->where($where)
            ->fetch();
        return $res;
    }
}