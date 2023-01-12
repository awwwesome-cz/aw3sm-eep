<?php

namespace AwwwesomeEEP\Modules\WooCommerce;

use AwwwesomeEEP\Modules\Module_Base;

class Module extends Module_Base
{

    /**
     * Widgets
     *
     * @return string[]
     * @throws \Exception
     */
    public function get_widgets()
    {
        // check if WooCommerce is activated
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            return [
                'Sale_Badge',
            ];
        }
        return [];
    }

    /**
     * Module name
     *
     * @return string
     */
    public function get_name()
    {
        return 'Woo Commerce';
    }
}