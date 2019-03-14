<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\User as DomainUser;
use PhalApi\Exception\BadRequestException;
/**
 * 员工接口
 */
class User extends Api
{
    public function getRules()
    {
        return [
            'add' => [
                'name' => ['name' => 'name', 'type' => 'string','require' => true, 'min' => 1, 'max' => 50, 'desc' => '名称', 'source' => 'post'],
                'age' => ['name' => 'age', 'type' => 'int', 'require' => true, 'min' => 1, 'max' => 200, 'desc' => '年龄', 'source' => 'post'],
                'sex' => ['name' => 'sex', 'type' => 'enum', 'require' => true, 'range' => ['1', '0'], 'desc' => '性别1:男,0:女', 'source' => 'post'],
                'section_id' => ['name' => 'section_id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '部门id', 'source' => 'post'],
                'r_id' => ['name' => 'r_id', 'type' => 'int', 'require' => true, 'min' => 1,  'desc' => '角色id', 'source' => 'post'],
                'job_number' => ['name' => 'job_number', 'type' => 'string', 'require' => true, 'regex' => '/^1[345678]\d{9}$/', 'desc' => '工号即手机号', 'source' => 'post'],
                'pwd' => ['name' => 'pwd', 'type' => 'string', 'require' => true, 'min' => 6, 'max' => 20, 'desc' => '密码', 'source' => 'post'],
                'phone' => ['name' => 'phone', 'type' => 'string', 'regex' => '/^1[345678]\d{9}$/', 'require' => true, 'desc' => '手机号', 'source' => 'post'],
                'img' => ['name' => 'img', 'type' => 'string','min'=>1,'max'=>255, 'require' => true, 'desc' => '图片路径', 'source' => 'post'],
            ],
            'checkPhone' => [
                'phone' => ['name' => 'phone', 'type' => 'string', 'regex' => '/^1[345678]\d{9}$/', 'require' => true, 'desc' => '手机号', 'source' => 'post'],
            ],
            'getUserById' => [
                'id' => ['name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '员工id'],
            ],
            'getUser' => [
                'page' => ['name' => 'page', 'type' => 'int', 'min' => 1, 'desc' => '当前页', 'default' => 1],
                'size' => ['name' => 'size', 'type' => 'int', 'min' => 1, 'desc' => '每页显示条数', 'default' => 5],
            ],
            'del' => [
                'id' => ['name' => 'id', 'type' => 'array', 'require' => true, 'format' => 'explode', 'min' => 1, 'desc' => '员工id(可传多个值","相隔)']
            ],
            'updateData' => [
                'id' => ['name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '员工id'],
                'name' => ['name' => 'name', 'type' => 'string', 'min' => 1, 'max' => 50, 'desc' => '名称', 'source' => 'post'],
                'age' => ['name' => 'age', 'type' => 'int',  'min' => 1, 'max' => 200, 'desc' => '年龄', 'source' => 'post'],
                'sex' => ['name' => 'sex', 'type' => 'enum',  'range' => ['1', '0'], 'desc' => '性别1:男,0:女', 'source' => 'post'],
                'section_id' => ['name' => 'section_id', 'type' => 'int',  'min' => 1, 'max' => 1000, 'desc' => '部门id', 'source' => 'post'],
                'r_id' => ['name' => 'r_id', 'type' => 'int', 'min' => 1,  'desc' => '角色id', 'source' => 'post'],
                'job_number' => ['name' => 'job_number', 'type' => 'string',  'regex' => '/^1[345678]\d{9}$/', 'desc' => '工号即手机号', 'source' => 'post'],
                'pwd' => ['name' => 'pwd', 'type' => 'string',  'min' => 6, 'max' => 20, 'desc' => '密码', 'source' => 'post'],
                'phone' => ['name' => 'phone', 'type' => 'string', 'regex' => '/^1[345678]\d{9}$/',  'desc' => '手机号', 'source' => 'post'],
                'img' => ['name' => 'img', 'type' => 'string','min'=>1,'max'=>255,  'desc' => '图片路径', 'source' => 'post'],
            ],
            'updateOpen' =>[
                'id' => ['name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '员工id'],
                'open_id' => ['name' => 'open_id', 'type' => 'string','require'=>true,'min'=>1,'max'=>30, 'desc' => '员工openid', 'source' => 'post'],
            ],
            'updatePwd' =>[
                'id' => ['name' => 'id', 'type' => 'int', 'require' => true, 'min' => 1, 'desc' => '员工id'],
                'pwd' => ['name' => 'pwd', 'type' => 'string',  'min' => 6, 'max' => 20, 'desc' => '密码', 'source' => 'post'],
            ],
        ];
    }

    /**
     * 员工添加
     * @desc传递数据 添加入库返回自增id
     * @return int id 自增id
     * @return string msg 描述
     */
    public function add()
    {
        //D层
        $domain = new DomainUser;
        $res['id'] = $domain->addData($this->name, $this->age, $this->sex, $this->section_id, $this->r_id, $this->job_number, $this->pwd, $this->phone,$this->img);
        $res['msg'] = '添加成功';
        return $res;
    }

    /**
     * 验证员工手机号
     * @desc员工手机号查询数据库是否存在
     * @return int code 是否存在
     * @return string desc 描述
     */
    public function checkPhone()
    {
        $domain = new DomainUser;
        $res = $domain->checkPhone($this->phone);
        if($res){
            $data['code'] = 1;
            $data['msg'] = '手机号存在';
        }else{
            $data['code'] = 0;
            $data['msg'] = '手机号不存在';
        }
        return $res;
    }

    /**
     * 获取成员信息
     * @desc通过id获取用户信息
     * @return int id 用户id
     * @return string name 姓名
     * @return int age 年龄
     * @return int sex 性别
     * @return int section_id 部门id
     * @return string job_number 工号
     * @return string pwd 密码
     * @return string phone 手机号
     * @return string img 头像
     * @exception 401 用户不存在
     */
    public function getUserById()
    {
        $domain = new DomainUser;
        $res = $domain->getUserById($this->id);
        if($res === false){
            throw new BadRequestException('用户不存在',1);
        }
        return $res;
    }

    /**
     * 获取员工列表
     * @desc传递分页参数获取员工列表
     *
     * @return array items 员工基本信息
     * @return int total 员工总数
     */
    public function getUser()
    {
        $domain = new DomainUser;
        $res = $domain->getUser($this->page,$this->size); 
        return $res;
    }

    /**
     * 员工删除
     * @desc 通过id删除数据
     *
     * @return int total 删除总条数
     * @return string msg 描述
     * @exception 402 操作失败
     */
    public function del()
    {
        $domain = new DomainUser;
        $res = $domain->del($this->id);
        if ($res == 0) {
            throw new BadRequestException('操作失败', 2);
        }
        $rs['total'] = $res;
        $rs['msg'] = '删除成功';
        return $rs;
    }

    /**
     * 员工修改
     * @desc 传员工id和需修改的值完成修改
     * @return int total 影响数
     * @return string msg 描述
     * @exception 402 操作失败
     */
    public function updateData()
    {
        $data = [
            'name' =>$this->name,
            'age' =>$this->age,
            'sex' =>$this->sex,
            'section_id' =>$this->section_id,
            'r_id' =>$this->r_id,
            'job_number' =>$this->job_number,
            'phone' =>$this->phone,
            'img' =>$this->img,
        ];
        $domain = new DomainUser;
        $res = $domain->updateData($this->id,$data);
        if ($res == 0) {
            throw new BadRequestException('操作失败', 2);
        }
        $rs['total'] = $res;
        $rs['msg'] = '成功';
        return $rs;
    }

    /**
     * 员工修改openid
     * @desc 传员工id和需修改的值完成修改
     * @return string msg 描述
     * @exception 402 操作失败
     */
    public function updateOpen(){
        $data = [
            'open_id' =>$this->open_id,
        ];
        $domain = new DomainUser;
        $res = $domain->updateOpen($this->id,$data);
        if ($res == 0) {
            throw new BadRequestException('操作失败', 2);
        }
        $rs['msg'] = '成功';
        return $rs;
    }

    /**
     * 员工修改pwd
     * @desc 传员工id和需修改的值完成修改
     * @return string msg 描述
     * @exception 402 操作失败
     */
    public function updatePwd(){
        $data = [
            'pwd' =>$this->pwd,
        ];
        $domain = new DomainUser;
        $res = $domain->updatePwd($this->id,$data);
        if ($res == 0) {
            throw new BadRequestException('操作失败', 2);
        }
        $rs['msg'] = '成功';
        return $rs;
    }
}