<?php

namespace AwwwesomeEEP\Modules\Navigation;

use AwwwesomeEEP\Modules\Module_Base;

class Module extends Module_Base
{


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Widgets
     *
     * @return string[]
     * @throws \Exception
     */
    public function get_widgets()
    {
        return [
            'Child_Navigation',
        ];
    }

    /**
     * Module name
     *
     * @return string
     */
    public function get_name()
    {
        return 'Navigatiion';
    }
}