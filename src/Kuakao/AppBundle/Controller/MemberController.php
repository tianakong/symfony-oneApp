<?php

namespace Kuakao\AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Kuakao\Common\Globals;
use Symfony\Component\Filesystem\Filesystem;

/**
 * 会员中心相关接口 todo
 * Class MemberController
 * @package Kuakao\AppBundle\Controller
 * @author wangbingang <bingangwang@kuakao.com>
 */
class MemberController extends BaseController
{
    /**
     * 重置密码接口第一步，验证手机号
     * @param Request $request
     * @return JsonResponse
     */
    public function restPasswd1Action(Request $request)
    {
        $mobile = $request->request->get('mobile');
        $scene = $request->request->get('scene', 'restPassword');
        $code   = $request->request->get('code');
        if(!Globals::is_mobile($mobile)) {
            return new JsonResponse(['status'=>'-1', 'info'=> '请填写正确的手机号码']);
        }
        if(!$scene ||  ! $this->cache->get($scene.$mobile) || $code != $this->cache->get($scene.$mobile)) {
            return new JsonResponse(['status'=>'-2', 'info'=> '短信验证码不正确']);
        }
        $this->cache->set($mobile.'_restPasswd', 1);
        $this->cache->rm($scene.$mobile);
        return new JsonResponse(['status'=>'1', 'info'=> '认证成功']);
    }

    /**
     * 重置密码第二步，修改密码
     * @param Request $request
     * @return JsonResponse
     */
    public function restPasswd2Action(Request $request)
    {
        $mobile = $request->request->get('mobile');
        $passwd = $request->request->get('passwd');
        $repPasswd = $request->request->get('rep_passwd');
        if(!Globals::is_mobile($mobile)) {
            return new JsonResponse(['status'=>'-1', 'info'=> '访问出错,手机号码错误']);
        }
        if(!Globals::is_passwd($passwd)) {
            return new JsonResponse(['status'=>'-2', 'info'=> '用户密码由数字和字母组合长度为6-20位']);
        }
        if($passwd != $repPasswd) {
            return new JsonResponse(['status'=>'-3', 'info'=> '重复密码不正确']);
        }
        if(!$this->cache->get($mobile.'_restPasswd')) {
            return new JsonResponse(['status'=>'-4', 'info'=>'访问错误,请先认证您的手机号' ]);
        }
        $this->cache->rm($mobile.'_restPasswd');
        $em = $this->getDoctrine()->getManager();
        $memberData = $em->getRepository('KuakaoAdminBundle:TMember')->findOneBy(['mobile'=>$mobile]);
        if(!$memberData) {
            return new JsonResponse(['status'=>'-5', 'info'=>'用户不存在' ]);
        }
        $memberData->setPassword($passwd);
        $em->persist($memberData);
        $em->flush();
        return new JsonResponse(['status'=>'1', 'info'=>'重置密码成功！' ]);
    }

    /**
     * 个人中心编辑接口
     * @param Request $request
     * @return JsonResponse
     */
    public function myinfoAction(Request $request)
    {
        $userid = $this->userid;
//        $userid = 36;

        $img = $request->get('img');
        if(empty($img)){
            return new JsonResponse(['status'=>'-1', 'info'=>'请检查img参数!' ]);
        }
        $name = $request->get('name');
        if(empty($name)){
            return new JsonResponse(['status'=>'-2', 'info'=>'请检查name参数！' ]);
        }
        $sex = $request->get('sex');
        if(empty($sex)){
            return new JsonResponse(['status'=>'-3', 'info'=>'请检查sex参数！' ]);
        }
        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('KuakaoAdminBundle:TMember')->find($userid);
        if(empty($member)){
            return new JsonResponse(['status'=>'-4', 'info'=>'用户ID不存在！' ]);
        }

        $fileName = md5($userid.Globals::random(5).time());
        $fileName = $fileName.'.jpg';
        $file = '/upload/member/'.$fileName;
        file_put_contents($_SERVER['DOCUMENT_ROOT'].$file, $img);
        $member->setImg($file);
        $member->setSex($sex);
        $member->setName($name);
        $em->persist($member);
        $em->flush();
        return new JsonResponse(['status'=>'1', 'info'=>'完成！' ]);
    }

    /**
     * 个人中心接口
     * @param Request $request
     * @return JsonResponse
     */
    public function findmyAction(Request $request)
    {
        $userid = $this->userid;

        $members = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMember');
        $query = $members->createQueryBuilder('m')
            ->andWhere("m.id = $userid")
            ->getQuery();
        $membersData = $query->getArrayResult();
        if(empty($membersData))
        {
            return new JsonResponse(['status'=>'-2', 'info'=>'用户数据为空或用户ID不存在！' ]);
        }
        return new JsonResponse(['status'=>'1', 'info'=>'完成！', 'data'=>$membersData ]);

    }

    /**
     * 考研志愿编辑接口
     * @param Request $request
     * @return JsonResponse
     */
    public function zhiyuanAction(Request $request)
    {
        //参数
        $userid = $this->userid;
       /* $year = $request->get('year');
        $major = $request->get('major');
        $school = $request->get('school');
        $scoreBa = $request->get('scoreBa');
        $targetMajor = $request->get('targetMajor');
        $targetSchool = $request->get('targetSchool');
        $sxcj = $request->get('sxcj');
        $yycj = $request->get('yycj');*/


        //查询操作
        $members = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMember');
        $query = $members->createQueryBuilder('m')
            ->select('m.id,m.year,m.major,m.school,m.targetMajor,m.targetSchool')
            ->andWhere("m.id = ".$userid)
            ->getQuery();
        $membersData2 = $query->getArrayResult();
//        var_dump($membersData2);
        $schoolModel = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TSchool');
        $majorModel = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMajor');
        //本科学校
        $school = $schoolModel->find($membersData2[0]['school']);
//        var_dump($school);
        //本科专业
        $major = $majorModel->find($membersData2[0]['major']);
        //意向学校
        $targetSchool = $schoolModel->find($membersData2[0]['targetSchool']);
        //意向专业
        $targetMajor = $majorModel->find($membersData2[0]['targetMajor']);
        $newmembersData2 = [];
        foreach($membersData2 as $ke=>$v){
            $v['school'] = $school->getName();
            $v['major'] = $major->getName();
            $v['targetSchool'] = $targetSchool->getName();
            $v['targetMajor'] = $targetMajor->getName();
            $newmembersData2[] = $v;
        }
        if(empty($membersData2)){
            return new JsonResponse(array('status'=>'-1', 'info'=>'用户ID不存在！'));
        }
       /* //修改操作
        if($year && $major && $school && $scoreBa && $sxcj && $yycj){
            $membersData =$this->getDoctrine()->getRepository('KuakaoAdminBundle:TMember')->find($userid);
            $em = $this->getDoctrine()->getManager();
            $membersData->setYear($year);
            $membersData->setMajor($major);
            $membersData->setSchool($school);
            if($targetMajor){
                $membersData->setTargetMajor($targetMajor);
            }
            if($targetSchool){
                $membersData->setTargetSchool($targetSchool);
            }
            $membersData->setSxcj($sxcj);
            $membersData->setYycj($yycj);
            $membersData->setScoreBa($scoreBa);
            $em->persist($membersData);
            $em->flush();
        }else{
            return new JsonResponse(array('status'=>'-2', 'info'=>'用户数据为空!'));
        }*/
        return new JsonResponse(array('status'=>'1', 'info'=>'完成！','data'=>$newmembersData2));

    }
}