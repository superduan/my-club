<?php
namespace App\Domain;

use App\Model\Menu as ModelMenu;
use PhalApi\Exception\BadRequestException;


class Menu {
    
    //添加入库
    public function addData($data){
        foreach($data as $key=>$val){
            if(!isset($val)){
                unset($data[$key]);
            }
        }
        $model = new ModelMenu;
        $res = $model->insert($data);
        return $res;
    }

    //获取菜单列表
    public function getInfo($type){
        $model = new ModelMenu;
        $data = $model->getInfoItems();
        if($type == 'list'){
            $data = $this->recursionList($data);
        }else if($type == 'one'){
            $data = $model->menuOne();
        }else{
            $data = $this->recursion($data);
        }
        $rs['button'] = $data;
        $rs['total'] = $model->getInfoTotal();
        return $rs;
    }

    //菜单删除
    public function del($id){
        $model = new ModelMenu;
        $res = $model->delete($id);
        return $res;
    }

    //通过id更新数据
    public function updateData($id,$data){
        $model = new ModelMenu;
        $res = $model->update($id,$data);
        return $res;
    }

    //单条
    public function getOne($id){
        $model = new ModelMenu;
        $res = $model->get($id);
        return $res;
    }

    //递归列表排序
    public function recursionList($arr,$pid=0,$level=1){
        static $list;
        foreach($arr as $val){
            if($val['pid'] == $pid){
                $val['level'] = $level;
                $list[] = $val;
                $this->recursionList($arr,$val['id'],$level+1);
            }
        }
        return $list;
    }

    //递归排序
    public function recursion($arr,$pid=0,$level=1){
        $data = [];
        foreach($arr as &$val){
            if($val['pid'] == $pid){
                $val['level'] = $level;
                if($val['level'] == 1){
                    $val['sub_button'] = $this->recursion($arr,$val['id'],$level+1);
                }
                if($val['type'] == 'click'){
                    unset($val['url']);
                }else if($val['type'] == 'view'){
                    unset($val['key']);
                }
                // if(isset($val['sub_button'])){
                //     unset($val['event']);
                //     unset($val['url']);
                //     unset($val['key']);
                // }
                $data[] = $val;
            }
        }
        return $data;
    }
}