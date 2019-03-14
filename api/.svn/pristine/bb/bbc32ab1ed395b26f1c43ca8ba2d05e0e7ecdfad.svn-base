<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;
use App\Domain\Shifts as DomainShifts;
/**
 * 班次接口
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Shifts extends Api
{
    public function getRules()
    {
        return array(
            'shifts_add' => array(
                'shifts_title' => array('name' => 'shifts_title','format' => 'utf8','desc'=>'班次名称','require' => true,  'source' => 'post'),
                'shifts_stime' => array('name' => 'shifts_stime', 'require' => true,  'desc' => '上班打卡时间', 'type' => 'string', 'source' => 'post'),
                'shifts_etime'=>array('name'=>'shifts_etime','desc'=>'下班打卡时间','require'=>true,'type'=>'string','source' => 'post'),
                'shifts_desc'=>array('name'=>'shifts_desc','desc'=>'班次描述','require'=>true,'type'=>'string','source' => 'post'),
                'shifts_day'=> array('name' => 'shifts_day', 'require' => true,  'desc' => '工作日', 'source' => 'post'),
                'elastic_time' => array('name' => 'elastic_time','desc'=>'弹性时间','require' => true,  'source' => 'post'),
                'shifts_role' => array('name' => 'shifts_role','desc'=>'作用角色','require' => true,  'source' => 'post','type'=>'int'),
            ),
            'shifts_upd' => array(
                'shifts_title' => array('name' => 'shifts_title','format' => 'utf8','desc'=>'班次名称',  'source' => 'post','default'=>''),
                'shifts_stime' => array('name' => 'shifts_stime',   'desc' => '上班打卡时间', 'type' => 'string', 'source' => 'post','default'=>''),
                'shifts_etime'=>array('name'=>'shifts_etime','desc'=>'下班打卡时间','type'=>'string','source' => 'post','default'=>''),
                'shifts_desc'=>array('name'=>'shifts_desc','desc'=>'班次描述','type'=>'string','source' => 'post','default'=>''),
                'shifts_day'=> array('name' => 'shifts_day',   'desc' => '工作日', 'source' => 'post','default'=>''),
                'elastic_time' => array('name' => 'elastic_time','desc'=>'弹性时间',  'source' => 'post','default'=>''),
                'shifts_role' => array('name' => 'shifts_role','desc'=>'作用角色',  'source' => 'post','type'=>'int','default'=>''),
                'shifts_id'=> array('name' => 'shifts_id', 'require' => true,  'desc' => '班次id','min'=>1,'type' => 'int', 'source' => 'post','default'=>''),
            ),
            'shifts_del' => array(
                'del_id'   => array('name' => 'del_id', 'require' => true,'format'=>'explode', 'desc' => '班次id(可用逗号隔开多个值)','type'=>'array'),
            ),
            'shifts' => array(
                'day'   => array('name' => 'day', 'desc' => '周几','type'=>'int'),
                'title'   => array('name' => 'title', 'desc' => '班次名称','type'=>'string'),
            ),
        );
    }

    /**
     * 班次添加
     * @desc 班次添加
     * @return int 成功插入后的id
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     */
   
    public function shifts_add()
    {
        $data=array(
            'shifts_title'=>$this->shifts_title,
            'shifts_stime'=>$this->shifts_stime,
            'shifts_etime'=>$this->shifts_etime,
            'shifts_desc'=>$this->shifts_desc,
            'shifts_day'=>$this->shifts_day,
            'elastic_time'=>$this->elastic_time,
            'shifts_role'=>$this->shifts_role,
        );
        $dn = new DomainShifts();
        $res = $dn->shifts_add($data);
        return $res;
    }
    /**
     * 班次修改
     * @desc 班次修改
     * @return jsonp data 影响行数
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     */
    public function shifts_upd()
    {
        $data=array(
            'shifts_title'=>$this->shifts_title,
            'shifts_stime'=>$this->shifts_stime,
            'shifts_etime'=>$this->shifts_etime,
            'shifts_desc'=>$this->shifts_desc,
            'shifts_day'=>$this->shifts_day,
            'elastic_time'=>$this->elastic_time,
            'shifts_role'=>$this->shifts_role,
        );
        $id=$this->shifts_id;
        $dn = new DomainShifts();
        $res = $dn->upd($id,$data);
        return $res;
    }
     /**
     * 班次删除
     * @desc 班次删除
     * @return jsonp data 影响行数
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     */
    public function shifts_del()
    {
        $id=$this->del_id;
        $dn = new DomainShifts();
        $res = $dn->del($id);
        return $res;
    }
    /**
     * 班次查询
     * @desc 班次查询
     * @return jsonp data 班次列表
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     */
    public function shifts()
    {
        $dn = new DomainShifts();
        $day=$this->day;
        $title=$this->title;
        $res = $dn->shifts($day,$title);
        return $res;
    }
}
