<?php

namespace AwwwesomeEEP\Modules\Utils;

use AwwwesomeEEP\Modules\Module_Base;

class Module extends Module_Base
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Module name
     *
     * @return string
     */
    public function get_name()
    {
        return 'Utils';
    }

    public function get_dynamic_tags()
    {
        return ['Template'];
    }
}