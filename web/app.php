<?php

use Symfony\Component\HttpFoundation\Request;

/**
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../app/bootstrap.php.cache';

// Enable APC for autoloading to improve performance.
// You should change the ApcClassLoader first argument to a unique prefix
// in order to prevent cache key conflicts with other applications
// also using APC.
/*
$apcLoader = new Symfony\Component\ClassLoader\ApcClassLoader(sha1(__FILE__), $loader);
$loader->unregister();
$apcLoader->register(true);
*/

if(true){
    $token = $_REQUEST['access_token'];
    $info = var_export($_REQUEST, true);
    $router = $_SERVER['REQUEST_URI'];

        $info = $router ."\n". $info ."\n";
        if(!file_exists('/tmp/app')) mkdir('/tmp/app');
        file_put_contents("/tmp/app/$token.request.log", $info, FILE_APPEND);

}

$kernel = new AppKernel('prod', true);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();

# debug模式, 只记录api
if(true){
    $token = $_REQUEST['access_token'];
    $info = var_export($_REQUEST, true);
    $router = $_SERVER['REQUEST_URI'];
    if(strstr('admin', $router)===false){
        $info = $router ."\n". $info ."\n";
        $info .= $response->getContent();
        $info .="\n\n";
        if(!file_exists('/tmp/app')) mkdir('/tmp/app');
        file_put_contents("/tmp/app/$token.log", $info, FILE_APPEND);
    }
}

$kernel->terminate($request, $response);
