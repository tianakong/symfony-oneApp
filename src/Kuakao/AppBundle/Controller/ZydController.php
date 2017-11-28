<?php

namespace Kuakao\AppBundle\Controller;

use Kuakao\AdminBundle\Entity\TZyd;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 志愿单
 * Class ZydController
 * @package Kuakao\AppBundle\Controller
 * @author wangbingang <bingangwang@kuakao.com>
 */
class ZydController extends BaseController
{
    /**
     * 获取志愿单
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction()
    {
        $zydData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TZyd')->findBy(['uid'=>$this->userid]);
        $newData = array();
        $majorData = array();
        $majorIds = array();
        foreach($zydData as $val) {
            if($val->getSchoolid() != 0){
                $schoolData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool')->findOneBy(['id'=>$val->getSchoolid()]);
                if(!$schoolData) {
                    return new JsonResponse(['status'=>-1, 'info'=>'操作错误,该学校不存在,学校ID：'.$val->getSchoolid()]);
                }
                $newData[$val->getMajorid()][$val->getSchoolid()]['name'] = $schoolData->getName();
                $newData[$val->getMajorid()][$val->getSchoolid()]['logo'] = $schoolData->getLogo();
            }else{
                $newData[$val->getMajorid()]['-1'] = -1;//志愿单里只有专业 没有学校
            }
            $majorIds[$val->getMajorid()] = $val->getMajorid();
        }
        foreach($majorIds as $val) {
            $majorResult = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->findOneBy(['id'=>$val]);
            if(!$majorResult) {
                return new JsonResponse(['status'=>-2, 'info'=>'操作错误,该专业不存在,专业ID：'.$val]);
            }
            $majorData[$val] = $majorResult->getName();
        }
        $resultArray = array();
        foreach($newData as $key=>$val) {
            $resultArray[$key]['name'] = $majorData[$key];
            $resultArray[$key]['school'] = $val;
        }
        return new JsonResponse(['status'=>1, 'info'=>'读取成功', 'data'=>$resultArray]);
    }

    /**
     * 添加志愿单
     * todo 验证学校专业的有效性
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request)
    {
        $datas = $request->get('data'); //约定好的数据格式
        if(!$datas) {
            return new JsonResponse(['status'=>0, 'info'=>'参数错误,请传入数据']);
        }
        $datas = json_decode($datas, true);  //json转换成数组

        $em = $this->getDoctrine()->getManager();
        foreach($datas as $majorId=>$val) {
            foreach($val as $schoolId) {
                //判断如果改值存在就不添加
                $res = $em->getRepository('KuakaoAdminBundle:TZyd')->findOneBy(['uid'=>$this->userid, 'majorid'=>$majorId, 'schoolid'=>$schoolId]);
                if($res) {
                    continue;
                }
                $zydModel = new TZyd();
                $zydModel->setMajorid($majorId);
                $zydModel->setSchoolid($schoolId);
                $zydModel->setUid($this->userid);
                $em->persist($zydModel);
                $em->flush();
            }
        }
        return new JsonResponse(['status'=>1, 'info'=>'操作成功']);
    }

    /**
     * 删除志愿单
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $schoolId = $request->get('school_id'); //学校id
        $majorId = $request->get('major_id'); //专业ID，同时删除下面的学校
        if(!$majorId) {
            return new JsonResponse(['status'=>-1, 'info'=>'参数错误,请传入专业ID']);
        }
        if(!$majorId && !$schoolId) {
            return new JsonResponse(['status'=>-2, 'info'=>'参数错误,请传入学校ID']);
        }
        $em = $this->getDoctrine()->getManager();
        if($majorId && !$schoolId) {
            //删除该专业下的所有学校
            $zydData = $em->getRepository('KuakaoAdminBundle:TZyd')->findBy(['uid'=>$this->userid, 'majorid'=>$majorId]);
            foreach($zydData  as $val) {
                $em->remove($val);
                $em->flush();
            }
        } else {
            //删除 某个专业下的某个学校
            $zydData = $em->getRepository('KuakaoAdminBundle:TZyd')->findOneBy(['uid'=>$this->userid, 'majorid'=>$majorId, 'schoolid'=>$schoolId]);

            $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TZyd');
            $query = $repository->createQueryBuilder('z')
                ->andWhere("z.uid = :uid")
                ->setParameter('uid', $this->userid)
                ->andWhere("z.majorid = :majorid")
                ->setParameter('majorid', $majorId)
                ->getQuery();
            $zydDateAll = $query->getResult();
//            var_dump($zydDateAll);
            if(count($zydDateAll) > 1)
            {
                $em->remove($zydData);
                $em->flush();
            }
            elseif(count($zydDateAll) == 1)
            {
                $zydData->setSchoolid(0);
                $em->persist($zydData);
                $em->flush();
            }
        }
        return new JsonResponse(['status'=>1, 'info'=>'删除成功']);
    }
}

