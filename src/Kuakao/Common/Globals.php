<?php

namespace Kuakao\Common;

use Kuakao\AdminBundle\Controller\TInfoLogController;
use Kuakao\AdminBundle\Entity\TInfoLog;
use Kuakao\Common\Info;
use Symfony\Component\HttpFoundation\Request;

/**
 * 公用类，常用方法库
 * Class Globals
 * @package Kuakao\Common
 */
class Globals
{
    /**
     * 判断email格式是否正确
     * @param $email
     * @return boolean 如果验证失败返回false,验证成功返回true
     */
    public static function is_email($email)
    {
        return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
    }

    /**
     * 验证是否是手机号码
     *
     * @param string $phone 待验证的号码
     * @return boolean 如果验证失败返回false,验证成功返回true
     */
    public static function is_mobile($phone)
    {
        if (strlen ( $phone ) != 11 || ! preg_match ( '/^1[3|4|5|7|8][0-9]\d{4,8}$/', $phone )) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 判断字符串是否为utf8编码，英文和半角字符返回ture
     * @param $string
     * @return bool
     */
    public static function is_utf8($string)
    {
        return preg_match('%^(?:
					[\x09\x0A\x0D\x20-\x7E] # ASCII
					| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte
					| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
					| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
					| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
					| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3
					| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15
					| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16
					)*$%xs', $string);
    }

    public static function sendCode($mobile, $scene='app_code', $code = '')
    {
        if(!$code) {
            $code = self::random(4);
        }
        $_SESSION[$scene] = $code;
        $result = Info::send($mobile, $code);
        //发送日志记录到数据库
        $em = new TInfoLogController();
        $em->add($mobile, $code, $scene, $result['info'], $result['status']);
        if($result['status'])
        {
            return array('status'=>1, 'info'=>'发送成功');
        }
        else
        {
            return array('status'=>0, 'info'=>'发送失败，请稍候重试');
        }
    }

    /**
     * 产生随机字符串
     *
     * @param    int        $length  输出长度
     * @param    string     $chars   可选的 ，默认为 0123456789
     * @return   string     字符串
     */
    public static function random($length, $chars = '0123456789')
    {
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }

    /**
     * 检测用户密码是否合法
     * @param $psd
     * @return bool
     */
    public static function is_passwd($psd)
    {
        if (preg_match("/^(\w){6,20}$/",$psd,$password))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 对用户的密码进行加密
     * @param $password
     * @param $encrypt //传入加密串，在修改密码时做认证
     * @return array/password
     */
    public static function  password($password, $encrypt='')
    {
        $pwd = array();
        $pwd['encrypt'] =  $encrypt ? $encrypt : self::random(6, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
        $pwd['password'] = md5(md5(trim($password)).$pwd['encrypt']);
        return $encrypt ? $pwd['password'] : $pwd;
    }

    /**
     * 获取短信验证码内容
     * @param $code 验证码
     * @return mixed
     */
    public static function Info($code)
    {
        $str = '【考研啦】验证码：{code}，您正在进行身份认证。考研啦，考研从这里开始。';
        return str_replace('{code}', $code, $str);
    }

    /**
     * 压缩图片大小
     * @param $imgsrc 原始图片路径
     * @param $imgdst 压缩图片路径
     * @return mixed
     */
    public static function image_png_size_add($imgsrc,$imgdst)
    {
        list($width,$height) = getimagesize($imgsrc);
        header('Content-Type:image/png');
        $image_wp=imagecreatetruecolor($width, $height);
        $image = imagecreatefrompng($imgsrc);
        imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $width, $height, $width, $height);
        if(imagejpeg($image_wp,$imgdst,75)){
            imagedestroy($image_wp);
            return true;
        }else{
            return false;
        }
    }

}