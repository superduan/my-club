<?php
namespace App\Domain;

use App\Model\User as ModelUser;
use PhalApi\Exception\BadRequestException;


class User {
    //添加入库
    public function addData($name,$age,$sex,$section_id,$r_id,$job_number,$pwd,$phone,$img){
        $data = [
            'name'=>$name,
            'age'=>$age,
            'sex'=>$sex,
            'section_id'=>$section_id,
            'r_id'=>$r_id,
            'job_number'=>$job_number,
            'pwd'=>$pwd,
            'phone'=>$phone,
            'img'=>$img,
        ];
        $model = new ModelUser;
        $res = $model->insert($data);
        return $res;
    }

    //验证员工手机号
    public function checkPhone($phone){
        $model = new ModelUser;
        $res = $model->checkPhone($phone);
        return $res;
    }

    //通过id获取用户信息
    public function getUserById($id){
        $model = new ModelUser;
        $res = $model->getUserById($id);
        return $res;
    }

    //获取用户列表
    public function getUser($page,$size){
        $model = new ModelUser;
        $rs['items'] = $model->getUserItems($page,$size);
        $rs['total'] = $model->getUserTotal();
        return $rs;
    }

    //员工删除
    public function del($id){
        $model = new ModelUser;
        $res = $model->delete($id);
        return $res;
    }

    //通过id更新数据
    public function updateData($id,$data){
        // foreach($data as $key=>$val){
        //     if(empty($val)){
        //         unset($data[$key]);
        //     }
        // }
        $model = new ModelUser;
        $res = $model->update($id,$data);
        return $res;
    }

    //通过id更新openid
    public function updateOpen($id,$data){
        $model = new ModelUser;
        $res = $model->update($id,$data);
        return $res;
    }
    
    //通过id更新pwd
    public function updatePwd($id,$data){
        $model = new ModelUser;
        $res = $model->update($id,$data);
        return $res;
    }
}