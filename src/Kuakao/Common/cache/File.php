<?php

namespace Kuakao\Common\cache;

define('DATA_CACHE_PATH',     str_replace('web/..', 'app/', $_SERVER['DOCUMENT_ROOT'].'/../cache/data/')); // 应用缓存目录
//define('DATA_CACHE_PATH',     $_SERVER['DOCUMENT_ROOT'].'/../cache/data/'); // 应用缓存目录
/**
 * 文件类型缓存类
 */
class File
{

    /**
     * 架构函数
     * @access public
     */
    public function __construct($options=array()) {
        if(!empty($options)) {
            $this->options =  $options;
        }
        $this->options['temp']      =   !empty($options['temp']) ?   $options['temp']    :   '';
        $this->options['temp']      =    DATA_CACHE_PATH.$this->options['temp'];
        $this->options['prefix']    =   isset($options['prefix'])?  $options['prefix']  :   '';
        $this->options['expire']    =   isset($options['expire'])?  $options['expire']  :   0;
        $this->options['length']    =   isset($options['length'])?  $options['length']  :   0;
        if(substr($this->options['temp'], -1) != '/')    $this->options['temp'] .= '/';
        $this->init();
    }

    /**
     * 初始化检查
     * @access private
     * @return boolean
     */
    private function init() {
        // 创建应用缓存目录
        if (!is_dir($this->options['temp'])) {
            //var_dump($this->options['temp']);
            mkdir($this->options['temp']);
        }
    }

    /**
     * 取得变量的存储文件名
     * @access private
     * @param string $name 缓存变量名
     * @return string
     */
    private function filename($name) {
        $name	=	md5($name);
        $filename	=	$this->options['prefix'].$name.'.php';
        return $this->options['temp'].$filename;
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name, $DATA_CACHE_COMPRESS=false) {
        $filename   =   $this->filename($name);
        if (!is_file($filename)) {
            return false;
        }
        $content    =   file_get_contents($filename);
        if( false !== $content) {
            $expire  =  (int)substr($content,8, 12);
            //取消删除缓存文件
            /*if($expire != 0 && time() > filemtime($filename) + $expire) {
                //缓存过期删除缓存文件
                unlink($filename);
                return false;
            }*/
            $content   =  substr($content,20, -3);
            if($DATA_CACHE_COMPRESS && function_exists('gzcompress')) {
                //启用数据压缩
                $content   =   gzuncompress($content);
            }
            $content    =   unserialize($content);
            return $content;
        }
        else {
            return false;
        }
    }

    /**
     * 写入缓存
     * @access public
     * @param string $name 缓存变量名
     * @param mixed $value  存储数据
     * @param int $expire  有效时间 0为永久
     * @param $DATA_CACHE_COMPRESS // 数据缓存是否压缩缓存
     * @return boolean
     */
    public function set($name, $value, $expire=null, $DATA_CACHE_COMPRESS = false) {
        if(is_null($expire)) {
            $expire =  $this->options['expire'];
        }
        $filename   =   $this->filename($name);
        $data   =   serialize($value);
        if($DATA_CACHE_COMPRESS && function_exists('gzcompress')) {
            //数据压缩
            $data   =   gzcompress($data,3);
        }
        $check  =  '';
        $data    = "<?php\n//".sprintf('%012d',$expire).$check.$data."\n?>";
        $result  =   file_put_contents($filename,$data);
        if($result) {
            clearstatcache();
            return true;
        }else {
            return false;
        }
    }

    /**
     * 删除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function rm($name) {
        return @unlink($this->filename($name));
    }

    /**
     * 清除缓存
     * @access public
     * @param string $name 缓存变量名
     * @return boolean
     */
    public function clear() {
        $path   =  $this->options['temp'];
        $files  =   scandir($path);
        if($files){
            foreach($files as $file){
                if ($file != '.' && $file != '..' && is_dir($path.$file) ){
                    array_map( 'unlink', glob( $path.$file.'/*.*' ) );
                }elseif(is_file($path.$file)){
                    unlink( $path . $file );
                }
            }
            return true;
        }
        return false;
    }
}