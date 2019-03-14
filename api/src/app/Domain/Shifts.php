<?php
namespace App\Domain;

use App\Model\Shifts as ModelShifts;

class Shifts {
    //班次添加
    public function shifts_add($data)
    {
        $model = new ModelShifts;
        $res = $model->shifts_add($data);
        return $res;
    }
    //班次修改
    public function upd($id,$data)
    {
        foreach($data as $key => $val){
            if(empty($val)){
                unset($data[$key]);
            }
        }
        $model=new ModelShifts;
        $res=$model->upd($id,$data);
        return $res;
    }
    //班次删除
    public function del($id)
    {
        $model = new ModelShifts;
        $res = $model->delete($id);
        return $res;
    }
    //班次查询
    public function shifts($day,$title)
    {
        $model = new ModelShifts;
        $res = $model->shifts($day,$title);
        return $res;
    }
}
