<?php
/**
 * 请在下面放置任何您需要的应用配置
 *
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author dogstar <chanzonghuang@gmail.com> 2017-07-13
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        // 'Token' => array('name' => 'Token', 'require' => true,'desc'=>'token','type'=>'string','min'=>32,'max'=>32),
    ),
    'wechat' => array(
        'token'=>'Wechat',
        'appid'=>'wxee42f58964d9037a',
        'appsecret'=>'f3772b564848167148097b5924c48cf6',
    ),
    /**
     * 接口服务白名单，格式：接口服务类名.接口服务方法名
     *
     * 示例：
     * - *.*         通配，全部接口服务，慎用！
     * - Site.*      Api_Default接口类的全部方法
     * - *.Index     全部接口类的Index方法
     * - Site.Index  指定某个接口服务，即Api_Default::Index()
     */
    'service_whitelist' => array(
        'Site.Index',
        // 'Login.*',
        '*.*',
        'Wechat.*',
        'Position.*',
        'Shifts.Shifts',
        'CheckWork.Add',
        'CheckWork.FindWork',
        'CheckWork.SelectWork',
        'Leave.Leave',
        'Leave.Leave_open',
        'Menu.GetInfo',
        //'*.*'
    ),
);