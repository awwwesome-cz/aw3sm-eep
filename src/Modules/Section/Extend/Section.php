<?php

namespace AwwwesomeEEP\Modules\Section\Extend;

use AwwwesomeEEP\Modules\Extend_Base;
use Elementor\Controls_Manager;
use Elementor\Element_Section;

class Section extends Extend_Base
{

    protected function register_controls()
    {
        // add controls
        add_action('elementor/element/section/section_layout/before_section_end', [$this, 'add_controls']);
    }

    /**
     * Add controls to section
     *
     * @param Element_Section $element
     */
    public static function add_controls(Element_Section $element)
    {
        //
        // Add responsive align columns in section
        $element->add_responsive_control(
            'scb_section_horizontal_align',
            [
                'label' => __('Horizontal align', 'aw3sm-eep'),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default', 'elementor'),
                    'flex-start' => __('Start', 'elementor'),
                    'flex-end' => __('End', 'elementor'),
                    'center' => __('Center', 'elementor'),
                    'space-between' => __('Space Between', 'elementor'),
                    'space-around' => __('Space Around', 'elementor'),
                    'space-evenly' => __('Space Evenly', 'elementor'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-container' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}} .elementor-row' => 'justify-content: {{VALUE}};',
                ],
            ]
        );
    }
}