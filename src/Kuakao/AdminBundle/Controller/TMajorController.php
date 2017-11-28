<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Entity\TMajorschool;
use Kuakao\AdminBundle\Entity\TSchool;
use Kuakao\AdminBundle\Repository\TMajorRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use Kuakao\AdminBundle\Entity\TMajor;

use Kuakao\AdminBundle\Form\TMajorType;

/**
 * TMajor controller.
 *
 */
class TMajorController extends BaseController
{
    /**
     * Lists all TMajor entities.
     * @专业列表方法
     * yanyuchuan
     *
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $page =  $request->query->getInt('page', 1);
        $name =  $request->query->get('name');
        $where = [];
        if($name){
            $where = ['name' => $name];
        }

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('KuakaoAdminBundle:TMajor')->findBy($where,['id' => 'desc']);

        $paginator = $this->get('knp_paginator');
        $majorData = $paginator->paginate($query, $page, 20);

        return $this->render('KuakaoAdminBundle:Major/index.html.twig', array(
            'majorData' => $majorData,
            'page' => $page,
            'name' => $name,
        ));
    }

    /*
     * Ajax一级学科查询
     * yanyuchuan
     *
     * */
    public function ajaxyjAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $ml=$request->query->get('xkml');
        $YjxkData=$this->getDoctrine()->getRepository('KuakaoAdminBundle:TYjxk')->findBy(['mlname'=>$ml],['id'=>'desc']);
        return $this->render('KuakaoAdminBundle:Major/ajaxyj.html.twig', array(
            'Yjxk'=>$YjxkData,
        ));
    }


    /**
     * Creates a new TMajor entity.
     * @添加专业方法
     * yanyuchuan
     *
     */
    public function newAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $tMajor = new TMajor();
        $form = $this->createFormBuilder($tMajor)
            ->add('name')
            ->add('pronum')
            ->add('gzrs')
//            ->add('ndxs')
            ->add('zyjs','textarea')
            ->add('jyfx','textarea')
            ->getForm();
        $form->handleRequest($request);

        $bksy = $request->request->get('bksy');
        $yjsy = $request->request->get('yjsy');
        $schoolLevel['bksy'] = isset($bksy) ? $bksy : 0;
        $schoolLevel['yjsy'] = isset($yjsy) ? $yjsy : 0;
        $level = $schoolLevel['bksy'] + $schoolLevel['yjsy'];

        if ($form->isSubmitted())
        {
            if( $form->isValid() )
            {
                $arr = explode(',', $request->request->get('yjxk'));
                $em = $this->getDoctrine()->getManager();
                $tMajor->setXkml($request->request->get('xkml'));
                $tMajor->setYjxk($arr[0]);
                $tMajor->setYjxknum($arr[1]);
                /*$tMajor->setMathlx($request->request->get('mathlx'));
                $tMajor->setEnglishlx($request->request->get('englishlx'));*/
                $tMajor->setZylx($request->request->get('zylx'));
                $tMajor->setAdminuser($this->username);
                $tMajor->setAddtime(time());
                $tMajor->setSchoolLevel($level);
                $em->persist($tMajor);
                $em->flush();
                $ids = $request->request->get('schoolIds');
                if($ids)
                {
                    $idsData=explode(',',$ids);
                    foreach($idsData as $val)
                    {
                        $MajorSchool = new TMajorschool();
                        $SchoolData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool')->find($val);
                        $mname = $tMajor->getName();
                        $pronum = $tMajor->getPronum();
                        $MajorData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->findOneBy(['pronum'=>$pronum]);
                        $mid = $MajorData->getId();
                        $sid = $SchoolData->getId();
                        $sname = $SchoolData->getName();
                        $ms = $this->getDoctrine()->getManager();
                        $MajorSchool -> setMajorid($mid);
                        $MajorSchool->setMajorname($mname);
                        $MajorSchool->setSchoolid($sid);
                        $MajorSchool->setSchoolname($sname);
                        $ms->persist($MajorSchool);
                        $ms->flush();
                    }
                }
                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
            }
            else
            {
                //var_dump($form->getErrorsAsString());
                return new JsonResponse(['status' => 0, 'info' => '添加失败']);
            }
        }
        //$YjxkData=$this->getDoctrine()->getRepository('KuakaoAdminBundle:TYjxk')->findAll();
        $MlData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('xkml');
        $MathData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('mathlx');
        $EnglishData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('englishlx');
        $ZyData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('zylx');

        return $this->render('KuakaoAdminBundle:Major/add.html.twig', array(
            'form'=>$form->createView(),
            /*'tMajor' => $tMajor,*/
            'form' => $form->createView(),
            'Xkml' => $MlData,
            'Math' => $MathData,
            'English' => $EnglishData,
            'Zy' => $ZyData,
            /*'Yjxk'=>$YjxkData,*/
        ));
    }

    /**
     * 显示所设院校的列表
     *yanyuchuan
     *
     */
    public function showAction(Request $request)
    {

        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $school_name = $request->query->get('school_name');
        $where = [];
        if($school_name) {
            $where = ['name'=>$school_name];
        }

        $page =  $request->query->getInt('page', 1);
        $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
        $query = $repository->createQueryBuilder('s')
            ->andWhere('s.name LIKE :name')
            ->setParameter('name', '%'.$school_name.'%')
            ->orderBy('s.id', 'ASC')
            ->getQuery();
        $schools = $query->getResult();

        $paginator = $this->get('knp_paginator');
        $SchoolData = $paginator->paginate($schools, $page, 5);

        return $this->render('KuakaoAdminBundle:Major/list.html.twig', array(
            'schoolData' => $SchoolData,
        ));
    }


    /**
     * Displays a form to edit an existing TMajor entity.
     * @编辑专业
     * yanyuchuan
     *
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->query->getInt('id');
        $majorData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->find($id);

        $form = $this->createFormBuilder($majorData)
            ->add('name')
            ->add('pronum')
            ->add('gzrs')
//            ->add('ndxs')
            ->add('zyjs','textarea')
            ->add('jyfx','textarea')
            ->getForm();
        $form->handleRequest($request);

        $bksy = $request->request->get('bksy');
        $yjsy = $request->request->get('yjsy');
        $schoolLevel['bksy'] = isset($bksy) ? $bksy : 0;
        $schoolLevel['yjsy'] = isset($yjsy) ? $yjsy : 0;
        $level = $schoolLevel['bksy'] + $schoolLevel['yjsy'];

        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $arr = explode(',', $request->request->get('yjxk'));
                $em = $this->getDoctrine()->getManager();
                $majorData->setXkml($request->request->get('xkml'));
                $majorData->setYjxk($arr[0]);
                $majorData->setYjxknum($arr[1]);
               /* $majorData->setMathlx($request->request->get('mathlx'));
                $majorData->setEnglishlx($request->request->get('englishlx'));*/
                $majorData->setZylx($request->request->get('zylx'));
                $majorData->setAdminuser($this->username);
                $majorData->setAddtime(time());
                $majorData->setSchoolLevel($level);
                $em->persist($majorData);
                $em->flush();
                $ids = $request->request->get('schoolIds');
                if($ids)
                {
                    $idsData = explode(',', $ids);
                    $MajorSchoolData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool')->findBy(['majorid'=>$id]);
                    foreach($MajorSchoolData as $v) {
                        $msd = $this->getDoctrine()->getManager();
                        $row = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool')->find($v);
                        //print_r($row);
                        $msd -> remove($row);
                        $msd -> flush();
                    }
                    foreach ($idsData as $val) {

                        $MajorSchool = new TMajorschool();
                        $SchoolData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool')->find($val);

                        $mname = $majorData->getName();
                        $pronum = $majorData->getPronum();
                        $Major = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->findOneBy(['pronum' => $pronum]);
                        $mid = $Major->getId();
                        $sid = $SchoolData->getId();
                        $sname = $SchoolData->getName();

                        $ms = $this->getDoctrine()->getManager();
                        $MajorSchool->setMajorid($mid);
                        $MajorSchool->setMajorname($mname);
                        $MajorSchool->setSchoolid($sid);
                        $MajorSchool->setSchoolname($sname);
                        $ms->persist($MajorSchool);
                        $ms->flush();
                    }
                }
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }

        $YjxkData=$this->getDoctrine()->getRepository('KuakaoAdminBundle:TYjxk')->findAll();

        $MlData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('xkml');
        $MathData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('mathlx');
        $EnglishData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('englishlx');
        $ZyData = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->getLx('zylx');

        return $this->render('KuakaoAdminBundle:Major/edit.html.twig', [
            'form'=>$form->createView(),
            'majorData' => $majorData,
            'id' => $id,
            'Xkml' => $MlData,
            'Math' => $MathData,
            'English' => $EnglishData,
            'Zy' => $ZyData,
            'Yjxk'=>$YjxkData,
        ]);
    }

    /**
     * Deletes a TMajor entity.
     * @删除专业
     * yanyuchuan
     *
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = $request->request->getInt('id');
        //删除专业表数据
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor')->find($id);
        $em->remove($product);
        $em->flush();
        //删除关联表数据
        $majorschool = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool')->findBy(['majorid'=>$id]);
        foreach($majorschool as $val) {
            $ms = $this->getDoctrine()->getManager();
            $row = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajorschool')->find($val);
            //print_r($row);
            $ms->remove($row);
            $ms->flush();
        }
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * Creates a form to delete a TMajor entity.
     *
     * @param TMajor $tMajor The TMajor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TMajor $tMajor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('tmajor_delete', array('id' => $tMajor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
