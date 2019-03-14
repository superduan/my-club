<?php
namespace App\Api;

use PhalApi\Api;
use App\Domain\Login as DomainLogin;
use PhalApi\Exception\BadRequestException;
/**
 * 登录接口
 */
class Login extends Api {

    public function getRules()
    {
        return [
            'login'=>[
                'job_number'=>['name'=>'job_number','require'=>true,'min'=>11,'max'=>11,'desc'=>'工号','source'=>'post'],
                'pwd'=>['name'=>'pwd','require'=>true,'min'=>1,'max'=>20,'desc'=>'密码','source'=>'post'],
            ],
            'getInfo'=>[
                'job_number'=>['name'=>'job_number','require'=>true,'min'=>11,'max'=>11,'desc'=>'工号'],
            ],
            'getInfoByOpen'=>[
                'open_id'=>['name'=>'open_id','require'=>true,'min'=>1,'desc'=>'openid'],
            ],
        ];
    }

    /**
     * 登录
     * @desc 传参数登录成功返回token
     * @return int id 用户id
     * @return string job_number 工号
     * @return string token token32位
     * @exception 401 参数传递错误
     */
    public function login(){
        //组成数组
        $data['job_number'] = $this->job_number;
        $data['pwd'] = $this->pwd;
        //D层
        $domain = new DomainLogin();
        $res = $domain->checkNamePwd($data);
        //判断登录成功
        if (!$res) {
            throw new BadRequestException('登录失败', 1);
        }
        $rs = $res[0];
        //生成token
        $token = md5('sign'.time().$rs['id']);
        //存入框架缓存
        \PhalApi\DI()->cache->set($token,1, 43200);
        //返回信息
        
        $rs['token'] = $token;
        
        return $rs;
    }
    /**
     * 信息
     * @desc 传参数成功返回信息
     * @exception 401 参数传递错误
     */
    public function getInfo(){
        //组成数组
        $data['job_number'] = $this->job_number;
        //D层
        $domain = new DomainLogin();
        $res = $domain->getInfo($data);
        //判断登录成功
        if (!$res) {
            throw new BadRequestException('登录失败', 1);
        }
        return $res[0];
    }


    /**
     * openid获取信息
     * @desc 传参数成功返回信息
     * @exception 401 参数传递错误
     */
    public function getInfoByOpen(){
        //组成数组
        $data['open_id'] = $this->open_id;
        //D层
        $domain = new DomainLogin();
        $res = $domain->getInfoByOpen($data);
        //判断获取成功
        if (!$res) {
            throw new BadRequestException('获取失败', 1);
        }
        return $res;
    }
}