<?php

namespace Kuakao\AppBundle\Controller;

use Kuakao\AdminBundle\Entity\TInfoLog;
use Kuakao\AdminBundle\Entity\TMember;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Kuakao\Common\Globals;
use Kuakao\Common\Info;
use Kuakao\Common\cache\File;


class RegisterController extends Controller
{
    public function __construct()
    {
        $this->cache = new File();
    }

    public function addAction(Request $request)
    {
        $mobile = $request->request->get('mobile');
        $password = $request->request->get('password');
        $code   = $request->request->get('code');
        $source = $request->request->get('source', 'IOS');
        $scene = $request->request->get('scene');

        if(!Globals::is_mobile($mobile)) {
            return new JsonResponse(['status'=>'-1', 'info'=> '请填写正确的手机号码']);
        }
        if(!$scene ||  ! $this->cache->get($scene.$mobile) || $code != $this->cache->get($scene.$mobile)) {
            return new JsonResponse(['status'=>'-2', 'info'=> '短信验证码不正确']);
        }
        if(!Globals::is_passwd($password)) {
            return new JsonResponse(['status'=>'-3', 'info'=> '用户密码由数字和字母组合长度为6-20位']);
        }
        $memberModel = new TMember();
        $memberModel->setMobile($mobile);
        $memberModel->setName($mobile);
        $memberModel->setStatus(1);
        $memberModel->setSource($source);
        $memberModel->setPassword($password);
        $memberModel->setAddTime(time());
        $em = $this->getDoctrine()->getManager();
        $em->persist($memberModel);
        $em->flush();

        return new JsonResponse(['status'=>'1', 'info'=> '注册成功']);
    }

    /**
     * 发送验证码
     * @param Request $request
     * @return JsonResponse
     */
    public function sendInfoAction(Request $request)
    {
        $scene  = $request->request->get('scene', 'register');
        $mobile = $request->request->get('mobile');
        if(!Globals::is_mobile($mobile)) {
            return new JsonResponse(['status'=>'-1', 'info'=> '请填写正确的手机号码']);
        }
        if( $this->cache->get($scene.$mobile.'_time')+60 > time() ) {
            return new JsonResponse(['status'=>'-2', 'info'=> '2次验证码发送时间间隔不得超过60秒']);
        }
        $code = Globals::random(4);
        $this->cache->set($scene.$mobile, $code);
        $this->cache->set($scene.$mobile.'_time', time());
        $result = Info::send($mobile, $code);
        //发送日志记录到数据库
        $infoLog = new TInfoLog();
        $infoLog->setMobile($mobile);
        $infoLog->setContent($code);
        $infoLog->setSendtime(time());
        $infoLog->setStatus($result['info']);
        $infoLog->setCode($result['status']);
        $infoLog->setUser($scene);
        $em = $this->getDoctrine()->getManager();
        $em->persist($infoLog);
        $em->flush();
        if($result['status'])
        {
            return new JsonResponse(array('status'=>1, 'info'=>'发送成功'));
        }
        else
        {
            return new JsonResponse(array('status'=>0, 'info'=>'发送失败，请稍候重试'));
        }
    }
}