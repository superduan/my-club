<?php
namespace App\Model;

use PhalApi\Model\NotORMModel as NotORM;
use App\Model\Shifts as ModelShifts;
use App\Model\Leave as LeaveModel;

class CheckWork extends NotORM
{
    //设置表名
    protected function getTableName($id)
    {
        return "check_work";
    }

    //添加数据
    public function insertAll($data){
        return $this->getORM()->insert_multi($data);
    }

    //查询签到列表
    public function findInfoItems($data)
    {
        $res = $this->getORM()
            ->select($data['select'])
            ->where($data['where'])
            ->limit(($data['page'] - 1) * $data['size'], $data['size'])
            ->order('id desc')
            ->fetchAll();
        return $res;
    }

    //查询签到列表总数
    public function findInfoTotal($data)
    {
        $res = $this->getORM()
            ->where($data['where'])
            ->count();
        return $res;
    }

    //查询是否签到
    public function findWork($data)
    {
        $res = $this->getORM()
            ->where('job_number', $data['job_number'])
            ->where('sign_time >=?', $data['start_time'])
            ->where('sign_time <=?', $data['end_time'])
            ->fetch();
        return $res;
    }

    public function selectWork($where)
    {
        $sql = "select max(kaoqin_check_work.sign_time) as big,min(kaoqin_check_work.sign_time) as small,dateline
         from kaoqin_check_work where 1=1 
         and kaoqin_check_work.job_number=:job_number 
         and kaoqin_check_work.dateline =:dateline
         GROUP BY kaoqin_check_work.job_number,kaoqin_check_work.dateline";
        $result = $this->getORM()->queryAll($sql, [
            ':job_number' => $where['job_number'],
            ':dateline' => $where['dateline'],
        ]);
        return $result;
    }

    //判断签到状态
    public function judge($id, $big, $small, $dateline)
    {
        //返回数据模板
        $result['type'] = "未加班";
        $result['status'] = "无效";
        $result['sduration'] = 0;//迟到时间
        $result['eduration'] = 0;//早退时间
        $result['sign_stime'] = 0;
        $result['sign_etime'] = 0;
        
        $leaveModel = new LeaveModel;
        $ifleave = $leaveModel->ifleave($id, $dateline);
        //判断是否请假
        if (empty($small)) {
            if ($ifleave) {
                $result['status'] = '请假';
            }
        }
        if (!empty($small)) {
            //有签到数据，判断是否相同
            if($big==$small){
                $big = 0;
            }
            $result = $this->test($big, $small,$result,$ifleave);
            $result['sign_stime'] = $small;
            $result['sign_etime'] = $big;
        }
        return $result;
    }

    //签到时间和早晚 返回状态、加班、时差
    public function test($big, $small,$result,$ifleave)
    {
        //取出角色班次时间
        $ModelShifts = new ModelShifts;
        $array = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];
        $time = date("w", time());
        $shifts = $ModelShifts->shifts($time);
        //早上打卡时间
        $ztime = [
            'stime' => strtotime($shifts[0]['shifts_stime']) - $shifts[0]['elastic_time'] * 60,
            'etime' => strtotime($shifts[0]['shifts_stime']) + $shifts[0]['elastic_time'] * 60,
        ];
        // return $ztime;
        if (array_key_exists(1, $shifts)) {
            //加班时间
            $overtime = [
                '0' => strtotime($shifts[0]['shifts_etime']) - $shifts[1]['elastic_time'] * 60,
                '1' => strtotime($shifts[0]['shifts_etime']) + $shifts[1]['elastic_time'] * 60,
                '2' => strtotime($shifts[1]['shifts_etime']) - $shifts[1]['elastic_time'] * 60,
                '3' => strtotime($shifts[1]['shifts_etime']) + $shifts[1]['elastic_time'] * 60,
            ];
        } else {
            //正常时间
            $otime = [
                '0' => strtotime($shifts[0]['shifts_etime']) - $shifts[0]['elastic_time'] * 60,
                '1' => strtotime($shifts[0]['shifts_etime']) + $shifts[0]['elastic_time'] * 60,
            ];
        }
        //早
        switch ($small) {
            case ($ztime['stime'] <= $small) && ($small <= $ztime['etime']):
                $result['status'] = '正常';
                break;
            case $small > $ztime['etime']:
                //迟到
                $result['status'] = '异常';
                $result['sduration'] = ($small - $ztime['etime']) / 60;
                break;
        }
        if(empty($big)){
            if ($ifleave) {
                //请假
                $result['status'] = '请假';
            } else if($result['status']=='异常' || $result['status']=='正常') {
                //未请假
                $result['status'] = '异常';
            }else{
                $result['status'] = '无效';
            }
            return $result;
        }
        //晚
        if (array_key_exists(1, $shifts)) {
            //今日有加班
            switch ($big) {
                case $big < $overtime[0]:
                    //早退
                    $result['status'] = '异常';
                    $result['eduration'] = ($overtime[0] - $big) / 60;
                    break;
                case ($overtime[0] <= $big) && ($big <= $overtime[1]):
                    if($result['status'] == '正常'){
                            
                    }
                    break;
                case ($overtime[1] <= $big) && ($big <= $overtime[2]):
                    $result['status'] = '无效';
                    break;
                case ($overtime[2] <= $big) && ($big <= $overtime[3]):
                    if($result['status'] == '正常'){
                            
                    }
                    $result['type'] = '加班';
                    break;
            }
        } else {
            //今日无加班
            switch ($big) {
                case $big < $otime[0]:
                    //早退
                    $result['status'] = '异常';
                    $result['eduration'] = ($otime[0] - $big) / 60;
                    break;
                case ($otime[0] <= $big) && ($big <= $otime[1]):
                    if($result['status'] == '正常'){

                    }
                    break;
            }
        }
        return $result;
    }
}