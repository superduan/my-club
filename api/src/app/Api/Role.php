<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;
use App\Domain\Role as DomainRole;
/**
 * 角色查询接口
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class role extends Api {
    public function getRules() {
        return array(
            'role_add'=>array(
                'role_name'    =>  array('name'=>'role_name','require'=>true,'format' => 'utf8','min'=>1,'max'=>'6','desc'=>'角色名','source' => 'post'),
                'role_desc'    =>  array('name'=>'role_desc','require'=>true,'format' => 'utf8','min'=>1,'max'=>'30','desc'=>'角色描述','source' => 'post'),
            ),
            'del_role'=>array(
                'role_id'   => array('name' => 'role_id', 'require' => true,'format'=>'explode', 'desc' => '角色id(可用逗号隔开多个值)','type'=>'array'),
            ),
            'get_role'=>array(
                'role_id'   =>  array('name' => 'role_id', 'require' => true,'format'=>'explode', 'desc' => '角色获取角色id(可用逗号隔开多个值)','type'=>'array'),
            ),
            'upd_role'=>array(
                'upd_id'        =>  array('name' => 'upd_id', 'require' =>true, 'min' => 1, 'desc' => '要修改的角色id','type'=>'int','source' => 'post'),
                'role_name'    =>  array('name'=>'role_name','format' => 'utf8','desc'=>'角色名','source' => 'post','default'=>''),
                'role_desc'    =>  array('name'=>'role_desc','format' => 'utf8','desc'=>'角色描述','source' => 'post','default'=>''),
            ),
            'role_power'=>array(
                'role_id'    =>  array('name' => 'role_id', 'require' => true,'min'=>1, 'desc' => '角色id','type'=>'int'),
            ),
        );
    }

     /**
     * 添加角色
     * @desc 添加角色
     * @return int reg 添加成功的id
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     */

    public function role_add()
    {
        //插入的数据
        $arr=array(
            'role_name'=>$this->role_name,
            'role_desc'=>$this->role_desc,
        );
        $role_name=$arr['role_name'];
        $dn=new DomainRole();
        $check_role=$dn->check_name($role_name);
        if($check_role){
            throw new BadRequestException('当前权限已经存在，换一个试试',9);
            die;
        }
        $reg=$dn->add_role($arr);
        return $reg;
    }

    /**
     * 删除角色
     * @desc 删除角色
     * @return int 状态码 删除成功状态码
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     */
    
    public function del_role()
    {
        $role_id=$this->role_id;
        $dn=new DomainRole();
        $reg=$dn->del_role($role_id);
        return $reg;
    }

    /**
     * 获取要修改的角色
     * @desc 获取要修改的角色
     * @return array data 要修改的角色数据
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     * @exception 408 没有数据
     */

    public function get_role()
    {
        $upd_id=$this->role_id;
        $dn=new DomainRole();
        $reg=$dn->get_role($upd_id);
        if (!$reg) {
            throw new BadRequestException('请您联系管理员核查数据',8);
        }
        return $reg;
    }

    /**
     * 修改角色
     * @desc 修改角色
     * @return int data 修改成功的影响行数
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     * @exception 408 未修改任何数据
     */
     public function upd_role()
     {
        //修改数据
        $id=$this->upd_id;
        $arr=array(
            'role_name'=>$this->role_name,
            'role_desc'=>$this->role_desc,
        );
        $dn=new DomainRole();
        $reg=$dn->upd_role($id,$arr);
        if ($reg==0) {
            throw new BadRequestException('您未修改任何数据',8);
        }
        return $reg;
     }
      /**
     * 获取角色权限
     * @desc 获取角色权限
     * @return jsonp data 角色权限结果
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     * @exception 408 未修改任何数据
     */
     public function role_power()
     {
         $role_id=$this->role_id;
         $dn=new DomainRole();
         $reg=$dn->role_power($role_id);
         return $reg;
     }
      /**
     * 获取角色列表
     * @desc 获取角色列表
     * @return jsonp data 角色列表
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     * @exception 408 未修改任何数据
     */
    public function role()
    {
        $dn=new DomainRole();
        $role=$dn->role();
        return $role;
    }
    
}