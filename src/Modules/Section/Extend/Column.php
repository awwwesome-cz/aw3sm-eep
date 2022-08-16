<?php

namespace AwwwesomeEEP\Modules\Section\Extend;

use AwwwesomeEEP\Modules\Extend_Base;
use Elementor\Controls_Manager;
use Elementor\Element_Column;

class Column extends Extend_Base
{

    protected function register_controls()
    {
        // add controls
        add_action('elementor/element/column/layout/before_section_end', [$this, 'add_controls']);
    }

    /**
     * Add controls for columns in sections
     *
     * @param Element_Column $element
     */
    public static function add_controls(Element_Column $element)
    {

        // TODO: left-right margin auto
        //
        // Add order
        $element->add_responsive_control(
            '_scb_column_order',
            [
                'label' => __('Column Order', 'aw3sm-eep'),
                'separator' => 'before',
                'type' => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}}.elementor-column' => '-webkit-box-ordinal-group: calc({{VALUE}} + 1 ); -ms-flex-order:{{VALUE}}; order: {{VALUE}};',
                ],
                'description' => sprintf(
                    __('Column ordering is a great addition for responsive design. You can learn more about CSS order property from %sMDN%s or %sw3schools%s.',
                        'aw3sm-eep'),
                    '<a href="https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout/Ordering_Flex_Items#The_order_property" target="_blank">',
                    '</a>',
                    '<a href="https://www.w3schools.com/cssref/css3_pr_order.asp" target="_blank">',
                    '</a>'
                ),
            ]
        );

        // TODO: hide original width = set to custom width
        //
        // Add width
        $element->add_responsive_control(
            '_scb_column_width',
            [
                'label' => __('Custom Column Width', 'aw3sm-eep'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'description' => __('E.g 250px, 50%, calc(100% - 250px)', 'aw3sm-eep'),
                'selectors' => [
                    '{{WRAPPER}}.elementor-column' => 'width: {{VALUE}};',
                ],
            ]
        );
    }
}