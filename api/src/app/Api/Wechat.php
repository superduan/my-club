<?php
namespace App\Api;

use PhalApi\Api;
use PhalApi\Exception\BadRequestException;
/**
 * 微信开发接口
 *
 * @author: dogstar <chanzonghuang@gmail.com> 2014-10-04
 */

class Wechat extends Api
{
    private static $lng1="116.293365";
    private static $lat1="40.040108";
    //接入微信公众平台
    public function index()
    {
        $echostr=$_GET["echostr"];
        if (!$echostr) {
            //$this->response();
            $this->makemenu();
            $this->responseMsg();
        }
        //file_put_contents('1.txt',111);
        $token=\PhalApi\DI()->config->get('app.wechat.token');
        //接收微信服务器传来的值
        $signature=$_GET["signature"];
        $timestamp=$_GET["timestamp"];
        $nonce=$_GET["nonce"];
        //按照微信平台方法运算
        $tmpArr = array($timestamp,$token, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        //file_put_contents('1.txt',$tmpStr);
        //file_put_contents('2.txt',$signature);
        if( $signature==$tmpStr ){
            echo $echostr;
        }else{
            //echo $echostr;
            return false;
        }
    }
    //curl函数
    public function curl($url, $data = null)
    {
        $init = curl_init();
        curl_setopt($init, CURLOPT_URL, $url);
        curl_setopt($init, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($init, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($init, CURLOPT_SSL_VERIFYHOST, false);

        if ($data) {
            curl_setopt($init, CURLOPT_POST, true);
            curl_setopt($init, CURLOPT_POSTFIELDS, $data);
        }
        $result = curl_exec($init);
        curl_close($init);
        return $result;
    }
    //获取微信accesstoken
    public function getaccesstoken(){
        $access_token = \PhalApi\DI()->cache->get('access_token');
        if ($access_token) {
            return $access_token;
        }else{
            $appid = \PhalApi\DI()->config->get('app.wechat.appid');
            $appsecret = \PhalApi\DI()->config->get('app.wechat.appsecret');
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
            $data = $this->curl($url);
            $data = json_decode($data,true);
            $access_token = $data['access_token'];
            \PhalApi\DI()->cache->set("access_token",$access_token, 60*60);
            if ($access_token) {
                return $access_token;
            }else{
                return false;
            }
        }
    }
    //生成菜单
    public function makemenu()
    {
        $access_token = $this->getaccesstoken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        $menu_url="http://works.ljdhz.com/?service=App.Menu.GetInfo";
        $menu=$this->curl($menu_url);
        $menu=json_decode($menu,1)['data'];
        $menu=json_encode($menu,JSON_UNESCAPED_UNICODE);
        $data = $this->curl($url,$menu);
        echo $data;
        //print_r($data);
    }
    //认证判断
    public function auth($open_id)
    {
        $url="http://works.ljdhz.com/?service=App.Login.GetInfoByOpen&open_id=".$open_id;
        $info=$this->curl($url);
        //file_put_contents("3.txt",$info);
        $info=json_decode($info,1)['data'];
        //file_put_contents("3.txt",$info);
        if(empty($info)){
            \PhalApi\DI()->cache->set($open_id,0);
        }else{
            $info=json_encode($info);
            \PhalApi\DI()->cache->set($open_id,$info);
        }
    }
    //认证链接地址
    public function sendImg($postObj)
    {
        $data=array(
            'title'=>"认证绑定",
            'des'=>"点击图片填写认证信息,为了让我更好的服务与您,请您务必填写认证信息",
            'pic'=>"http://works.ljdhz.com/uploads/a82a226c2bfa3ee9fd960478bd260279.jpg",
            'url'=>"http://works.ljdhz.com:8087/index/wechat/personal?openid=".$postObj->FromUserName,
        );
        $result = $this->transmitImg($postObj,$data);
        return $result;
    }
    //我的考勤记录
    public function view($job_number)
    {
        //前一天
        $daytime= date("Y-m-d", strtotime("-1 day"));
        //本月开始时间
        $begindate=date('Y-m-01', strtotime(date("Y-m-d")));
        $url="http://works.ljdhz.com/?service=App.CheckWork.GetInfo&&start_time=".$begindate."&&end_time=".$daytime."&&job_number=".$job_number;
        $res=$this->curl($url);
        //file_put_contents("1.txt",$url);
        //file_put_contents("2.txt",$res);
        $data=json_decode($res,1)['data']['items'];
        if($data){
            $msg="您本月打卡记录为:\n";
            foreach ($data as $key => $val) {
                $msg.=$val['dateline']."-".$val['status'].",迟到：".$val['sduration']."分钟,早退:".$val['eduration']."分钟\n";
            }
        }else{
            $msg="打卡记录为月初到前一天的打卡记录,您暂时没有打卡记录";
        }
        return $msg;
    }
    //消息类型
    public function responseMsg()
    {
        $postStr = file_get_contents("php://input");
        
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            //$open_id = $postObj->FromUserName;
            //file_put_contents('2.txt',$open_id);
            //用户发送的消息类型判断
            switch ($RX_TYPE)
            {
                case 'event':
                //判断具体的时间类型（关注、取消、点击）
                $event = $postObj->Event;
                if ($event=='subscribe') { // 关注事件
                    //查看用户是否首次关注
                    $content = "欢迎关注《踏梦远行--云打卡》系统!\n\n<a href=\"http://works.ljdhz.com:8087/index/wechat/personal?openid=".$open_id."\">绑定账号体验更多功能.</a>\n\n发送指令:\n#考勤编号\n或\n@姓名\n就能查看员工考勤记录。\n如果有疑问，也可联系duan1355350889，电话:18600894014.";
                    $result = $this->transmitText($postObj, $content);
                    if(isset($postObj->EventKey)) {
                            // 扫特定二维码关注会携带相对应的参数，具体见下一篇
                    }

                    }elseif ($event=='CLICK') {//菜单点击事件
                        switch ($postObj->EventKey) {
                            case 'BAOKUGO':
                                $result = $this->sendImg($postObj);
                                break;
                            case 'BAI':
                                $info=\PhalApi\DI()->cache->get($postObj->FromUserName);
                                if(!$info){
                                        $url="http://works.ljdhz.com/?service=App.Login.GetInfoByOpen&open_id=".$postObj->FromUserName;
                                        $info=$this->curl($url);
                                        //file_put_contents("3.txt",$info);
                                        $info=json_decode($info,1)['data'];
                                        //file_put_contents("3.txt",$info);
                                        if(empty($info)){
                                            \PhalApi\DI()->cache->set($postObj->FromUserName,0);
                                            $result=$this->sendImg($postObj);
                                            break;
                                        }else{
                                            $info=json_encode($info);
                                            \PhalApi\DI()->cache->set($postObj->FromUserName,$info);
                                        }
                                }
                                $open_key=$postObj->FromUserName."lat";
                                $res=\PhalApi\DI()->cache->get($open_key);
                                if ($res>0.1) {
                                   $msg="请前往公司打卡";
                                   $result=$this->transmitText($postObj,$msg);
                                   break;
                                }
                                $info=json_decode($info,1);
                                //上班打卡
                                $curr_time=time();
                                $date=date("Y-m-d",$curr_time);
                                $post=array(
                                    'job_number'=>$info['job_number'],
                                    'name'=>$info['name'],
                                    'dateline'=>$date,
                                    'sign_time'=>$curr_time,
                                    'sign_status'=>"正常",
                                    'section_id'=>$info['section_id'],
                                    'type'=>0
                                );
                                $manager = new \MongoDB\Driver\Manager('mongodb://127.0.0.1:27017');
                                $bulk = new \MongoDB\Driver\BulkWrite; 
                                $bulk->insert($post);
                                $res=$manager->executeBulkWrite("test.tables".date('i',time()), $bulk);
                                $msg="打卡成功";
                                $result=$this->transmitText($postObj,$msg);
                                break;
                            case 'YE':
                                $url="http://works.ljdhz.com/?service=App.Position.Night";
                                $info=$this->curl($url,$position);
                                $msg=json_decode($info,true)['data'];
                                $result=$this->transmitText($postObj,$msg);
                                break;
                            case 'LEAVE':
                                $data=array(
                                    'title'=>"我要请假",
                                    'des'=>"点击图片进入请假页面",
                                    'pic'=>"http://works.ljdhz.com/uploads/b1bbf8c177eb0a2d2c8926962638be40.png",
                                    'url'=>"http://works.ljdhz.com:8087/index/wechat/leave?openid=".$postObj->FromUserName,
                                );
                                $result = $this->transmitImg($postObj,$data);
                                break;
                            case 'VIEW':
                                $info=\PhalApi\DI()->cache->get($postObj->FromUserName);
                                if(!$info){
                                    $result=$this->sendImg($postObj);
                                    break;
                                }
                                $info=json_decode($info,1);
                                $job_number=$info['job_number'];
                                $msg=$this->view($job_number);
                                $result=$this->transmitText($postObj,$msg);
                                break;
                            default:
                                
                                break;
                        }
                    }elseif ($event=='VIEW') {//连接跳转事件

                    }elseif ($event=='LOCATION') {//进入公众号事件
                        //获取经纬度
                        $Latitude=$postObj->Latitude;
                        $Longitude=$postObj->Longitude;
                        $data=array(
                            'Latitude'=>$Latitude,
                            'Longitude'=>$Longitude
                        );
                        $data=json_encode($data);
                        $data=json_decode($data,1);
                        $lat1=self::$lat1;
                        $lat2=$data['Latitude'][0];
                        $lng1=self::$lng1;
                        $lng2=$data['Longitude'][0];
                        $res=$this->distance($lng1,$lat1,$lng2,$lat2);
                        $open_key=$postObj->FromUserName."lat";
                        \PhalApi\DI()->cache->set($open_key,$res, 43200);
                        $this->auth($postObj->FromUserName);
                    }
                    break;

                case "text":    //文本消息
                    $result = $this->receiveText($postObj);
                    break;
                case "image":   //图片消息
                    $result = $this->receiveImage($postObj);
                    break;
                case "voice":   //语音消息
                    $result = $this->receiveVoice($postObj);
                    break;
                case "video":   //视频消息
                    $result = $this->receiveVideo($postObj);
                    break;
                case "location"://位置消息
                    $result = $this->receiveLocation($postObj);
                    break;
                case "link":    //链接消息
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknow msg type: ".$RX_TYPE;
                    break;
            }
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    private function receiveText($object)
    {
        $msg = trim($object->Content) ;
        $open_id=$object->FromUserName;
        $change=substr($msg,0,1);
        switch ($change) {
            case '#':
                $job_number=substr($msg,1);
                $content = $job_number;
                break;
            case '@':
                $name=substr($msg,1);
                $content = $name;
                break;
            default:
                $content="小心我盘你!";
                break;
        }
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 接收图片消息
     */
    private function receiveImage($object)
    {
        $content = "你发送的是图片，地址为：".$object->PicUrl;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 接收语音消息
     */
    private function receiveVoice($object)
    {
        $content = "你发送的是语音，媒体ID为：".$object->MediaId;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 接收视频消息
     */
    private function receiveVideo($object)
    {
        $content = "你发送的是视频，媒体ID为：".$object->MediaId;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 接收位置消息
     */
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 接收链接消息
     */
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    /*
     * 回复文本消息
     */
    private function transmitText($object, $content)
    {
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[text]]></MsgType>
        <Content><![CDATA[%s]]></Content>
        </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }
    //回复图文消息
    private function transmitImg($object, $data)
    {
        $textTpl = "<xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <ArticleCount>1</ArticleCount>
        <Articles>
        <item>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
        </item>
        </Articles>
        </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $data['title'],$data['des'],$data['pic'],$data['url']);
        return $result;
    }    
     //根据经纬度计算距离
     private function distance($lng1,$lat1,$lng2,$lat2)
     {
         //将角度转为弧度
         $radLat1=deg2rad($lat1);
         $radLat2=deg2rad($lat2);
         $radLng1=deg2rad($lng1);
         $radLng2=deg2rad($lng2);
         $a=$radLat1-$radLat2;//两纬度之差,纬度<90
         $b=$radLng1-$radLng2;//两经度之差纬度<180
         $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137;
         return $s;
     }  
}