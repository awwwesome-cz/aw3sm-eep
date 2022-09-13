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

	protected function get_dynamic_tags() {
		return ['Top_Parent_Page'];
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