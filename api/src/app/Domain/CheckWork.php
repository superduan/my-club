<?php
namespace App\Domain;

use App\Model\CheckWork as ModelCheckWork;
use App\Model\User as ModelUser;
use App\Model\Work as ModelWork;
use PhalApi\Exception\BadRequestException;


class CheckWork {
    //添加入库
    public function addData($data){
        $model = new ModelCheckWork;
        $res = $model->insertAll($data);
        return $res;
    }


    //获取签到列表
    public function findInfo($data){
        $data['select'] = implode(',',$data['select']);
        $model = new ModelCheckWork;
        $rs['items'] = $model->findInfoItems($data);
        $rs['total'] = $model->findInfoTotal($data);
        return $rs;
    }

    //获取签到大概
    public function getInfo($data){
        // return $data['where'];
        $model = new ModelWork;
        $arr = $model->getInfoItems($data);
        // foreach($arr as &$val){
        //     $info = $model->getInfoDatail($val['job_number'],$val['dat'],$val['section_id']);
        //     foreach($info as $v){
        //         $val[$v['sign_status']] = $v['count(sign_status)'];
        //     }
        // }
        $rs['items'] = $arr;
        $rs['total'] = $model->getInfoTotol($data);
        return $rs;
    }

    //查询是否签到
    public function findWork($data){
        $model = new ModelCheckWork;
        $res = $model->findWork($data);
        return $res;
    }

    public function selectWork(){
        //统计今天的签到状态
        $dateline = date("Y-m-d", strtotime(date("Y-m-d")));
        // $dateline = '2019-01-11';
        //自身模型
        $model = new ModelCheckWork;
        //员工模型
        $userModel = new ModelUser;
        //所有员工信息
        $user = $userModel->selectInfo();
        //循环查询员工签到
        foreach($user as $val){
            //获取第一次和最后一次签到时间
            $result = $model->selectWork([
                'job_number'=>$val['job_number'],
                'dateline'=>$dateline,
            ]);
            //默认数组
            $arr = [
                'user_id'=>$val['id'],
                'job_number'=>$val['job_number'],
                'name'=>$val['name'],
                'r_id'=>$val['role_id'],
                'r_name'=>$val['role_name'],
                'section_id'=>$val['section_id'],
                'section_name'=>$val['section_name'],
                'dateline'=>$dateline,
            ];
            // return $arr;
            if(!empty($result)){
                //有签到的值
                $judge = $model->judge($arr['user_id'],$result[0]['big'],$result[0]['small'],'');
            }else{
                //无签到的值
                $judge = $model->judge($arr['user_id'],'','',$dateline);
                $judge['sduration'] = ceil($judge['sduration']);
                $judge['eduration'] = ceil($judge['eduration']);
            }
            $list[] = array_merge($arr,$judge);
        }
        // return $list;
        $workModel = new ModelWork;
        $rs = $workModel->add($list);
        return $rs;
    }
}