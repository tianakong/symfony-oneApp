<?php

namespace Kuakao\AppBundle\Controller;

use Kuakao\AdminBundle\Entity\TFollow;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * 学校相关接口
 * Class SchoolController
 * @package Kuakao\AppBundle\Controller
 * @author wangbingang <bingangwang@kuakao.com>
 */
class SchoolController extends BaseController
{
    /**
     * 获取院校接口
     * @param Request $request
     * @return JsonResponse
     * @author wangbingang <bingangwang@kuakao.com>
     */
    public function schoolAction(Request $request)
    {
        $major_id = (int)$request->get('major_id', 0);
        $school_tpye = (int)$request->get('school_type', 2); //1本科，2研究生院，3本科加研究生,4所有本科,5所有研究生院
        $area = (int)$request->get('area'); //地区id
        $menlei = $request->get('xkml');
        $keywords = $request->get('keyword'); //搜索关键词
        $is211 = $request->get('is211');
        $is985 = $request->get('is985');
        $isZhx = $request->get('iszhx');
        $isYjs = $request->get('isyjs');
        $sortfield = $request->get('sortfield', 'id'); //排序字段
        $sortType = $request->get('sorttype', 'asc'); //排序类型 asc desc
        $page =  (int)$request->get('page', 0); //当前页数
        $pagesize =  (int)$request->get('pagesize', 20); //一页显示多少条


        $schoolIds = array();
        if($major_id)
        {
            //根据专业id查询学校
            $data = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMajorschool')->findBy(array('majorid'=>$major_id));
            foreach($data as $val) {
                $schoolIds[] = $val->getSchoolid();
            }
        }else{
            //通过门类查询学校
            if($menlei) {
                $majorIds = array();
                # 获取某一门类下的专业
                $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
                $query = $repository->createQueryBuilder('m')
                    ->select('m.id')
                    ->andWhere("m.xkml=:xkml")
                    ->setParameter('xkml', $menlei)
                    ->getQuery();
                $data = $query->getArrayResult();
                foreach($data as $val) {
                    $majorIds[] = $val['id'];
                }
                # 通过专业id，查学校
                if($majorIds) {
                    $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool');
                    $query = $repository->createQueryBuilder('s')
                        ->select('s.schoolid')
                        ->groupby('s.schoolid')
                        ->andWhere('s.majorid IN (:majorIds)')
                        ->setParameter('majorIds', $majorIds)
                        ->getQuery();
                    $data = $query->getArrayResult();
                    if($data) {
                        foreach($data as $val) {
                            $schoolIds[$val['schoolid']] = $val['schoolid'];
                        }
                    }
                }
            }
        }

        //本科
        if($school_tpye == 4){
            $school_tpye = [1,3];
        }
        //研究生
        elseif($school_tpye == 5){
            $school_tpye = [2,3];
        }else{
            $school_tpye = [$school_tpye];
        }
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
        $query = $repository->createQueryBuilder('s')
                ->select('s.id,s.name,s.logo,s.province,s.is211,s.is985,s.isZhx,s.isYjs,s.gzrs')
                ->andWhere('s.schoolLevel In (:school_type)')
                ->setParameter('school_type', $school_tpye);
        if($schoolIds) {
            $query->andWhere('s.id IN (:schoolIds)')
                ->setParameter('schoolIds', $schoolIds);
        }
        if($area) {
            $query->andWhere('s.province = :province')
                ->setParameter('province', $area);
        }
        if($is211) {
            $query->andWhere('s.is211 = :is211')
                ->setParameter('is211', $is211);
        }
        if($is985) {
            $query->andWhere('s.is985 = :is985')
                ->setParameter('is985', $is985);
        }
        if($isZhx) {
            $query->andWhere('s.isZhx = :isZhx')
                ->setParameter('isZhx', $isZhx);
        }
        if($isYjs) {
            $query->andWhere('s.isYjs = :isYjs')
                ->setParameter('isYjs', $isYjs);
        }
        if($keywords) {
            $query->andWhere('s.name LIKE :name')
                ->setParameter('name', '%'.$keywords.'%');
        }
        if($page) {
            $query->setFirstResult($page*$pagesize);
        }
        if($pagesize) {
            $query->setMaxResults($pagesize);
        }
        $query = $query->orderBy('s.'.$sortfield, $sortType)->getQuery();
        $schoolData = $query->getArrayResult();

        if($schoolData) {
            //查询地区
            $area = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea')->getData();
            foreach ($schoolData as &$val) {
                $val['areaId'] = $val['province'];
                $val['areaName'] = isset($area[$val['province']]) ? $area[$val['province']]->getName() : '';
                unset($val['province']);
            }
        }
        $schoolData = $this->isFollow($schoolData, 1);
        $uid = $this->userid;
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TZyd');
        $query = $repository->createQueryBuilder('z')
            ->andWhere("z.uid = :uid")
            ->setParameter('uid', $uid)
            ->getQuery();
        $zydDate = $query->getArrayResult();
        $newSchoolData = [];
        foreach($schoolData as $k=>$v){
            $v['zyd'] = 0;
            foreach($zydDate as $key=>$value){
                if(in_array($v['id'],$value)){
                    $v['zyd'] = 1;
                }
            }
            $newSchoolData[$k] = $v;
        }
        if($schoolData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$newSchoolData));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));
    }

    /**
     * 地区获取接口
     * 1.通过专业获取地区
     * 2.通过门类获取地区
     * 用专业id或者门类查数据，需要先去关联表表中查出专业id，再去关联表查出学校id,在学校表中获取地区id
     * @return JsonResponse
     */
    public function areaAction(Request $request)
    {
        $majorid = (int)$request->get('major_id'); //专业id
        $menlei = htmlspecialchars($request->get('xkml')); //门类名称
        $cacheRest = (int)$request->get('cache'); //1就重设缓存
        $cacheKey = 'app_school_area'.$majorid.$menlei;
        $cacheData = $this->cache->get($cacheKey);
        if($cacheData && !$cacheRest) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$cacheData, 'type'=>'cache'));
        }
        $schoolIds = array();
        $areaIds = array();
        //通过专业查询地区
        if($majorid) {
            $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool')->findBy(['majorid'=>$majorid]);
            foreach($data as $val) {
                if($val->getSchoolid()) {
                    $schoolIds[$val->getSchoolid()] = $val->getSchoolid();
                }
            }
            if($schoolIds) {
                $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
                $query = $repository->createQueryBuilder('s')
                    ->select('s.id,s.province')
                    ->andWhere('s.id IN (:schoolIds)')
                    ->setParameter('schoolIds', $schoolIds)
                    ->getQuery();
                $data = $query->getArrayResult();
                foreach($data as $val) {
                    $areaIds[$val['province']] = $val['province'];
                }
            }
        }
        //通过门类查询地区
        if($menlei) {
            $schoolIds = array();
            $majorIds = array();
            $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->findBy(['xkml'=>$menlei]);
            foreach($data as $val) {
                if($val->getId()) {
                    $majorIds[$val->getId()] = $val->getId();
                }
            }
            if($majorIds) {
                $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool');
                $query = $repository->createQueryBuilder('s')
                    ->select('s.schoolid')
                    ->andWhere('s.majorid IN (:majorIds)')
                    ->setParameter('majorIds', $majorIds)
                    ->getQuery();
                $data = $query->getArrayResult();
                if($data) {
                    foreach($data as $val) {
                        $schoolIds[$val['schoolid']] = $val['schoolid'];
                    }
                }
            }
            if($schoolIds) {
                $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
                $query = $repository->createQueryBuilder('s')
                    ->select('s.id,s.province')
                    ->andWhere('s.id IN (:schoolIds)')
                    ->setParameter('schoolIds', $schoolIds)
                    ->getQuery();
                $data = $query->getArrayResult();
                foreach($data as $val) {
                    $areaIds[$val['province']] = $val['province'];
                }
            }
        }

        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea');
        $query = $repository->createQueryBuilder('a')
            ->select('a.id,a.name,a.zone')
            ->orderBy('a.listorder', 'desc');
        if($areaIds) {
            $query->andWhere('a.id IN (:areaIds)')
            ->setParameter('areaIds', $areaIds);
        }
        $query = $query->getQuery();
        $areaData = $query->getArrayResult();
        $this->cache->set($cacheKey, $areaData);
        if($areaData) {
            return new JsonResponse(array('status'=>1, 'info'=>'读取成功', 'data'=>$areaData));
        }
        return new JsonResponse(array('status'=>-1, 'info'=>'暂无数据'));
    }

    /**
     * 获取考研年份
     * @param Request $request
     * @return JsonResponse
     */
    public function yearAction(Request $request)
    {
        $array = array(
            '2017',
            '2018',
            '2019',
            '2020',
            '2021'
        );
        return new JsonResponse(array('status'=>1, 'info'=>'获取成功', 'data'=>$array));
    }

    /**
     * 获取最高学历
     * @return JsonResponse
     */
    public function educationAction()
    {
        $array = array(
            '应届毕业生',
            '同等学力',
            '在职考研',
            '强军计划',
            '享受少数民族政策',
        );
        return new JsonResponse(array('status'=>1, 'info'=>'获取成功', 'data'=>$array));
    }

    /**
     * 获取数学水平
     * @return JsonResponse
     */
    public function sxspAction()
    {
        $array = array(
            [1=>'班级/年级前20%'],
            [2=>'其他'],
            [3=>'班级/年级后20%']
        );
        return new JsonResponse(array('status'=>1, 'info'=>'获取成功', 'data'=>$array));
    }

    /**
     * 获取英语成绩
     * @return JsonResponse
     */
    public function yycjAction()
    {
        $array = array(
            [1=>'六级550分以上'],
            [2=>'六级550分以下'],
            [3=>'四级550分以上'],
            [4=>'四级550分以下']
        );
        return new JsonResponse(array('status'=>1, 'info'=>'获取成功', 'data'=>$array));
    }

    /**
     * 获取学校类型
     * @return JsonResponse
     */
    public function typeAction()
    {
        $array = array(
            '211',
            '985',
            '自划线',
            '研究生院'
        );
        return new JsonResponse(array('status'=>1, 'info'=>'获取成功', 'data'=>$array));
    }

    /**
     * 获取学校详情
     * @param Request $request
     * @return JsonResponse
     */
    public function showAction(Request $request)
    {
        $id = (int)$request->get('id');
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '学校ID不能为空'));
        }
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
        $query = $repository->createQueryBuilder('s')
            ->select('s.id,s.name,s.logo,s.province,s.is211,s.is985,s.isZhx,s.isYjs,s.gzrs,s.pic,s.rank')
            ->andWhere('s.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        $data = $query->getArrayResult();
        if($data) {
            //查询地区
            $area = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea')->getData();
            foreach ($data as &$val) {
                $val['areaId'] = $val['province'];
                $val['areaName'] = isset($area[$val['province']]) ? $area[$val['province']]->getName() : '';
                unset($val['province']);
            }
        }
        $result = array(
            'data' => $data,
            'url1' => '/schoolshowpage?id='.$id.'&type=1',  //学校介绍链接
            'url2' => '/schoolshowpage?id='.$id.'&type=2', //就业方向链接
            'url3' => '/schoolshowpage?id='.$id.'&type=3', //分数线链接
        );
        return new JsonResponse(array('status' => 1, 'info' => '读取成功', 'data' => $result));
    }

    /**
     * 已移动到Default控制器
     * 学校详情页面
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
   /* public function showPageAction(Request $request)
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
        return $this->render('KuakaoAppBundle:School:'.$template, ['data'=>$data, 'type'=>$type]);
    }*/


    /**
     * 获取所有地区下的院校
     * @return JsonResponse
     */
    public function getAreaSchoolAction()
    {
        $cacheData = $this->cache->get('SchoolController_getAreaSchool');
        if($cacheData) {
            return new JsonResponse(['status'=>1, 'info'=>'读取成功', 'type'=>'cache', 'data'=>$cacheData]);
        }
        $areaData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea')->findAll();
        $newAreaData = array();
        $school_level = [2,3];
        foreach($areaData as $val) {
            $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
            $query = $repository->createQueryBuilder('s')
                ->select('s.id,s.name')
                ->andWhere('s.province = :province')
                ->setParameter('province', $val->getId())
                ->andWhere('s.schoolLevel IN (:schoolLevel)')
                ->setParameter('schoolLevel', $school_level)
                ->getQuery();
            $data = $query->getArrayResult();
            if(empty($data)){
                continue;
            }
            $newAreaData[$val->getId()] = array(
                'name' => $val->getName(),
                'type' => $val->getZone(),
                'school' => $data,
            );
        }
        $this->cache->set('SchoolController_getAreaSchool', $newAreaData);
        return new JsonResponse(['status'=>1, 'info'=>'读取成功', 'type'=>'data', 'data'=>$newAreaData]);
    }



    /**
     * 获取所有地区(分一二区)
     * @return JsonResponse
     */
    public function getAreaZoneAction()
    {
        $cacheData = $this->cache->get('SchoolController_getAreaZone');
        if($cacheData) {
            return new JsonResponse(['status'=>1, 'info'=>'读取成功', 'type'=>'cache', 'data'=>$cacheData]);
        }
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea');
        $query = $repository->createQueryBuilder('a')
            ->select('a.id,a.name')
            ->andWhere('a.zone = :zone')
            ->setParameter('zone', 1)
            ->getQuery();
        $oneData = $query->getArrayResult();
        $query = $repository->createQueryBuilder('a')
            ->select('a.id,a.name')
            ->andWhere('a.zone = :zone')
            ->setParameter('zone', 2)
            ->getQuery();
        $twoData = $query->getArrayResult();
         $newAreaData['one'] = $oneData;
         $newAreaData['two'] = $twoData;

        $this->cache->set('SchoolController_getAreaZone', $newAreaData);
        return new JsonResponse(['status'=>1, 'info'=>'读取成功', 'type'=>'data', 'data'=>$newAreaData]);
    }
}