<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Entity\TAdminRole;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * TAdminRole controller.
 *
 */
class TAdminRoleController extends BaseController
{
    /**
     * Lists all TAdminRole entities.
     *
     */
    public function indexAction()
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdminRole')->findAll();
        $adminRole = new TAdminRole();
        $form = $this->createForm('Kuakao\AdminBundle\Form\TAdminRoleType', $adminRole);
        return $this->render('KuakaoAdminBundle:AdminRole/index.html.twig', array(
            'data' => $data,
            'form' => $form->createView(),
        ));
    }

    /**
     * 添加角色
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $adminRole = new TAdminRole();
        $form = $this->createForm('Kuakao\AdminBundle\Form\TAdminRoleType', $adminRole);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($adminRole);
                $em->flush();
                return new JsonResponse(['status'=>1, 'info'=>'添加成功']);
            } else {
                //print_r( $form->getErrorsAsString() );
                return new JsonResponse(['status'=>0, 'info'=>'添加失败']);
            }
        }
        return $this->render('KuakaoAdminBundle:AdminRole/add.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * 根据角色id读取一条数据
     * @param Request $request
     * @return JsonResponse
     */
    public function readAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $result = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdminRole')->find($request->get('id'));
        $data = array();
        $data['id']            = $result->getId();
        $data['rolename']     = $result->getRolename();
        $data['description']  = $result->getDescription();
        $data['listorder']    = $result->getListorder();
        $data['disabled']     = $result->getDisabled() == '禁用' ? 0 : 1;
        if ($data) {
            return new JsonResponse(['status' => 1, 'data' => $data, 'info' => '读取成功']);
        }
        return new JsonResponse(['status' => 0, 'info' => '读取失败或数据不存在']);
    }

    /**
     * 编辑角色
     * @param Request $request
     * @return JsonResponse
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = (int)$request->get('id');
        $adminRole = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdminRole')->find($id);
        $form = $this->createForm('Kuakao\AdminBundle\Form\TAdminRoleType', $adminRole);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            if($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($adminRole);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            } else {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        return new JsonResponse(['status' => -1, 'info' => '访问错误']);
    }

    /**
     * 删除角色
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $id = (int)$request->get('id');
        $data = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TAdminRole')->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }
}
