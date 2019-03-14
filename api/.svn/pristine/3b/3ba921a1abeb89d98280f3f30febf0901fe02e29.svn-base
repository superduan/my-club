<?php
namespace App\Domain;

use App\Model\Allot as ModelAllot;

class Allot {
    //分配权限
    public function allot($r_id,$p_id)
    {
        $model = new ModelAllot;
        $res = $model->allot($r_id,$p_id);
        return $res;
    }
    //修改角色权限
    public function updr_p($r_id,$p_id)
    {
        //先删除角色权限
        $model = new ModelAllot;
        $del= $model->del($r_id);
        if($del){
            $res = $model->allot($r_id,$p_id);
        }else{
            $res = 0;
        }
        return $res;
    }
}