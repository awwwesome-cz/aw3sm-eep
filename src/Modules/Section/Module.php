<?php

namespace AwwwesomeEEP\Modules\Section;

use AwwwesomeEEP\Modules\Module_Base;

class Module extends Module_Base
{


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Extends
     *
     * @return string[]
     * @throws \Exception
     */
    public function get_extends()
    {
        return [
            'Section',
            'Column'
        ];
    }

    /**
     * Module name
     *
     * @return string
     */
    public function get_name()
    {
        return 'Section';
    }
}