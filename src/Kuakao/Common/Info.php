<?php
namespace Kuakao\Common;

class Info
{
    /**
     * 短信API
     * @var string
     */
    private static $urlApi = 'http://smsapi.c123.cn/OpenPlatform/OpenApi';

    /**
     * 短信发送帐号
     * @var string
     */
    private static $user = '1007@500896280003';

    /**
     * 短信发送密钥
     * @var string
     */
    //private static $authkey = 'A8E71A20C73471A0715BCB17A9D1E870';

    private static $authkey = 'D3BAEB9109F4F38642F6498EAC557E0A';

    /**
     * 通道组编号
     * @var int
     */
    //private static $cgid = 1053;
    private static $cgid = 184;

    /**
     * 签名编号 ,可以为空时，使用系统默认的编号
     * @var int
     */
    private static $csid = 1915;

    /**
     * 发送类型 ，可以有sendOnce短信发送，sendBatch一对一发送，sendParam动态参数短信接口
     * @var string
     */
    public static $action = 'sendOnce';

    /**
     * 发送手机短信
     * @param $mobile 手机号码，多个用英文逗号分割
     * @param $content 短信内容；使用sendBatch一对一发送，多个内容之间使用 {|} 分隔，如: 内容一{|}内容二
     * @param string $time 定时发送，为空时表示立即发送,yyyyMMddHHmmss 如:20130721182038
     * @author wangbingang<bingangwang@kuakao.com>
     */
    public static function send($mobile, $content, $time='')
    {
        //return array('status'=>1, 'info'=>'发送成功');
        $data = [
            'action'=> self::$action,
            'ac'=> self::$user,
            'authkey'=> self::$authkey,
            'cgid'=> self::$cgid,
            'm'=> $mobile,
            'c'=> $content,
            'csid'=>self::$cgid,
            't'=> $time,
        ];
        $result = self::postSMS($data);
        preg_match_all('/result="(.*?)"/',$result,$res);
        if(trim($res[1][0]) == '1' )  //发送成功 ，返回企业编号，员工编号，发送编号，短信条数，单价，余额
        {
            preg_match_all('/\<Item\s+(.*?)\s+\/\>/',$result,$item);
            for($i=0;$i<count($item[1]);$i++){
                preg_match_all('/cid="(.*?)"/',$item[1][$i],$cid);
                preg_match_all('/sid="(.*?)"/',$item[1][$i],$sid);
                preg_match_all('/msgid="(.*?)"/',$item[1][$i],$msgid);
                preg_match_all('/total="(.*?)"/',$item[1][$i],$total);
                preg_match_all('/price="(.*?)"/',$item[1][$i],$price);
                preg_match_all('/remain="(.*?)"/',$item[1][$i],$remain);

                $send['cid']=$cid[1][0];           //企业编号
                $send['sid']=$sid[1][0];          //员工编号
                $send['msgid']=$msgid[1][0];   //发送编号
                $send['total']=$total[1][0];   //计费条数
                $send['price']=$price[1][0];   //短信单价
                $send['remain']=$remain[1][0]; //余额
                $send_arr[]=$send;     //数组send_arr 存储了发送返回后的相关信息
            }
            //echo "发送成功,状态为".$res[1][0];   //发送成功返回的值
            return array('status'=>$res[1][0], 'info'=>'发送成功');
        }
        else  //发送失败的返回值
        {
            switch(trim($res[1][0])){
                case  0:
                    return array('status'=>0, 'info'=>'帐户格式不正确(正确的格式为:员工编号@企业编号)');
                    break;
                case  -1:
                    return array('status'=>-1, 'info'=>'服务器拒绝(速度过快、限时或绑定IP不对等)如遇速度过快可延时再发');
                    break;
                case  -2:
                    return array('status'=>-2, 'info'=>'密钥不正确');
                    break;
                case  -3:
                    return array('status'=>-3, 'info'=>'密钥已锁定');
                    break;
                case  -4:
                    return array('status'=>-4, 'info'=>'参数不正确(内容和号码不能为空，手机号码数过多，发送时间错误等)');
                    break;
                case  -5:
                    return array('status'=>-5, 'info'=>'无此帐户');
                    break;
                case  -6:
                    return array('status'=>-6, 'info'=>'帐户已锁定或已过期');
                    break;
                case  -7:
                    return array('status'=>-7, 'info'=>'帐户未开启接口发送');
                    break;
                case  -8:
                    return array('status'=>-8, 'info'=>'不可使用该通道组');
                    break;
                case  -9:
                    return array('status'=>-9, 'info'=>'帐户余额不足');
                    break;
                case  -10:
                    return array('status'=>-10, 'info'=>'内部错误');
                    break;
                case  -11:
                    return array('status'=>-11, 'info'=>'扣费失败');
                    break;
                default:break;
            }
        }

    }

    /**
     * 发送短信
     * @param string $data
     * @return string
     */
    private static function postSMS($data='')
    {
        $row = parse_url(self::$urlApi);
        $host = $row['host'];
        $port = isset($row['port']) ? $row['port']:80;
        $file = $row['path'];
        $post = '';
        while (list($k,$v) = each($data))
        {
            $post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
        }
        $post = substr( $post , 0 , -1 );
        $len = strlen($post);
        $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.0\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n",$receive);
            unset($receive[0]);
            return implode("",$receive);
        }
    }
}