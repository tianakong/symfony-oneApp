<?php

namespace Kuakao\AdminBundle\Controller;

use Kuakao\AdminBundle\Entity\TMenu;
use Kuakao\AdminBundle\Repository\TMenuRepository;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class MenuController extends BaseController
{
    /**
     * 菜单管理
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $em = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMenu');
        $menuData = $em->getAll();
        //获取顶级菜单
        $topMenuData = array();
        foreach ($menuData as $val) {
            if ($val->getParentid() == 0) {
                $topMenuData[] = $val;
            }
        }
        if (!empty($request->request->get('eq_pid'))) {
            $eq_pid = intval($request->request->get('eq_pid'));
        } elseif (!empty($request->get('eq_pid'))) {
            $eq_pid = intval($request->get('eq_pid'));
        } else {
            $eq_pid = $topMenuData ? $topMenuData['0']->getId() : 0;
        }
        $leftMenuData = TMenuRepository::popup_tree_menu(TMenuRepository::list_to_tree($menuData, $eq_pid));
        $topMenuData = TMenuRepository::fillOption($topMenuData);
        $allMenuData = TMenuRepository::fillOption($menuData);
        //加载菜单form表单
        $menu = new TMenu();
        $form = $this->createForm('Kuakao\AdminBundle\Form\Menu', $menu);
        $params = [
            'topMenuData' => $topMenuData,
            'leftMenuData' => $leftMenuData,
            'eq_pid' => $eq_pid,
            'allMenuData' => $allMenuData,
            'form' => $form->createView(),
        ];
        return $this->render("KuakaoAdminBundle:Menu:index.html.twig", $params);
    }

    /**
     * 添加菜单
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $menu = new TMenu();
        $form = $this->createForm('Kuakao\AdminBundle\Form\Menu', $menu);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $res = $this->getDoctrine()->getManager()->getRepository('KuakaoAdminBundle:TMenu')->findOneBy(['routename'=>$menu->getRoutename()]);
                if($res) {
                    return new JsonResponse(['status' => 0, 'info' => '路由名称已经存在']);
                }
                $this->isRoute($menu->getRoutename()) ? $menu->setUrl($this->generateUrl($menu->getRoutename())) : '';
                $em->persist($menu);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '添加成功']);
            } else {
                return new JsonResponse(['status' => 0, 'info' => '添加失败']);
            }
        }
        return $this->render("KuakaoAdminBundle:Menu:add.html.twig", ['form' => $form->createView()]);
    }

    /**
     * 根据菜单id读取一条数据
     * @param Request $request
     * @return JsonResponse
     */
    public function readAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $result = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMenu')->find($request->get('id'));
        if ($result) {
            return new JsonResponse(['status' => 1, 'data' => $result, 'info' => '读取成功']);
        }
        return new JsonResponse(['status' => 0, 'info' => '读取失败或数据不存在']);
    }

    /**
     * 编辑菜单
     * @param Request $request
     * @param TMenu $tMenu
     * @return JsonResponse
     */
    public function editAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);

        $tMenu = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMenu')->find($request->request->getInt('id'));
        $form = $this->createForm('Kuakao\AdminBundle\Form\Menu', $tMenu);
        $form->handleRequest($request);
        if ($form->isSubmitted())
        {
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $this->isRoute($tMenu->getRoutename()) ? $tMenu->setUrl($this->generateUrl($tMenu->getRoutename())) : '';
                $em->persist($tMenu);
                $em->flush();
                return new JsonResponse(['status' => 1, 'info' => '修改成功']);
            }
            else
            {
                return new JsonResponse(['status' => 0, 'info' => '修改失败']);
            }
        }
        return new JsonResponse(['status' => -1, 'info' => '访问错误']);
    }

    /**
     * 删除菜单
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction(Request $request)
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $id = (int)$request->get('id', 0);
        $em = $this->getDoctrine()->getManager();
        $product = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMenu')->find($id);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(['status' => 1, 'info' => '删除成功']);
    }

    /**
     * 选择父类菜单
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function selectParentAction()
    {
        $this->_globalHook(['routeName'=>$this->getRequest()->attributes->get('_route')]);
        $em =  $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMenu');
        $menuData = $em->getAll([]);
        $menuData = TMenuRepository::popup_tree_menu(TMenuRepository::list_to_tree($menuData));
        return $this->render("KuakaoAdminBundle:Menu:selectParent.html.twig", ['menuData'=>$menuData]);
    }
}