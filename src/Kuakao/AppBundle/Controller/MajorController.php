<?php

namespace Kuakao\AppBundle\Controller;

use Kuakao\AdminBundle\Entity\TFollow;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 专业相关接口
 * Class MajorController
 * @package Kuakao\AppBundle\Controller
 * @author wangbingang <bingangwang@kuakao.com>
 */
class MajorController extends BaseController
{
    /**
     * 专业获取接口
     * @param Request $request
     * @return JsonResponse
     */
    public function majorAction(Request $request)
    {
        $schoolId = (int)$request->get('school_id', 0);
        $major_tpye = (int)$request->get('major_type', 3); //1本科，2研究生院，3全部
        $keywords = $request->get('keyword'); //搜索关键词
        $zylx = $request->get('zylx'); //专业类型 [学术硕士 or 专业硕士]
        $xkml = $request->get('xkml'); //学科门类名称
        $yjxk = $request->get('yjxk'); //一级学科名称
        $mathlx = $request->get('math'); //数学考试类型
        $englishlx = $request->get('english'); //英语考试类型
        $sortfield = $request->get('sortfield', 'id'); //排序字段
        $sortType = $request->get('sorttype', 'asc'); //排序类型 asc desc
        $page =  (int)$request->get('page', 0); //当前页数
        $pagesize =  (int)$request->get('pagesize', 20); //一页显示多少条
        $marjorIds = array();

        //本科加研究生
        if($major_tpye == 3){
            $major_tpye = [1,2];
        }else{
            $major_tpye = [$major_tpye];
        }

        if ($schoolId) {
            //根据学校id查询专业
            $data = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMajorschool')->findBy(array('schoolid' => $schoolId));

            foreach ($data as $val) {
                $marjorIds[] = $val->getMajorId();
            }
        }
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
        $query = $repository->createQueryBuilder('m')
            ->select('m.id,m.name,m.pronum,m.gzrs')
            ->andWhere('m.schoolLevel in (:school_type)')
            ->setParameter('school_type', $major_tpye);
        if ($marjorIds) {
            $query->andWhere('m.id IN (:marjorIds)')
                ->setParameter('marjorIds', $marjorIds);
        }
        if ($xkml) {
            $query->andWhere('m.xkml = :xkml')
                ->setParameter('xkml', $xkml);
        }
        if ($yjxk) {
            $query->andWhere('m.yjxk = :yjxk')
                ->setParameter('yjxk', $yjxk);
        }
        if ($mathlx) {
            $query->andWhere('m.mathlx LIKE :mathlx')
                ->setParameter('mathlx', '%'.$mathlx.'%');
        }
        if ($englishlx) {
            $query->andWhere('m.englishlx LIKE :englishlx')
                ->setParameter('englishlx','%'.$englishlx.'%');
        }
        if ($zylx) {
            $query->andWhere('m.zylx = :zylx')
                ->setParameter('zylx', $zylx);
        }
        if($keywords) {
            $query->andWhere('m.name LIKE :name')
                ->setParameter('name', '%'.$keywords.'%');
        }
        if($page) {
            $query->setFirstResult($page*$pagesize);
        }
        if($pagesize) {
            $query->setMaxResults($pagesize);
        }
        $query = $query->orderBy('m.'.$sortfield, $sortType)->getQuery();
        $majorData = $query->getArrayResult();
        $majorData = $this->isFollow($majorData, 2);
        $uid = $this->userid;
//        $uid = 0;
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TZyd');
        $query = $repository->createQueryBuilder('z')
            ->andWhere("z.uid = :uid")
            ->setParameter('uid', $uid)
            ->getQuery();
        $zydDate = $query->getArrayResult();
        $newMajorlData = [];
        foreach($majorData as $k=>$v){
            $v['zyd'] = 0;
            foreach($zydDate as $key=>$val){
                 if(in_array($v['id'],$val)){
                     $v['zyd'] = 1;
                 }
            }
            $newMajorlData[$k] = $v;
        }
        //echo $query->getDQL();
        if ($majorData) {
            return new JsonResponse(array('status' => 1, 'info' => '读取成功', 'data' => $newMajorlData));
        }
        return new JsonResponse(array('status' => -1, 'info' => '暂无数据'));
    }

    /**
     * 获取门类
     * @return JsonResponse
     * @author wangbingang <bingangwang@kuakao.com>
     * 增加学校id参数 过滤门类
     */
    public function menleiAction(Request $request)
    {
        $schoolId = $request->get('sid');
        if(!$schoolId){
            //所有门类
            $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('xkml');
            return new JsonResponse(array('status' => 1, 'info' => '读取成功', 'data' => $data));
        }
        //学校有的门类
            $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool');
            $query = $repository->createQueryBuilder('m')
                ->select('m.id,m.majorid')
                ->andWhere('m.schoolid = :schoolid')
                ->setParameter('schoolid', $schoolId)
                ->getQuery();
            $majoriddata = $query->getArrayResult();
            $newData = [];
            foreach($majoriddata as $k=>$v){
                $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
                $query = $repository->createQueryBuilder('m')
                    ->select('m.id,m.xkml')
                    ->andWhere('m.id = :id')
                    ->setParameter('id', $v['majorid'])
                    ->getQuery();
                $majordata = $query->getArrayResult();
                $newData[] = $majordata[0]['xkml'];
                $newData = array_unique($newData);
                $newData = array_values($newData);
        }
        return new JsonResponse(array('status' => 1, 'info' => '读取成功', 'data' => $newData));
    }

    /**
     * 获取考试类型
     * @return JsonResponse
     */
    public function kstypeAction(Request $request)
    {
        $data1 = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('mathlx');
        $data2 = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('englishlx');
        $data = array(
            'math' => $data1,
            'english' => $data2,
        );
        return new JsonResponse(array('status' => 1, 'info' => '读取成功', 'data' => $data));
    }

    /**
     * 获取一级学科
     * @param Request $request
     * @return JsonResponse
     */
    public function yjxkAction(Request $request)
    {
        $xkml = $request->get('xkml'); //学科门类名称
        $type = $request->get('type'); //办学层次
        $schoolId = $request->get('sid');


        if(!$schoolId){
            //所有一级学科
            if(!$type){
                return new JsonResponse(array('status' => -1, 'info' => '请检查type参数'));
            }
            $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TYjxk');
            $query = $repository->createQueryBuilder('m')
                ->select('m.yjname as name,m.yjnum as num,m.type')
                ->andWhere('m.type = :type')
                ->setParameter('type', $type);
            if($xkml) {
                $query->andWhere('m.mlname = :xkml')
                    ->setParameter('xkml', $xkml);
            }
            $query = $query->orderBy('m.id', 'asc')->getQuery();
            $data = $query->getArrayResult();
            return new JsonResponse(array('status' => 1, 'info' => '读取成功', 'data' => $data));
        }



        //该学校有的一级学科
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool');
        $query = $repository->createQueryBuilder('m')
            ->select('m.id,m.majorid')
            ->andWhere('m.schoolid = :schoolid')
            ->setParameter('schoolid', $schoolId)
            ->getQuery();
        $majoridata = $query->getArrayResult();
        $newData = [];
        foreach($majoridata as $k=>$v){
            $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
            $query = $repository->createQueryBuilder('m')
                ->select('m.id,m.yjxk')
                ->andWhere('m.id = :id')
                ->setParameter('id', $v['majorid'])
                ->getQuery();
            $yjxkdata = $query->getArrayResult();
            $newData[] = $yjxkdata[0]['yjxk'];
            $newData = array_unique($newData);

        }
        //该学校有的一级学科详情
        $newData2 = [];
        foreach($newData as $K=>$v){
            if(!$type){
                return new JsonResponse(array('status' => -1, 'info' => '请检查type参数'));
            }
            $repository2 = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TYjxk');
            $query2 = $repository2->createQueryBuilder('m')
                ->select('m.yjname as name,m.yjnum as num,m.type')
                ->andWhere('m.type = :type')
                ->setParameter('type', $type)
                ->andWhere('m.yjname = :yjname')
                ->setParameter('yjname', $v);
            if($xkml) {
                $query2->andWhere('m.mlname = :xkml')
                    ->setParameter('xkml', $xkml);
            }
            $query2 = $query2->orderBy('m.id', 'asc')->getQuery();
            $data2 = $query2->getArrayResult();
//            var_dump($data2);die();
            if(!empty($data2)){
                $newData2[] = $data2[0];
            }
        }
        if(empty($newData2)){
            return new JsonResponse(array('status' => -2, 'info' => '请检查type参数和sid参数!'));
        }
        return new JsonResponse(array('status' => 1, 'info' => '读取成功', 'data' => $newData2));
    }

    /**
     * 获取专业详情
     * @param Request $request
     * @return JsonResponse
     */
    public function showAction(Request $request)
    {
        $id = (int)$request->get('id');
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '专业ID不能为空'));
        }
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
        $query = $repository->createQueryBuilder('m')
            ->select('m.id,m.name,m.pronum,m.xkml,m.yjxk,m.zylx,m.gzrs')
            ->andWhere('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        $data = $query->getArrayResult();
        $result = array(
            'data' => $data,
            'url1' => '/majorshowpage?id='.$id.'&type=1',  //专业介绍链接
            'url2' => '/majorshowpage?id='.$id.'&type=2', //就业方向链接
        );
        return new JsonResponse(array('status' => 1, 'info' => '读取成功', 'data' => $result));
    }

    /**
     * 已移动default 控制器
     * 专业详情页面，显示专业介绍 / 就业方向
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    /*public function showPageAction(Request $request)
    {
        $id = (int)$request->get('id');
        $type = $request->get('type'); // 1专业介绍，2就业方向
        if(!$id) {
            return new JsonResponse(array('status' => -1, 'info' => '专业ID不能为空'));
        }
        $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->find($id);
        return $this->render('KuakaoAppBundle:Major:show.html.twig', ['data'=>$data, 'type'=>$type]);
    }*/

    /**
     * 关注专业
     * @param Request $request
     * @return JsonResponse
     */
    public function followAction(Request $request)
    {
        $id = (int)$request->get('id'); //专业ID或者学校ID
        $type = (int)$request->get('type'); //关注类型，1学校，2专业
        if(!$id || !$type) {
            return new JsonResponse(array('status' => 0, 'info' => '参数错误,请传入ID和关注类型'));
        }
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository('KuakaoAdminBundle:TFollow')->findOneBy(['uid'=>$this->userid, 'fid'=>$id, 'type'=>$type]);
        if($res) {
            return new JsonResponse(array('status' => -1, 'info' => '已经关注'));
        }
        if($type==1) {
            //增加学校关注人数
            $schoolModel = $em->getRepository('KuakaoAdminBundle:TSchool')->find($id);
            $schoolModel->setGzrs(($schoolModel->getGzrs()+1));
        } elseif($type==2) {
            //增加专业关注人数
            $majorModel = $em->getRepository('KuakaoAdminBundle:TMajor')->find($id);
            $majorModel->setGzrs(($majorModel->getGzrs()+1));
        }
        $follow = new TFollow();
        $follow->setFid($id);
        $follow->setUid($this->userid);
        $follow->setType($type);
        $em->persist($follow);
        $em->flush();
        return new JsonResponse(array('status' => 1, 'info' => '关注成功'));
    }

    /**
     * 取消关注专业或者学校
     * @param Request $request
     * @return JsonResponse
     */
    public function cancelFollowAction(Request $request)
    {
        $id = (int)$request->get('id'); //专业ID或者学校ID
        $type = (int)$request->get('type'); //关注类型，1学校，2专业
        if(!$id || !$type) {
            return new JsonResponse(array('status' => 0, 'info' => '参数错误,请传入ID和关注类型'));
        }
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository('KuakaoAdminBundle:TFollow')->findOneBy(['uid'=>$this->userid, 'fid'=>$id, 'type'=>$type]);
        if($res) {
            $em->remove($res);
            $em->flush();
        }
        return new JsonResponse(array('status' => 1, 'info' => '取消关注'));
    }


    /**
     * 获取我的关注(学校或专业)
     * @param Request $request
     * @return JsonResponse
     */
    public function myFollowAction(Request $request)
    {
        $type = (int)$request->get('type'); //关注类型，1学校，2专业
        $uid = $this->userid;
        if(!$type){
            return new JsonResponse(['status'=>-2, 'info'=>'请检查type参数!']);
        }
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TFollow');
        $query = $repository->createQueryBuilder('f')
            ->andWhere('f.type = :type')
            ->setParameter('type', $type)
            ->andWhere('f.uid = :uid')
            ->setParameter('uid', $uid)
            ->getQuery();
        $data2 = $query->getArrayResult();
        $schoolids = [];
        $majorids = [];
        $data = [];
        foreach($data2 as $k=>$v){
            if($v['type'] == 1){
                $schoolids[] = $v['fid'];
            }elseif($v['type'] == 2){
                $majorids[] = $v['fid'];
            }
        }

        if($type == 1){
            if($schoolids){
                $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
                $query = $repository->createQueryBuilder('s')
                    ->select('s.id,s.name,s.logo,s.province,s.is211,s.is985,s.isZhx,s.isYjs,s.gzrs')
                    ->andWhere('s.id IN (:schoolids)')
                    ->setParameter('schoolids', $schoolids)
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
            }
        }
        if($type == 2){
            if($majorids){
                $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
                $query = $repository->createQueryBuilder('m')
                    ->select('m.id,m.name,m.pronum,m.gzrs')
                    ->andWhere('m.id IN (:majorids)')
                    ->setParameter('majorids', $majorids)
                    ->getQuery();
                $data = $query->getArrayResult();
            }
        }
        if($data){
            return new JsonResponse(['status'=>1, 'info'=>'读取成功', 'type'=>'data', 'data'=>$data]);
        }
        return new JsonResponse(['status'=>-1, 'info'=>'暂无数据']);
    }


    /**
     * 获取所有学科门类下一级学科
     * @param Request $request
     * @return JsonResponse
     */
    public function getXkmlYjxkAction(Request $request)
    {
        $cacheData = $this->cache->get('MajorController_getXkmlYjxk');
        if($cacheData) {
            return new JsonResponse(['status'=>1, 'info'=>'读取成功', 'type'=>'cache', 'data'=>$cacheData]);
        }
        $XkmlData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('xkml');
        $newXkmlData = [];
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TYjxk');
        foreach($XkmlData as $k => $v){
            $query = $repository->createQueryBuilder('y')
                ->select('y.id,y.yjname,y.yjnum')
                ->andWhere('y.mlname = :mlname')
                ->setParameter('mlname', $v)
                ->andWhere('y.type = :type')
                ->setParameter('type', 2)
                ->getQuery();
            $data = $query->getArrayResult();
            $newXkmlData[$k][$v]= $data;
        }
//        var_dump($newXkmlData);
        $this->cache->set('MajorController_getXkmlYjxk', $newXkmlData);
        return new JsonResponse(['status'=>2, 'info'=>'读取成功', 'type'=>'data', 'data'=>$newXkmlData]);
    }


    /**
     * 获取所有学科门类下一级学科二级学科
     * 过滤本科专业
     * @param Request $request
     * @return JsonResponse
     */
    public function getXkmlYjxkMajorAction(Request $request)
    {
        $cacheData = $this->cache->get('MajorController_getXkmlYjxkMajor');
        if($cacheData) {
            return new JsonResponse(['status'=>1, 'info'=>'读取成功', 'type'=>'cache', 'data'=>$cacheData]);
        }
        $XkmlData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('xkml');
        $newXkmlData = [];
        $repositoryy = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TYjxk');
        $repositorym = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
        foreach($XkmlData as $k => $v){
            $query = $repositoryy->createQueryBuilder('y')
                ->select('y.id,y.yjname,y.yjnum')
                ->andWhere('y.mlname = :mlname')
                ->setParameter('mlname', $v)
                ->andWhere('y.type = :type')
                ->setParameter('type', 2)
                ->getQuery();
            $YjxkData = $query->getArrayResult();
            $newYjxkData = [];
            foreach($YjxkData as $key => $val){
                $query = $repositorym->createQueryBuilder('m')
                    ->select('m.id,m.name,m.pronum')
                    ->andWhere('m.schoolLevel = :schoolLevel')
                    ->setParameter('schoolLevel', 2)
                    ->andWhere('m.xkml = :xkml')
                    ->setParameter('xkml', $v)
                    ->andWhere('m.yjxk = :yjxk')
                    ->setParameter('yjxk', $val['yjname'])
                    ->getQuery();
                $data = $query->getArrayResult();
                $newYjxkData[$key] = $val;
                $newYjxkData[$key]['major']= $data;
            }
            $newXkmlData[$k][$v]= $newYjxkData;
        }
        $this->cache->set('MajorController_getXkmlYjxkMajor', $newXkmlData);
        return new JsonResponse(['status'=>2, 'info'=>'读取成功', 'type'=>'data', 'data'=>$newXkmlData]);
    }


}