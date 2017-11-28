<?php

/**
 * User: junchiren@kuakao.com
 * Date: 2016/5/14
 * Time: 9:41
 */

namespace Kuakao\Service\Parameters;

class parameter
{

    protected $config;

    public function __construct( $config)
    {
        $this->config = $config;
    }

    /**
     * @param $field_name
     * @return mixed
     */
    public function showField($field_name)
    {
        $field = $this->config[$field_name];
        //交换数组中的键和值
        return array_flip($field);
    }

    /**
     * @param $field_name
     * @return mixed
     */

    public function showFieldValue($field_name)
    {
        $field = $this->config[$field_name];

        return $field;
    }

}
