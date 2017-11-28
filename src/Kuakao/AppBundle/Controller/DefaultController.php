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

class DefaultController extends Controller
{
    private $cache;

    public function __construct()
    {
        $this->cache = new File();
    }

    /**
     * 默认访问界面
     */
    public function indexAction(Request $request)
    {
        $useragent = $request->server->get('HTTP_USER_AGENT');

        if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
            $useragent)){
//            echo 'mobile';
            return $this->render('KuakaoAppBundle:Default/mobile.index.html.twig');
        }else{

//            echo  'pc';
            return $this->render('KuakaoAppBundle:Default/pc.index.html.twig');
        }
        exit;
    }

    /**
     * 注册接口，详细参数见API文档
     * @param Request $request
     * @return JsonResponse
     * @author wangbingang
     */
    public function registerAction(Request $request)
    {
        $mobile = $request->request->get('mobile');
        $password = $request->request->get('password');
        $code   = $request->request->get('code');
        $source = $request->request->get('source', 'IOS');
        $scene = $request->request->get('scene', 'register');

        if(!Globals::is_mobile($mobile)) {
            return new JsonResponse(['status'=>'-1', 'info'=> '请填写正确的手机号码']);
        }
        if(!$scene ||  ! $this->cache->get($scene.$mobile) || $code != $this->cache->get($scene.$mobile)) {
            return new JsonResponse(['status'=>'-2', 'info'=> '短信验证码不正确']);
        }
        if(!Globals::is_passwd($password)) {
            return new JsonResponse(['status'=>'-3', 'info'=> '用户密码由数字和字母组合长度为6-20位']);
        }
        $memberData = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMember')->findOneBy(['mobile'=>$mobile]);
        if($memberData) {
            return new JsonResponse(['status'=>'-4', 'info'=> '用户已存在']);
        }
        $memberModel = new TMember();
        $memberModel->setMobile($mobile);
        $memberModel->setName($mobile);
        $memberModel->setStatus(1);
        $memberModel->setSource($source);
        $memberModel->setPassword($password);
        $memberModel->setAddTime(time());
        $memberModel->setLastLoginTime(time());
        $em = $this->getDoctrine()->getManager();
        $em->persist($memberModel);
        $em->flush();
        $memberData = $em->getRepository('KuakaoAdminBundle:TMember')->findOneBy(['mobile'=>$mobile]);
        $this->cache->rm($scene.$mobile);
        //删除旧的access_token
        $this->cache->rm($this->cache->get($mobile));
        $this->cache->rm($this->cache->get($mobile).'Data');
        $str1 = Globals::random(10, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
        $str2 = Globals::random(15, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
        $loginKey = md5($str1.$str2.$mobile);
        $this->cache->set($mobile, $loginKey, 2592000);
        $this->cache->set($loginKey, $memberData->getId(), 2592000);
        $this->cache->set($loginKey.'Data', $memberData, 2592000);
        return new JsonResponse(['status'=>'1', 'info'=> '注册成功', 'access_token'=>$loginKey, 'expires_in'=>2592000]);
    }

    /**
     * 密码登录接口
     * @param Request $request
     * @return JsonResponse
     * @author wangbingang
     */
    public function loginAction(Request $request)
    {
        $mobile = $request->request->get('mobile');
        $password = $request->request->get('password');
        if(!Globals::is_mobile($mobile)) {
            return new JsonResponse(['status'=>'-1', 'info'=> '请填写正确的手机号码']);
        }
        if(!Globals::is_passwd($password)) {
            return new JsonResponse(['status'=>'-2', 'info'=> '用户密码由数字和字母组合长度为6-20位']);
        }
        $memberData = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMember')->findOneBy(['mobile'=>$mobile]);
        if(!$memberData) {
            return new JsonResponse(['status'=>'-3', 'info'=> '用户不存在']);
        }
        if(Globals::password($password, $memberData->getEncrypt()) != $memberData->getPassword()) {
            return new JsonResponse(['status'=>'-4', 'info'=> '密码错误']);
        }
        //更新最后登陆时间
        $memberData->setLastLoginTime(time());
        $em = $this->getDoctrine()->getManager();
        $em->persist($memberData);
        $em->flush();
        //删除旧的access_token
        $this->cache->rm($this->cache->get($mobile));
        $this->cache->rm($this->cache->get($mobile).'Data');
        $str1 = Globals::random(10, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
        $str2 = Globals::random(15, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
        $loginKey = md5($str1.$str2.$mobile);
        $this->cache->set($mobile, $loginKey, 2592000);
        $this->cache->set($loginKey, $memberData->getId(), 2592000);
        $this->cache->set($loginKey.'Data', $memberData, 2592000);
        return new JsonResponse(['status'=>'1', 'info'=> '登录成功', 'access_token'=>$loginKey, 'expires_in'=>2592000]);
    }

    /**
     * 短信验证码登录
     * @param Request $request
     * @return JsonResponse
     */
    public function infoLoginAction(Request $request)
    {
        $mobile = $request->request->get('mobile');
        $code   = $request->request->get('code');
        $scene = $request->request->get('scene', 'infologin');
        if(!Globals::is_mobile($mobile)) {
            return new JsonResponse(['status'=>'-1', 'info'=> '请填写正确的手机号码']);
        }
        if(!$scene ||  ! $this->cache->get($scene.$mobile) || $code != $this->cache->get($scene.$mobile)) {
            return new JsonResponse(['status'=>'-2', 'info'=> '短信验证码不正确']);
        }
        $em = $this->getDoctrine()->getManager();
        $memberData = $em->getRepository('KuakaoAdminBundle:TMember')->findOneBy(['mobile'=>$mobile]);
        if(!$memberData) {
            return new JsonResponse(['status'=>'-3', 'info'=> '用户不存在']);
        }
        //更新最后登陆时间
        $memberData->setLastLoginTime(time());
        $em = $this->getDoctrine()->getManager();
        $em->persist($memberData);
        $em->flush();
        $this->cache->rm($scene.$mobile);
        //删除旧的access_token
        $this->cache->rm($this->cache->get($mobile));
        $this->cache->rm($this->cache->get($mobile).'Data');
        $str1 = Globals::random(10, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
        $str2 = Globals::random(15, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
        $loginKey = md5($str1.$str2);
        $this->cache->set($mobile, $loginKey, 2592000);
        $this->cache->set($loginKey, $memberData->getId(), 2592000);
        $this->cache->set($loginKey.'Data', $memberData, 2592000);
        return new JsonResponse(['status'=>'1', 'info'=> '登录成功', 'access_token'=>$loginKey, 'expires_in'=>2592000]);
    }

    /**
     * 发送验证码
     * @param Request $request
     * @return JsonResponse
     * @author wangbingang
     */
    public function sendInfoAction(Request $request)
    {
        $str = 'S7ZelGT3h4';
        $key  = $request->request->get('key');
        $scene  = $request->request->get('scene');
        $mobile = $request->request->get('mobile');
        if(md5($scene.$mobile.$str) != $key) {
            return new JsonResponse(['status'=>'-0', 'info'=> '访问权限错误']);
        }
        if(!Globals::is_mobile($mobile)) {
            return new JsonResponse(['status'=>'-1', 'info'=> '请填写正确的手机号码']);
        }
        if( $this->cache->get($scene.$mobile.'_time')+60 > time() ) {
            return new JsonResponse(['status'=>'-2', 'info'=> '2次验证码发送时间间隔不得超过60秒']);
        }
        //根据场景检测手机号是否存在
        $res = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMember')->findOneBy(['mobile'=>$mobile]);
        if(in_array($scene, array('infologin', 'restPassword')))
        {
            if(!$res) {
                return new JsonResponse(['status'=>'-3', 'info'=> '手机号不存在']);
            }
        }
        elseif(in_array($scene, array('register')))
        {
            if($res) {
                return new JsonResponse(['status'=>'-4', 'info'=> '手机号已存在']);
            }
        }
        $code = Globals::random(4);
        $infoContent = Globals::Info($code);
        $this->cache->set($scene.$mobile, $code);
        $this->cache->set($scene.$mobile.'_time', time());
        $result = Info::send($mobile, $infoContent);
        //发送日志记录到数据库
        $infoLog = new TInfoLog();
        $infoLog->setMobile($mobile);
        $infoLog->setContent($infoContent);
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


    public function passwordAction(Request $request)
    {

    }

    /**
     * 关于我们页面
     * @param Request $request
     * @return JsonResponse
     * @author yuchuanyan
     */
    public function aboutUsAction()
    {
        return $this->render('KuakaoAppBundle:Default/pc.about.html.twig');
    }


    /**
     * 使用条款页面
     * @param Request $request
     * @return JsonResponse
     * @author yuchuanyan
     */
    public function clauseAction()
    {
        return $this->render('KuakaoAppBundle:Default/clause.html.twig');
    }
    /**
     * 了解更多页面
     * @param Request $request
     * @return JsonResponse
     * @author yuchuanyan
     */
    public function knowAction()
    {
        return $this->render('KuakaoAppBundle:Default/know.html.twig');
    }
    /**
     * 使用说明页面
     * @param Request $request
     * @return JsonResponse
     * @author yuchuanyan
     */
    public function stateAction()
    {
        return $this->render('KuakaoAppBundle:Default/state.html.twig');
    }
    /**
     * 页面地址接口
     * @param Request $request
     * @return JsonResponse
     * @author yuchuanyan
     */
    public function pageAction()
    {
        return new JsonResponse(array('status'=>1, 'info'=>'获取页面完成', 'url1'=>'/clause', 'url2'=>'/know','url3'=>'/state'));
    }


    /**
     * 学校详情页面
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showPageAction(Request $request)
    {
        $id = (int)$request->get('id');
        $type = $request->get('type'); // 1简介，2就业方向, 3分数线
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '专业ID不能为空'));
        }
        $template = 'show.html.twig';
        if($type == 2) {
            $template = 'show2.html.twig';
        }
        if($type == 3) {
            $template = 'show3.html.twig';
        }
        $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool')->find($id);
        $data->setAbout("<p>" .nl2br($data->getAbout())."</p>");
        return $this->render('KuakaoAppBundle:School:'.$template, ['data'=>$data, 'type'=>$type]);
    }


    /**
     * 专业详情页面，显示专业介绍 / 就业方向
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function MajorshowPageAction(Request $request)
    {
        $id = (int)$request->get('id');
        $type = $request->get('type'); // 1专业介绍，2就业方向
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '专业ID不能为空'));
        }
        $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->find($id);
        $data->setZyjs(nl2br($data->getZyjs()));
        return $this->render('KuakaoAppBundle:Major:show.html.twig', ['data'=>$data, 'type'=>$type]);
    }


    /**
     * 资讯详情页面 todo
     * @param Request $request
     * @return JsonResponse
     */
    public function infoshowPageAction(Request $request)
    {
        $infoid = (int)$request->get('id');
        if(!$infoid) {
            return new JsonResponse(array('status' => -1, 'info' => '资讯详情ID不能为空'));
        }
        $categoryInfoData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo')->find($infoid);
        $catid = (int)$request->get('cid');
        if(!$catid){
            return new JsonResponse(array('status' => -1, 'info' => '栏目ID不能为空'));
        }
        $category = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategory')->find($catid);

        #排行榜走特殊模板
        if($catid=='10'){
            return $this->render('KuakaoAppBundle:Category:toplistpage.html.twig',array(
                'categoryinfo'=>$categoryInfoData,
                'category' => $category
            ));
        }

        return $this->render('KuakaoAppBundle:Category:infoshow.html.twig',array(
            'categoryinfo'=>$categoryInfoData,
            'category' => $category
        ));
    }

    /**
     * 专家说详情页面 todo
     * @param Request $request
     * @return JsonResponse
     */
    public function ExpertshowPageAction(Request $request)
    {
        $id = (int)$request->get('id');
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '专家说ID不能为空'));
        }
        $expertData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TExpert')->find($id);
        $name = $expertData->getName();
        $where = array(
//            'name'=>$name,
            'catid'=>6,
            'status'=>1,
        );
        $categoryInfoData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo')->findBy($where,['infoid'=>'desc'],3);
        return $this->render('KuakaoAppBundle:Category:show.html.twig',array(
            'expert'=> $expertData,
            'categoryinfo'=>$categoryInfoData
        ));
    }

    /**
     * 视频播放详情页接口
     * @param Request $request
     * @return JsonResponse
     */
    public function playShowPageAction(Request $request)
    {
        //获取主讲人介绍
        $id = (int)$request->get('id');
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '视频ID不能为空'));
        }
        $videosData = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TVideos')->find($id);
       /* //获取主讲人的文章列表
        $speaker = $videosData->getSpeaker();
        $where = array(
            'name'=>$speaker,
            'catid'=>6,
            'status'=>1
        );
        $categoryInfoData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TCategoryInfo')->findBy($where,['infoid'=>'desc']);*/
        return $this->render('KuakaoAppBundle:Videos:show.html.twig',array(
            'videos'=>$videosData,
        ));

    }

    /**
     * 专题详情页面
     * @param Request $request
     * @return JsonResponse
     */
    public function specialshowPageAction(Request $request)
    {
        $id = (int)$request->get('id');
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '专题ID不能为空'));
        }
        $specialData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSpecial')->find($id);
        return $this->render('KuakaoAppBundle:Special:show.html.twig',array(
            'special'=>$specialData,
        ));
    }


    /**
     * 反馈详情页面 todo
     * @param Request $request
     * @return JsonResponse
     */
    public function feedbackshowPageAction(Request $request)
    {
        $id = (int)$request->get('id');
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '反馈详情ID不能为空'));
        }
        $feedbackData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TFeedback')->find($id);
        //反馈图片
        $pics = array();
        if( !empty($feedbackData->getImg()) ){
            $pics = json_decode($feedbackData->getImg(), true);
        }
        return $this->render('KuakaoAppBundle:Feedback:show.html.twig',array(
            'feedbackData'=>$feedbackData,
            'pics'=>$pics
        ));
    }

}
