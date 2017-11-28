<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Repository\TAdminRepository;
use Kuakao\Common\Globals;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{

    /**
     * todo 后台登录
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @author wangbingang<bingangwang@kuakao.com>
     */
    public function loginAction(Request $request)
    {
        //判断用户是否登录，如果登录则跳转到系统页面
        if( isset($_SESSION['_sf2_attributes']['admin_login_id']) ) {
            header("location:/admin/menu/");
            exit;
        }
        if($request->isMethod('POST'))
        {
            $username = $request->request->get('username');
            if(!$username) {
                return new JsonResponse(['status'=>-1, 'info'=>'请填写用户名']);
            }
            $password = $request->request->get('password');
            if(!$password) {
                return new JsonResponse(['status'=>-2, 'info'=>'请填写登录密码']);
            }
            $user = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdmin')->findOneBy(['username'=>$username]);
            if(!$user) {
                return new JsonResponse(['status'=>-3, 'info'=>'用户名或密码错误']);
            }
            if( $user->getPassword() != Globals::password($password, $user->getEncrypt()) ) {
                return new JsonResponse(['status'=>-3, 'info'=>'用户名或密码错误']);
            }
            //登录成功
            $session = $this->getRequest()->getSession();
            $session->set('admin_login_id',   $user->getUserid());
            $session->set('admin_login_data', $user);
            return new JsonResponse(['status'=>1, 'info'=>'登录成功']);
        }
        return $this->render('KuakaoAdminBundle:Index/login.html.twig');
    }

    /**
     * 安全退出
     */
    public function loginOutAction()
    {
        $session = $this->getRequest()->getSession();
        $session->remove('admin_login_id');
        $session->remove('admin_login_data');
        session_destroy();
        header("location:/admin/");
        exit;
    }
}