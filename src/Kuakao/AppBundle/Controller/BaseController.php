<?php

namespace Kuakao\AppBundle\Controller;

use Kuakao\Common\cache\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    protected $cache;

    protected $userid;

    protected $userData;

    public function __construct()
    {
        $access_token  = isset($_POST['access_token']) ? $_POST['access_token'] : (isset($_GET['access_token']) ? $_GET['access_token'] : null);
        if(!$access_token) {

			echo json_encode(['status'=>'4001', 'info'=>'访问错误']);exit;
        }
        $this->cache = new File();
        $this->userid = $this->cache->get($access_token);
        $this->userData = $this->cache->get($access_token.'Data');
        if(!$this->userid || !$this->userData) {
            echo json_encode(['status'=>'4003', 'info'=>'没有权限访问']);exit;
        }
    }

    /**
     * 判断学校或者专业是否被关注
     * @param $data
     * @param $type
     * @return mixed
     */
    protected function isFollow($data, $type)
    {
        foreach($data as &$val)
        {
            $where = array(
                'type'=>$type,
                'fid' => $val['id'],
                'uid' => $this->userid,
            );
            $res = $this->getDoctrine()->getRepository('KuakaoAdminBundle:TFollow')->findOneBy($where);

            file_put_contents('/tmp/isfollow.log' , var_export($where, 1) . var_export($res,1) ,FILE_APPEND);

            if($res) {
                $val['follow'] = true;
            } else {
                $val['follow'] = false;
            }
        }
        return $data;
    }

}