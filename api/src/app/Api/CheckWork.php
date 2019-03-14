<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\CheckWork as DomainCheckWork;
use PhalApi\Exception\BadRequestException;
/**
 * 考勤记录
 */
class CheckWork extends Api
{
    public function getRules()
    {
        return [
            'add' => [
                // 'job_number' => ['name' => 'job_number', 'type' => 'string','require' => true, 'min' => 11, 'max' => 11, 'desc' => '工号', 'source' => 'post'],
                // 'name' => ['name' => 'name','type'=>'string','require'=>true,'min'=>1,'max'=>50,'desc'=>'姓名','source'=>'post'],
                // 'dateline' => ['name' => 'dateline','type'=>'date','require'=>true,'desc'=>'日期','source'=>'post'],
                // 'sign_time' => ['name' => 'sign_time','type'=>'date','require'=>true,'format'=>'timestamp','desc'=>'签到时间','source'=>'post'],
                // 'sign_status' => ['name' => 'sign_status','type'=>'enum','require'=>true,'range'=>['正常','迟到','早退','请假','旷工'],'desc'=>'签到状态','source'=>'post'],
                // 'section_id' => ['name' => 'section_id','type'=>'int','require'=>true,'min'=>1,'desc'=>'部门id','source'=>'post'],
                // 'type' => ['name' => 'type','type'=>'enum','range'=>[0,1],'desc'=>'是否加班','default'=>'0','source'=>'post'],
            ],
            'findInfo' => [
                'page' => ['name' => 'page', 'type' => 'int', 'min' => 1, 'desc' => '当前页', 'default' => 1],
                'size' => ['name' => 'size', 'type' => 'int', 'min' => 1, 'desc' => '每页显示条数', 'default' => 5],
                'select'=>['name'=>'select','type'=>'array','format'=>'explode','default'=>'*','desc'=>'可选字段'],
                'start_time' => ['name' => 'start_time','type'=>'date','desc'=>'开始时间'],
                'end_time' => ['name' => 'end_time','type'=>'date','desc'=>'结束时间'],
                'name' => ['name' => 'name','type'=>'string','desc'=>'姓名'],         
            ],
            'getInfo' => [
                'page' => ['name' => 'page', 'type' => 'int', 'min' => 1, 'desc' => '当前页', 'default' => 1],
                'size' => ['name' => 'size', 'type' => 'int', 'min' => 1, 'desc' => '每页显示条数', 'default' => 5],
                'dateline' => ['name' => 'start_time','type'=>'date','desc'=>'日期'],
                'name' => ['name' => 'name','type'=>'string','desc'=>'姓名'],
                'type' => ['name' => 'type','type'=>'enum' ,'default'=>'0','range'=>['0','1'],'desc'=>'是否加班'],
            ],
            'findWork'=>[
                'start_time' => ['name' => 'start_time', 'require' => true,'type'=>'string','desc'=>'开始时间'],
                'end_time' => ['name' => 'end_time', 'require' => true,'type'=>'string','desc'=>'结束时间'],
                'job_number' => ['name' => 'job_number', 'type' => 'string','require' => true, 'min' => 11, 'max' => 11, 'desc' => '工号'],
            ],
            'getInfo' => [
                'page' => ['name' => 'page', 'type' => 'int', 'min' => 1, 'desc' => '当前页', 'default' => 1],
                'size' => ['name' => 'size', 'type' => 'int', 'min' => 1, 'desc' => '每页显示条数', 'default' => 10],
                'start_time' => ['name' => 'start_time','type'=>'date','desc'=>'开始时间'],
                'end_time' => ['name' => 'end_time','type'=>'date','desc'=>'结束时间'],
                'name' => ['name' => 'name','type'=>'string','desc'=>'姓名'],
                'status' => ['name' => 'status','type'=>'string','default'=>'','desc'=>"考勤状态('正常','异常','请假','无效')"],
                'type' => ['name' => 'type','type'=>'string' ,'default'=>'','desc'=>'是否加班'],
                'job_number' => ['name' => 'job_number', 'type' => 'string', 'min' => 11, 'max' => 11, 'desc' => '工号'],
            ],
        ];
    }

    /**
     * 签到
     * @desc传递数据 添加入库返回自增id
     * @return int id 自增id
     * @return string msg 描述
     */
    public function add()
    {
        $manager = new \MongoDB\Driver\Manager('mongodb://123.206.73.64:27017');
        //搜索条件
        $filter = [];
        $options = [
            'projection'=>['_id'=>0],//不输出_id字段 
        ];
        $query = new \MongoDB\Driver\Query($filter,$options);
        //表名
        $tablename = 'test.tables'.(date('i')-1);
        //执行查询
        $list = $manager->executeQuery($tablename,$query);
        $arr = [];
        foreach($list as $val){
            $arr[] = $val;
        }
        if(!$arr){
            return ;
        }
        $data = json_decode(json_encode($arr),1);
        // return $data;
        //D层
        $domain = new DomainCheckWork;
        $result =  $domain->addData($data);
        if($result){
            //入库成功清空表
            $bulk = new \MongoDB\Driver\BulkWrite;
            $bulk->delete([]);
            $manager->executeBulkWrite($tablename,$bulk);
        }
        return $result;
    }

    /**
     * 签到查询
     * @desc传递分页参数获取签到列表
     *
     * @return array items 签到基本信息
     * @return int total 签到总数
     */
    public function findInfo()
    {
        $data = [
            'page' => $this->page,
            'size' => $this->size,
            'select' => $this->select,
            'where' => [],
        ];
        if(!empty($this->start_time)){
            $data['where']['dateline >= ?'] = $this->start_time;
        }
        if(!empty($this->end_time)){
            $data['where']['dateline <= ?'] = $this->end_time;
        }
        if(!empty($this->name)){
            $data['where']['name'] = $this->name;
        }
        //D层
        $domain = new DomainCheckWork;
        $res = $domain->findInfo($data);
        return $res;
    }

    /**
     * 查询是否签到
     * @desc传递数据判断时是否签到
     */
    public function findWork(){
        $data = [
            'start_time' => $this->start_time,
            'end_time'=>$this->end_time,
            'job_number'=>$this->job_number,
        ];
        $domain = new DomainCheckWork;
        $res = $domain->findWork($data);
        return $res;
    }

    /**
     * 考勤脚本
     * @desc 自动过滤昨天的签到日志入库
     *
     * @return
     */
    public function selectWork(){
        $domain = new DomainCheckWork;
        $res = $domain->selectWork();
        return $res;
    }

    /**
     *  统计
     * @desc 统计精确考勤数据
     *
     * @return void
     */
    public function getInfo(){
        $data = [
            'page' => $this->page,
            'size' => $this->size,
            'where' => [],
        ];
        $time = date('Y-m-d',time()-5184000);
        if($this->start_time<$time){
            return '开始时间不能超过两个月哦';
        }
        empty($this->start_time)?$time:$data['where']['dateline >= ?'] = $this->start_time;
        empty($this->end_time)?'':$data['where']['dateline <= ?'] = $this->end_time;
        empty($this->name)?'':$data['where']['name'] = $this->name;
        empty($this->status)?'':$data['where']['status'] = $this->status;
        empty($this->type)?'':$data['where']['type'] = $this->type;
        empty($this->job_number)?'':$data['where']['job_number'] = $this->job_number;
        //D层
        $domain = new DomainCheckWork;
        $res = $domain->getInfo($data);
        return $res;
    }
}