<?php
namespace Kuakao\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends BaseController
{
    /**
     * 根据关键词搜索学校或者专业
     * 搜索专用，也可以单独使用获取专业或者获取学校接口
     * @param Request $request
     * @return JsonResponse
     */
    public function indexAction(Request $request)
    {
        $schlvl = [2, 3]; # 2研究生， 3 研究生+本科
        $searcType = $request->request->get('type'); //搜索类型 major / school
        $keywords = $request->request->get('keyword'); //搜索关键词
        $page =  $request->request->getInt('page', 0); //当前页数
        $pagesize =  $request->request->getInt('pagesize', 20); //一页显示多少条
        if(!$keywords) {
            return new JsonResponse(['status'=>-1, 'info'=>'请输入搜索关键词']);
        }
        if(!$searcType) {
            return new JsonResponse(['status'=>-2, 'info'=>'搜索错误,请选择搜索类型']);
        }
        $data = array();
        if($searcType == 'major')
        {
            //查专业
            $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
            $query = $repository->createQueryBuilder('m')
                ->select('m.id,m.name,m.pronum,m.gzrs')
                ->andWhere('m.name LIKE :name')
                ->andWhere('m.schoolLevel IN (:schoollevel)')
                ->setParameter('name', '%'.$keywords.'%')
                ->setParameter('schoollevel', $schlvl)
                ->orderBy('m.id', 'asc')
                ->setMaxResults($pagesize)
                ->setFirstResult($page)
                ->getQuery();
            $data = $query->getArrayResult();
            # 根据专业代码排重
            if(is_array($data)){
                $redata = [];
                foreach ($data as $item){
                    $redata[$item['pronum']] = $item;
                }
                $data = array_values($redata);
            }
            //查看专业是否已经被关注
            $data = $this->isFollow($data, 2);
        }
        elseif($searcType == 'school')
        {
            //查学校
            $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
            $query = $repository->createQueryBuilder('s')
                ->select('s.id,s.name,s.logo,s.province,s.is211,s.is985,s.isZhx,s.isYjs,s.gzrs')
                ->andWhere('s.schoolLevel IN (:schoollevel)')
                ->andWhere('s.name LIKE :name')
                ->setParameter('name', '%'.$keywords.'%')
                ->setParameter('schoollevel', $schlvl)
                ->orderBy('s.id', 'asc')
                ->setMaxResults($pagesize)
                ->setFirstResult($page)
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
            //查看学校是否已经被关注
            $data = $this->isFollow($data, 1);
        }
        $result = array(
            'data' => $data,
            'page' => $page,
        );
        return new JsonResponse(['status'=>1, 'info'=>'搜索成功', 'data'=>$result]);
    }

    /**
     * 关联关键词
     * @param Request $request
     * @return JsonResponse
     */
    public function keywordsAction(Request $request)
    {
        $schlvl =[2,3];
        $keyword = $request->get('keyword'); //关键词
        $type = $request->get('type'); //搜索类型 major / school
        $pagesize = $request->get('pagesize', 10); //显示多少条
        if(!$keyword) {
            return new JsonResponse(['status'=>-1, 'info'=>'请输入搜索关键词']);
        }
        if(!$type) {
            return new JsonResponse(['status'=>-2, 'info'=>'搜索错误,请选择搜索类型']);
        }
        if($type == 'major')
        {
            //查专业
            $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TMajor');
            $query = $repository->createQueryBuilder('m')
                ->select('m.proname as name' )
                ->andWhere('m.proname LIKE :name')
                ->andWhere('m.schoolLevel IN (:schoollevel)')
                ->setParameter('schoollevel', $schlvl)
                ->setParameter('name', '%'.$keyword.'%')
                ->orderBy('m.id', 'desc')
                ->setMaxResults($pagesize)
                ->getQuery();
            $data = $query->getArrayResult();

            # 拆分‘）’,且唯一化
            if(is_array($data)){
                foreach ($data as $item){
                    $names[] = $item['name'];
                }
                $names = array_unique($names);
                $data =[];
                foreach ($names as $nm){
                    $data[] =['name'=>$nm];
                }
            }
        }
        elseif($type == 'school')
        {
            //查学校
            $repository = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TSchool');
            $query = $repository->createQueryBuilder('s')
                ->select('s.name')
                ->andWhere('s.name LIKE :name')
                ->andWhere('s.schoolLevel IN (:schoollevel)')
                ->setParameter('name', '%'.$keyword.'%')
                ->setParameter('schoollevel', $schlvl)
                ->orderBy('s.id', 'asc')
                ->setMaxResults($pagesize)
                ->getQuery();
            $data = $query->getArrayResult();
        }
        return new JsonResponse(['status'=>1, 'info'=>'搜索成功', 'data'=>$data]);
    }

    /**
     * 获取热门关键词
     * @return JsonResponse
     */
    public function hotKeywordAction()
    {
        $data = array(
            'major' => array('会计学', '会计硕士', '经济学', '金融硕士', '翻译硕士', '医学硕士'),
            'school'=> array('北京大学', '清华大学', '上海大学', '武汉大学', '东北大学', '山东大学'),
        );
        return new JsonResponse(['status'=>1, 'info'=>'读取成功', 'data'=>$data]);
    }

}