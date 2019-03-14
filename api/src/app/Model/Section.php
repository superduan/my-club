<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Section extends NotORM {
    //表名
    protected function getTableName($id){
        return "section";
    }

    //通过名称验证唯一
    public function checkName($where){
        $res = $this->getORM()
            ->where($where)
            ->fetchOne();
        return $res;
    }

    //查询列表
    public function getSectionItems($page,$size){
        $res = $this->getORM()
            ->limit(($page-1)*$size,$size)
            ->fetchAll();
        return $res;
    }

    //查询列表总数
    public function getSectionTotal(){
        $res = $this->getORM()
            ->count();
        return $res;
    }
}