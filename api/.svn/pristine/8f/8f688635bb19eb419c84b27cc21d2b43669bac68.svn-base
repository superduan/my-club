<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;

class Menu extends NotORM {
    //设置表名
    protected function getTableName($id)
    {
        return "menu";
    }

    //查询菜单列表
    public function getInfoItems(){
        $res = $this->getORM()
            ->fetchAll();
        return $res;
    }

    //查询菜单列表总数
    public function getInfoTotal(){
        $res = $this->getORM()
            ->count();
        return $res;
    }

    //查询一级菜单
    public function menuOne(){
        $res = $this->getORM()
            ->where(['pid'=>0])
            ->fetchAll();
        return $res;
    }
}