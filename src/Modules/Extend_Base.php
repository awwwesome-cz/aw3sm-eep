<?php

namespace AwwwesomeEEP\Modules;

abstract class Extend_Base
{
    public function __construct()
    {
        $this->register_controls();
    }


    /**
     * Set all do_action() hooks for elementor
     * @return void
     */
    protected function register_controls()
    {
    }
}