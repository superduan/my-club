<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;
use App\Domain\Allot as DomainAllot;
/**
 * 分配权限接口
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Allot extends Api
{
    public function getRules()
    {
        return array(
            'allot' => array(
                'role_id' => array('name' => 'Allot_id', 'require' => true, 'min' => 1, 'desc' => '角色id', 'type' => 'int', 'source' => 'post'),
                'power_id' => array('name' => 'power_id', 'require' => true, 'format' => 'explode', 'desc' => '权限id', 'type' => 'array', 'source' => 'post'),
            ),
            'updr_p' => array(
                'role_id' => array('name' => 'Allot_id', 'require' => true, 'min' => 1, 'desc' => '角色id', 'type' => 'int', 'source' => 'post'),
                'power_id' => array('name' => 'power_id', 'require' => true, 'format' => 'explode', 'desc' => '权限id', 'type' => 'array', 'source' => 'post'),
            ),
        );
    }
    /**
     * 角色分配权限
     * @desc 角色分配权限
     * @return jsonp success ok
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     * @exception 408 未修改任何数据
     */
    public function allot()
    {
        $r_id = $this->role_id;
        $p_id = $this->power_id;
        $dn = new DomainAllot();
        $res = $dn->allot($r_id, $p_id);
        return $res;
    }

    /**
     * 修改角色权限
     * @desc 修改角色权限
     * @return jsonp success ok
     * @exception 401 参数传递错误
     * @exception 400 缺少参数
     * @exception 408 先添加角色权限
     */
    public function updr_p()
    {
        $r_id = $this->role_id;
        $p_id = $this->power_id;
        $dn = new DomainAllot();
        $res = $dn->updr_p($r_id, $p_id);
        if (!$res) {
            throw new BadRequestException('请先添加角色权限', 8);
        }
        return $res;
    }
}