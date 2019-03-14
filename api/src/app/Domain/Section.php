<?php
namespace App\Domain;

use App\Model\Section as ModelSection;
use App\Model\User as ModelUser;


class Section {
    //添加入库
    public function addData($data){
        $model = new ModelSection;
        $res = $model->insert($data);
        return $res;
    }

    //验证用户名
    public function checkName($data){
        $model = new ModelSection;
        $res = $model->checkName($data);
        return $res;
    }

    //通过id获取部门
    public function getSectionById($id,$page,$size){
        $model = new ModelUser;
        $rs['items'] = $model->getInfoBySectionId($id,$page,$size);
        $rs['total'] = $model->getInfoTotal($id);
        return $rs;
    }

    //获取活动列表
    public function getSection($page,$size){
        $model = new ModelSection;
        $rs['items'] = $model->getSectionItems($page,$size);
        $rs['total'] = $model->getSectionTotal();
        return $rs;
    }

    //部门删除
    public function del($id){
        $model = new ModelSection;
        $res = $model->delete($id);
        return $res;
    }

    //通过id更新数据
    public function updateData($id,$name){
        // if(!empty($name)){
        //     $data['name'] = $name;
        // }
        $model = new ModelSection;
        $res = $model->update($id,['name'=>$name]);
        return $res;
    }

    //获取部门
    public function getSectionOne($id){
        $model = new ModelSection;
        $res = $model->get($id);
        return $res;
    }
}