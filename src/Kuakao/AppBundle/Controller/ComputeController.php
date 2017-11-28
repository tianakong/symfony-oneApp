<?php

namespace Kuakao\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * 选目标结果相关接口 todo
 * Class ComputeController
 * @package Kuakao\AppBundle\Controller
 * @author wangbingang <bingangwang@kuakao.com>
 */
class ComputeController extends BaseController
{

    /**
     * 选目标结果  计算结果 / 推荐专业 / 推荐学校
     * @param Request $request
     * @return JsonResponse
     */
    public function resultAction(Request $request)

   {
        $year = $request->get('year');  //考研年份
        $area = $request->get('area');  //选择暂无意向学校时显示目标省市
        $category = $request->get('category'); //选择暂无意向专业时显示门类
        $schoolY = (int)$request->get('school_y');  //意向学校
        $majorY = (int)$request->get('major_y'); //意向专业
        $education = $request->get('education');  //最高学历
        $schoolB = (int)$request->get('school_b');  //本科学校
        $majorB = (int)$request->get('major_b'); //本科专业
        $maths = (int)$request->get('maths'); //数学成绩
        $english = (int)$request->get('english'); //英语成绩

        $schoolModel = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TSchool');
        $majorModel = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMajor');

        if(!$area && !$schoolY) {
            return new JsonResponse(['status'=>-1, 'info'=>'参数错误,请选择意向学校']);
        }
        if(!$category && !$majorY) {
            return new JsonResponse(['status'=>-2, 'info'=>'参数错误,请选择意向专业']);
        }
        if(!$schoolB) {
            return new JsonResponse(['status'=>-3, 'info'=>'参数错误,请选择本科学校']);
        }
        if(!$majorB) {
            return new JsonResponse(['status'=>-4, 'info'=>'参数错误,请选择本科专业']);
        }

        //本科学校
        $schoolBData = $schoolModel->find($schoolB);
        if(!$schoolBData) {
            return new JsonResponse(['status'=>-7, 'info'=>'参数错误,本科学校不存在']);
        }
        //本科专业
        $majorBData = $majorModel->find($majorB);
        if(!$majorBData) {
            return new JsonResponse(['status'=>-10, 'info'=>'参数错误,本科专业不存在']);
        }

        //推荐专业
        if($category && !$majorY)
        {
            $tMajorSchool = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool');
            //本科专业的门类 和 选择的门类不一样   跨门类跨专业
            if($category != $majorBData->getXkml())
            {
                //太容易
                //v1【不考数学，英语二】 或者 【英语一，不考数学】
                //v2 根据意向学校和科目筛选专业
                $res1 = $tMajorSchool->findBy(['math0'=>1, 'english2'=>'1', 'schoolid'=>$schoolY]);
                if(!$res1) {
                    $res1 = $tMajorSchool->findBy(['math0'=>'1', 'english1'=>'1', 'schoolid'=>$schoolY]);
                }
                $majorData1 = $this->getByMajor($res1, $category);
                //保底
                //【数学二，英语二】 或者 【英语一，数学二】
                $res2 = $tMajorSchool->findBy(['math2'=>'1', 'english2'=>'1', 'schoolid'=>$schoolY]);
                if(!$res2) {
                    $res2 = $tMajorSchool->findBy(['math2'=>'1', 'english1'=>'1', 'schoolid'=>$schoolY]);
                }
                $majorData2 = $this->getByMajor($res2, $category);
                //推荐
                //专业代码必须带 [z]
                $res3 = $tMajorSchool->findBy(['schoolid'=>$schoolY]);
                $majorIds = array();
                //提取专业ID
                foreach($res3 as $val) {
                    $majorIds[] = $val->getMajorid();
                }
                $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
                $query = $repository->createQueryBuilder('m')
                    ->select('m.id,m.name,m.pronum,m.gzrs')
                    ->andWhere('m.xkml = :xkml')
                    ->setParameter('xkml', $category)
                    ->andWhere('m.schoolLevel IN (:school_type)')
                    ->setParameter('school_type', array(2,3))
                    ->andWhere('m.id IN (:marjorIds)')
                    ->setParameter('marjorIds', $majorIds)
                    ->andWhere('m.pronum LIKE :pronum')
                    ->setParameter('pronum', '%Z%')
                    ->setMaxResults(20)
                    ->orderBy('m.id', 'desc')->getQuery();
                $majorData3 = $query->getArrayResult();
                //冲刺
                //【英语二，数学三】 或者【英语一，数学三】
                $res4 = $tMajorSchool->findBy(['math3'=>'1', 'english2'=>'1', 'schoolid'=>$schoolY]);
                if(!$res4) {
                    $res4 = $tMajorSchool->findBy(['math3'=>'1', 'english1'=>'1', 'schoolid'=>$schoolY]);
                }
                $majorData4 = $this->getByMajor($res4, $category);
                //太难
                //【英语二，数学一】 或者 【英语一，数学一】
                $res5 = $tMajorSchool->findBy(['math1'=>'1', 'english2'=>'1', 'schoolid'=>$schoolY]);
                if(!$res5) {
                    $res5 = $tMajorSchool->findBy(['math1'=>'1', 'english1'=>'1', 'schoolid'=>$schoolY]);
                }
                $majorData5 = $this->getByMajor($res5, $category);
                $data = array(
                    'rongyi' => $majorData1,
                    'baodi' => $majorData2,
                    'tuijian' => $majorData3,
                    'chongci' => $majorData4,
                    'tainan' => $majorData5,
                );
                $totalNumber = count($majorData1) + count($majorData2) + count($majorData3) + count($majorData4) + count($majorData5);
            }
            else   //本科专业的门类 和 选择的门类一样 本专业
            {
                $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
                //太容易
                //v1 相同专业 or 同一级学科
                //v2 根据意向学校和意向门类和（相同专业 or 同一级学科）和不带 [z]筛选专业
                $res1 = $tMajorSchool->findBy(['schoolid'=>$schoolY]);
                $majorIds = array();
                //提取专业ID
                foreach($res1 as $val) {
                    $majorIds[] = $val->getMajorid();
                }
                //查询同一级学科的数据，如果没有则查同专业名称的数据
                //同一级学科
                $query = $repository->createQueryBuilder('m')
                    ->select('m.id,m.name,m.pronum,m.gzrs')
                    ->andWhere('m.xkml = :xkml')
                    ->setParameter('xkml', $category)
                    ->andWhere('m.schoolLevel IN (:school_type)')
                    ->setParameter('school_type', array(2,3))
                    ->andWhere('m.yjxk = :yjxk')
                    ->setParameter('yjxk', $majorBData->getYjxk())
                    ->andWhere('m.id IN (:marjorIds)')
                    ->setParameter('marjorIds', $majorIds)
                    ->andWhere('m.pronum NOT LIKE :pronum')
                    ->setParameter('pronum', '%Z%')
                    ->setMaxResults(20)
                    ->orderBy('m.id', 'desc')->getQuery();
                $majorData1 = $query->getArrayResult();
                if(!$majorData1) {
                    //同专业名称
                    $query = $repository->createQueryBuilder('m')
                        ->select('m.id,m.name,m.pronum,m.gzrs')
                        ->andWhere('m.xkml = :xkml')
                        ->setParameter('xkml', $category)
                        ->andWhere('m.schoolLevel IN (:school_type)')
                        ->setParameter('school_type', array(2,3))
                        ->andWhere('m.proname = :proname')
                        ->setParameter('proname', $majorBData->getProname())
                        ->andWhere('m.id IN (:marjorIds)')
                        ->setParameter('marjorIds', $majorIds)
                        ->andWhere('m.pronum NOT LIKE :pronum')
                        ->setParameter('pronum', '%Z%')
                        ->setMaxResults(20)
                        ->orderBy('m.id', 'desc')->getQuery();
                    $majorData1 = $query->getArrayResult();
                    if(!$majorData1) {
                        $query = $repository->createQueryBuilder('m')
                            ->select('m.id,m.name,m.pronum,m.gzrs')
                            ->andWhere('m.xkml = :xkml')
                            ->setParameter('xkml', $category)
                            ->andWhere('m.schoolLevel IN (:school_type)')
                            ->setParameter('school_type', array(2,3))
                            ->andWhere('m.proname = :proname')
                            ->setParameter('proname', $majorBData->getProname())
                            //->andWhere('m.id IN (:marjorIds)')
                            //->setParameter('marjorIds', $majorIds)
                            ->andWhere('m.pronum NOT LIKE :pronum')
                            ->setParameter('pronum', '%Z%')
                            ->setMaxResults(20)
                            ->orderBy('m.id', 'desc')->getQuery();
                        $majorData1 = $query->getArrayResult();
                    }
                }
                if(!$majorData1) {
                    $query = $repository->createQueryBuilder('m')
                        ->select('m.id,m.name,m.pronum,m.gzrs')
                        ->andWhere('m.xkml = :xkml')
                        ->setParameter('xkml', $category)
                        ->andWhere('m.schoolLevel IN (:school_type)')
                        ->setParameter('school_type', array(2,3))
                        ->andWhere('m.id IN (:marjorIds)')
                        ->setParameter('marjorIds', $majorIds)
                        ->andWhere('m.pronum NOT LIKE :pronum')
                        ->setParameter('pronum', '%Z%')
                        ->setMaxResults(20)
                        ->orderBy('m.id', 'desc')->getQuery();
                    $majorData1 = $query->getArrayResult();
                }
                $majorData2 = array();
                $majorData3 = array();
                $majorData4 = array();
                $majorData5 = array();
                if(is_array($majorData1) && $majorData1)
                {
                    $yjsMajorData = $majorData1[0]; //研究生专业
                    $pronum2 = substr($yjsMajorData['pronum'], 0, 2); //前2位专业代码
                    $pronum4 = substr($yjsMajorData['pronum'], 0, 4); //前4位专业代码
                    //保底
                    //v1 跨一级学科，专业代码前两位相同
                    //v2 （相同专业 or 同一级学科 ） && 专业代码不带 [z]
                    //v3 同一级学科（专业代码（太容易中获取专业代码）前四位相同 && 专业代码不带 z）
                    $query = $repository->createQueryBuilder('m')
                        ->select('m.id,m.name,m.pronum,m.gzrs')
                        ->andWhere('m.xkml = :xkml')
                        ->setParameter('xkml', $category)
                        ->andWhere('m.id IN (:marjorIds)')
                        ->setParameter('marjorIds', $majorIds)
                        ->andWhere('m.schoolLevel IN (:school_type)')
                        ->setParameter('school_type', array(2, 3))
                        ->andWhere('m.pronum LIKE :pronum')
                        ->setParameter('pronum', '' . $pronum4 . '%')
                        ->andWhere('m.pronum not LIKE :pronum2')
                        ->setParameter('pronum2', '%Z%')
                        ->setMaxResults(20)
                        ->orderBy('m.id', 'desc')->getQuery();
                    $majorData2 = $query->getArrayResult();
                    //推荐
                    //v1 专业代码必须带 [z]
                    //v2 同一级学科（一级学科代码必须带[z]） or  跨一级学科(一级学科代码必须带 [z])
                    //v3 同一级学科（专业代码（太容易中获取专业代码）前四位相同 && 专业代码必须带 z）
                    $query = $repository->createQueryBuilder('m')
                        ->select('m.id,m.name,m.pronum,m.gzrs')
                        ->andWhere('m.xkml = :xkml')
                        ->setParameter('xkml', $category)
                        ->andWhere('m.id IN (:marjorIds)')
                        ->setParameter('marjorIds', $majorIds)
                        ->andWhere('m.schoolLevel IN (:school_type)')
                        ->setParameter('school_type', array(2, 3))
                        ->andWhere('m.pronum LIKE :pronum')
                        ->setParameter('pronum', '' . $pronum4 . '%')
                        ->andWhere('m.pronum LIKE :pronum2')
                        ->setParameter('pronum2', '%Z%')
                        ->setMaxResults(20)
                        ->orderBy('m.id', 'desc')->getQuery();
                    $majorData3 = $query->getArrayResult();
                    //冲刺
                    //v3 跨一级学科（专业代码（太容易中获取专业代码）前2位相同 && 专业代码必须带 z）
                    $query = $repository->createQueryBuilder('m')
                        ->select('m.id,m.name,m.pronum,m.gzrs')
                        ->andWhere('m.xkml = :xkml')
                        ->setParameter('xkml', $category)
                        ->andWhere('m.id IN (:marjorIds)')
                        ->setParameter('marjorIds', $majorIds)
                        ->andWhere('m.schoolLevel IN (:school_type)')
                        ->setParameter('school_type', array(2, 3))
                        ->andWhere('m.pronum LIKE :pronum')
                        ->setParameter('pronum', '' . $pronum2 . '%')
                        ->andWhere('m.pronum LIKE :pronum2')
                        ->setParameter('pronum2', '%Z%')
                        ->setMaxResults(20)
                        ->orderBy('m.id', 'desc')->getQuery();
                    $majorData4 = $query->getArrayResult();
                    //太难
                    //v3 跨一级学科（专业代码（太容易中获取专业代码）前2位相同 && 专业代码不带 z）
                    $query = $repository->createQueryBuilder('m')
                        ->select('m.id,m.name,m.pronum,m.gzrs')
                        ->andWhere('m.xkml = :xkml')
                        ->setParameter('xkml', $category)
                        ->andWhere('m.id IN (:marjorIds)')
                        ->setParameter('marjorIds', $majorIds)
                        ->andWhere('m.schoolLevel IN (:school_type)')
                        ->setParameter('school_type', array(2, 3))
                        ->andWhere('m.pronum LIKE :pronum')
                        ->setParameter('pronum', '' . $pronum2 . '%')
                        ->andWhere('m.pronum NOT LIKE :pronum2')
                        ->setParameter('pronum2', '%Z%')
                        ->setMaxResults(20)
                        ->orderBy('m.id', 'desc')->getQuery();
                    $majorData5 = $query->getArrayResult();
                }
                $data = array(
                    'rongyi' => $majorData1,
                    'baodi' => $majorData2,
                    'tuijian' => $majorData3,
                    'chongci' => $majorData4,
                    'tainan' => $majorData5,
                );
                $totalNumber = count($majorData1) + count($majorData2) + count($majorData3) + count($majorData4) + count($majorData5);
            }
            if($totalNumber<=0) {
                return new JsonResponse(['status'=>0, 'info'=>'推荐专业失败，暂无数据']);
            }
            $result = array(
                'type'=>'major',
                'totalNumber' => $totalNumber,
                'data' => $data,
            );
            return new JsonResponse(['status'=>1, 'info'=>'计算成功', 'data'=>$result]);
        }
        elseif($area && !$schoolY) //推荐相同地区的学校
        {
            //根据意向专业id查询学校
            $schoolIds = array();
            $data = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMajorschool')->findBy(array('majorid'=>$majorY));
            foreach($data as $val) {
                $schoolIds[] = $val->getSchoolid();
            }
            $schoolNdxs = $schoolBData->getNdxs(); //本科学校难度系数
            //***********************************太容易
            //情况一
            $schoolNdxs1 = ($schoolNdxs - 3) <= 0 ? 1 : ($schoolNdxs - 3);
            $schoolData1 = $this->getBySchool($area, $schoolNdxs1, $schoolIds);
            if(!$schoolData1) {
                //情况二
                $schoolNdxs1 = ($schoolNdxs - 4) <= 0 ? 1 : ($schoolNdxs - 4);
                $schoolData1 = $this->getBySchool($area, $schoolNdxs1, $schoolIds);
            }
            //***********************************保底
            $schoolNdxs2 = ($schoolNdxs - 1) <= 0 ? 1 : ($schoolNdxs - 1);
            $schoolData2 = $this->getBySchool($area, $schoolNdxs2, $schoolIds);
            if(!$schoolData2) {
                $schoolNdxs2 = ($schoolNdxs - 2) <= 0 ? 1 : ($schoolNdxs - 2);
                $schoolData2 = $this->getBySchool($area, $schoolNdxs2, $schoolIds);
            }
            //推荐
            $schoolData3 = $this->getBySchool($area, $schoolNdxs, $schoolIds);
            if(!$schoolData3) {
                $schoolNdxs3 = ($schoolNdxs + 1) > 13 ? 13 : ($schoolNdxs + 1);
                $schoolData3 = $this->getBySchool($area, $schoolNdxs3, $schoolIds);
            }
            //冲刺
            $schoolNdxs4 = ($schoolNdxs + 2) > 13 ? 13 : ($schoolNdxs + 2);
            $schoolData4 = $this->getBySchool($area, $schoolNdxs4, $schoolIds);
            if(!$schoolData4) {
                $schoolNdxs4 = ($schoolNdxs + 3) > 13 ? 13 : ($schoolNdxs + 3);
                $schoolData4 = $this->getBySchool($area, $schoolNdxs4, $schoolIds);
            }
            //太难
            $schoolNdxs5 = ($schoolNdxs + 4) > 13 ? 13 : ($schoolNdxs + 4);
            $schoolData5 = $this->getBySchool($area, $schoolNdxs5, $schoolIds);
            if(!$schoolData5) {
                $schoolNdxs5 = ($schoolNdxs + 5) > 13 ? 13 : ($schoolNdxs + 5);
                $schoolData5 = $this->getBySchool($area, $schoolNdxs5, $schoolIds);
            }
            $totalNumber = count($schoolData1) + count($schoolData2) + count($schoolData3) + count($schoolData4) + count($schoolData5);
            $data = array(
                'rongyi' => $schoolData1,
                'baodi' => $schoolData2,
                'tuijian' => $schoolData3,
                'chongci' => $schoolData4,
                'tainan' => $schoolData5,
            );
            if($totalNumber<=0) {
                return new JsonResponse(['status'=>0, 'info'=>'推荐学校失败，暂无数据']);
            }
            $result = array(
                'type'=>'school',
                'totalNumber' => $totalNumber,
                'data' => $data,
            );
            return new JsonResponse(['status'=>1, 'info'=>'计算成功', 'data'=>$result]);
        }

        if(!$maths) {
            return new JsonResponse(['status'=>-5, 'info'=>'参数错误,请选择数学成绩']);
        }
        if(!$english) {
            return new JsonResponse(['status'=>-6, 'info'=>'参数错误,请选择英语成绩']);
        }
        //------------------------------------------根据算法模型推荐数据
        //保存个人资料
        $memberRes = $this->saveMember($year, $majorB, $schoolB, $majorY, $schoolY, $maths, $english, $education);
        if(!$memberRes) {
            return new JsonResponse(['status'=>-20, 'info'=>'用户不存在']);
        }
        //学校系数
        $schoolYData = $schoolModel->findOneBy(['id'=>$schoolY]); //意向学校
        if(!$schoolYData) {
            return new JsonResponse(['status'=>-8, 'info'=>'参数错误,意向学校不存在']);
        }
        //$school = ($schoolBData->getNdxs() - $schoolYData->getNdxs()) > 0 ? ($schoolBData->getNdxs() - $schoolYData->getNdxs()) : abs($schoolBData->getNdxs() - $schoolYData->getNdxs()) ;
        $school = ($schoolBData->getNdxs() - $schoolYData->getNdxs()) > 0 ? 0 : abs($schoolBData->getNdxs() - $schoolYData->getNdxs()) ;
        $school = $school * 0.3;
        //专业系数
        $majorYData = $majorModel->findOneBy(['id'=>$majorY]); //意向专业
        if(!$majorYData) {
            return new JsonResponse(['status'=>-9, 'info'=>'参数错误,意向专业不存在']);
        }

        $major = 0;
        //专业门类不一样
        if( $majorYData->getXkml() != $majorBData->getXkml() ) {
            $major += 3;
        } elseif($majorYData->getYjxk() != $majorBData->getYjxk()) {
            $major += 2;
        } elseif($majorY != $majorB) {
            $major += 1;
        }
        $major = $major * 0.3;
        //数学系数
        $maths = $maths * 0.2;
        //英语系数
        $english = $english * 0.2;
        //结果
        $result = $school + $major + $maths + $english;

        //目标分数测评
        $scoresData = $this->getScores($majorY, $schoolY);
        $data = array(
            'type'=>'result',
            'ndxs' => $result,
            'zone' => $scoresData['zone'],
            'renshu' => $scoresData['renshu'],
            'fenshu' => $scoresData['fenshu'],
            'kemu' => $scoresData['kemu'],

        );
        return new JsonResponse(['status'=>1, 'info'=>'计算成功', 'data'=>$data]);
    }

    /**
     * 根据条件查询推荐学校
     * @param $area 地区id
     * @param $schoolNdxs  难度系数
     * @param array $schoolIds 学校id
     * @return array
     */
    public function getBySchool($area, $schoolNdxs, $schoolIds=array())
    {
        $schoolModel = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TSchool');
        $query = $schoolModel->createQueryBuilder('s')
            ->select('s.id,s.name,s.logo,s.province,s.is211,s.is985,s.isZhx,s.isYjs,s.gzrs')
            //->andWhere('s.schoolLevel = :school_type')
            //->setParameter('school_type', 2)
            ->andWhere('s.schoolLevel IN (:school_type)')
            ->setParameter('school_type', array(2,3))
            ->andWhere('s.province = :province')
            ->setParameter('province', $area)
            ->andWhere('s.ndxs = :ndxs')
            ->setParameter('ndxs', $schoolNdxs)
            ->setMaxResults(20);
        if($schoolIds) {
            $query->andWhere('s.id IN (:schoolIds)')
                ->setParameter('schoolIds', $schoolIds);
        }
        $query = $query->orderBy('s.id', 'desc')->getQuery();
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
        return $schoolData;
    }

    /**
     * 获取推荐专业
     * 本科专业的门类 和 选择的门类不一样
     * @param $data 专业关联表数据
     * @param $category 意向门类
     * @return mixed
     */
    public function getByMajor($data, $category)
    {
        $majorIds = array();
        //提取专业ID
        foreach($data as $val) {
            $majorIds[] = $val->getMajorid();
        }
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
        $query = $repository->createQueryBuilder('m')
            ->select('m.id,m.name,m.pronum,m.gzrs')
            ->andWhere('m.xkml = :xkml')
            ->setParameter('xkml', $category)
            ->andWhere('m.schoolLevel IN (:school_type)')
            ->setParameter('school_type', array(2,3))
            ->andWhere('m.id IN (:marjorIds)')
            ->setParameter('marjorIds', $majorIds)
            ->setMaxResults(20)
            ->orderBy('m.id', 'desc')->getQuery();
        $majorData = $query->getArrayResult();
        return $majorData;
    }

    /**
     * 保存会员考研志愿
     * @param $year 考研年费
     * @param $major 本科专业
     * @param $school 本科院校
     * @param $targetMajor 意向专业
     * @param $targetSchool 意向学校
     * @param $math 数学成绩
     * @param $english 英语成绩
     * @param $education 本科成绩
     * @return JsonResponse
     */
    private function saveMember($year, $major, $school, $targetMajor, $targetSchool, $math, $english, $education)
    {
        $em = $this->getDoctrine()->getManager();
        $members = $em->getRepository('KuakaoAdminBundle:TMember')->find($this->userid);
        if(!$members) {
            return false;
        }
        $members->setYear($year);
        $members->setMajor($major);
        $members->setSchool($school);
        $members->setTargetMajor($targetMajor);
        $members->setTargetSchool($targetSchool);
        $members->setSxcj($math);
        $members->setYycj($english);
        $members->setScoreBa($education);
        $em->persist($members);
        $em->flush();
        return true;
    }

    /**
     * 测评计算
     * @param Request $request
     * @return JsonResponse
     */
    public function evaluationAction(Request $request)
    {
        $schoolY = $request->request->getInt('school_y');  //意向学校
        $majorY = $request->request->getInt('major_y'); //意向专业
        if(!$schoolY) {
            return new JsonResponse(['status'=>-1, 'info'=>'参数错误,请选择意向学校']);
        }
        if(!$majorY) {
            return new JsonResponse(['status'=>-2, 'info'=>'参数错误,请选择意向专业']);
        }
        //根据算法模型推荐数据
        $schoolModel = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TSchool');
        $majorModel = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMajor');
        //从会员资料中获取本科学校、本科专业、数学成绩、英语成绩
        $memberData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMember')->find($this->userid);
//        $memberData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMember')->find(35);

        if(!$memberData || !$memberData->getSxcj() || !$memberData->getYycj() || !$memberData->getSchool() || !$memberData->getMajor()) {
            return new JsonResponse(['status'=>-5, 'info'=>'会员资料不完善']);
        }
        $maths = $memberData->getSxcj();
        $english = $memberData->getYycj();
        //查询本科学校
        $schoolBData = $schoolModel->find($memberData->getSchool());
        //查询本科专业
        $majorB = $memberData->getMajor(); //本科专业ID
        $majorBData = $majorModel->find($memberData->getMajor());
        //学校系数
        $schoolYData = $schoolModel->find($schoolY); //意向学校
        if(!$schoolYData) {
            return new JsonResponse(['status'=>-3, 'info'=>'参数错误,意向学校不存在']);
        }
        //$school = ($schoolBData->getNdxs() - $schoolYData->getNdxs()) > 0 ? ($schoolBData->getNdxs() - $schoolYData->getNdxs()) : 0 ;
        $school = ($schoolBData->getNdxs() - $schoolYData->getNdxs()) > 0 ? 0 : abs($schoolBData->getNdxs() - $schoolYData->getNdxs()) ;
        $school = $school * 0.3;
        //专业系数
        $majorYData = $majorModel->find($majorY); //意向专业
        if(!$majorYData) {
            return new JsonResponse(['status'=>-4, 'info'=>'参数错误,意向专业不存在']);
        }

        $major = 0;
        //专业门类不一样
        if( $majorYData->getXkml() != $majorBData->getXkml() ) {
            $major += 3;
        } elseif($majorYData->getYjxk() != $majorBData->getYjxk()) {
            $major += 2;
        } elseif($majorY != $majorB) {
            $major += 1;
        }
        $major = $major * 0.3;
        //数学系数
        $maths = $maths * 0.2;
        //英语系数
        $english = $english * 0.2;
        //结果
        $result = $school + $major + $maths + $english;
        //目标分数测评
        $scoresData = $this->getScores($majorY, $schoolY);
        $data = array(
            'ndxs' => $result,
            'zone' => $scoresData['zone'],
            'renshu' => $scoresData['renshu'],
            'fenshu' => $scoresData['fenshu'],
            'kemu' => $scoresData['kemu']
        );

        return new JsonResponse(['status'=>1, 'info'=>'计算成功', 'data'=>$data]);
    }

    /**
     * 获取分数
     * @param $majorid 专业ID
     * @param $schoolid 学校ID
     * @return array
     */
    private function getScores($majorid, $schoolid)
    {
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TScores');
        $dayYear = date('Y');
        $year = array($dayYear, ($dayYear-1), ($dayYear-2));
        $query = $repository->createQueryBuilder('m')
            ->andWhere('m.schoolId = :schoolId')
            ->setParameter('schoolId', $schoolid)
            ->andWhere('m.majorId = :majorId')
            ->setParameter('majorId', $majorid)
            ->andWhere('m.year IN (:year)')
            ->setParameter('year', $year)
            ->getQuery();
        $scoresData = $query->getResult();
        //录取难度
        $renshu = array();
        //分数线
        $fenshu = array();
        //科目名称
        $kemu = array();
        foreach($scoresData as $obj) {
            $renshu[$obj->getYear()]['lqrs'] = $obj->getEnrollNum(); //录取人数
            $renshu[$obj->getYear()]['zsrs'] = $obj->getRecruitNum(); //招生人数
            $renshu[$obj->getYear()]['tms'] = $obj->getPushAvoidNum(); //推免生人数

            $fenshu['zhengzhi'][$obj->getYear()] = $obj->getPolitical(); //政治分数
            $fenshu['yingyu'][$obj->getYear()] = $obj->getEnglish(); //英语分数
            $fenshu['ywk1'][$obj->getYear()] = $obj->getProfes1(); //业务课1
            $fenshu['ywk2'][$obj->getYear()] = $obj->getProfes2(); //业务课2
            $fenshu['zongfen'][$obj->getYear()] = $obj->getTotalScore(); //总分


            //插入科目表名称数据
            $kemuData = $this->getKemu($majorid, $schoolid);

            $zhengzhi = $kemuData->getZhengzhi();
            $waiyu = $kemuData->getWaiyu();
            $ywk1 = $kemuData->getYwk1();
            $ywk2 = $kemuData->getYwk2();
            //判断科目里是否有多个科目
            if(substr_count($zhengzhi,',') > 0){
                $zhengzhi = explode(',',$zhengzhi);
            }else{
                $zhengzhi = [$zhengzhi];
            }
            if(substr_count($waiyu,',') > 0){
                $waiyu = explode(',',$waiyu);
            }else{
                $waiyu = [$waiyu];
            }
            if(substr_count($ywk1,',') > 0){
                $ywk1 = explode(',',$ywk1);
            }else{
                $ywk1 = [$ywk1];
            }
            if(substr_count($ywk2,',') > 0){
                $ywk2 = explode(',',$ywk2);
            }else{
                $ywk2 = [$ywk2];
            }
            $kemu['zhengzhi'] = $zhengzhi;
            $kemu['waiyu'] = $waiyu;
            $kemu['ywk1'] = $ywk1;
            $kemu['ywk2'] = $ywk2;

            //插入学校分区
            $zone = $this->getArea($schoolid);
        }
        $data = array('renshu'=>$renshu, 'fenshu'=>$fenshu, 'kemu'=>$kemu, 'zone'=>$zone);
        return $data;
    }

    /**
     * 获取科目名称
     * @param $majorid 专业ID
     * @param $schoolid 学校ID
     * @return array
     */
    private function getKemu($majorid, $schoolid)
    {
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool');
        $query = $repository->createQueryBuilder('m')
            ->andWhere('m.schoolid = :schoolid')
            ->setParameter('schoolid', $schoolid)
            ->andWhere('m.majorid = :majorid')
            ->setParameter('majorid', $majorid)
            ->getQuery();
        $MajorschoolData = $query->getResult();
        return $MajorschoolData[0];

    }

    /**
     * 获取学校分区
     * @param $majorid 专业ID
     * @param $schoolid 学校ID
     * @return array
     */
    private function getArea($schoolid)
    {
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
        $query = $repository->createQueryBuilder('s')
            ->andWhere('s.id = :schoolid')
            ->setParameter('schoolid', $schoolid)
            ->getQuery();
        $schoolData = $query->getArrayResult();
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TArea');
        $query = $repository->createQueryBuilder('a')
            ->andWhere('a.id = :id')
            ->setParameter('id', $schoolData[0]['province'])
            ->getQuery();
        $areaData = $query->getArrayResult();
        return $areaData[0]['zone'];
    }



}