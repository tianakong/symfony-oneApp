<?php

namespace Kuakao\Service\File;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

class SaveFileHandler
{
    protected $container;

    public function __construct( ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function save( $fileObject , $path)
    {
        $dir = $this->container->get('kernel')->getRootDir() . '/../web/' . $path;

        $sub_path = date("Y/m");

        $dir .= '/' . $sub_path . '/';

        $fs = new Filesystem();

        if( !$fs->exists( $dir ) )
        {
            try {
                $fs->mkdir( $dir );
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at ".$e->getPath();
            }
        }

        $mimeType = str_replace( 'image/' , '.' , $fileObject->getMimeType() );

        $newName = md5( $fileObject->getClientOriginalName() . microtime() );

        $newPath = '/'.$path.'/' . $newName . $mimeType;

        $fileObject->move( $dir , $newPath );

        return '/' . $path . '/' . $sub_path . '/' . $newName . $mimeType;
    }

    public function remove( $file)
    {
        $fs = new Filesystem();
        $full_path =  $this->container->get('kernel')->getRootDir() .'/../web'. $file ;

        $full_path = preg_replace('/\/\d+\.\w+$/' , '' , $full_path);
        
        if( $fs->exists( $full_path) )
        {

            try {
                $fs->remove( $full_path );
            } catch (IOExceptionInterface $e) {
                echo "An error occurred while creating your directory at ".$e->getPath();
            }
        }

    }
}