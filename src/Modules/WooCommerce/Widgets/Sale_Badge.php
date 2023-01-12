<?php

namespace AwwwesomeEEP\Modules\WooCommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;

class Sale_Badge extends Widget_Base
{

    public function get_name()
    {
        return 'woo-sale-badge';
    }

    /**
     * Get widget title.
     *
     * Retrieve button widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return __('Sale Badge', 'aw3sm-eep');
    }

    /**
     * Get widget icon.
     *
     * Retrieve button widget icon.
     *
     * @return string Widget icon.
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eicon-woocommerce-notices';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the button widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @return array Widget categories.
     * @access public
     *
     */
    public function get_categories()
    {
        return ['woocommerce-elements'];
    }

    protected function register_controls()
    {
        do_action('aw3sm_eep/widgets/woocommerce/sale-badge/template/before_section_start', $this);

        $this->start_controls_section(
            'section_value',
            [
                'label' => __('Value', 'aw3sm-eep'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        do_action('aw3sm_eep/widgets/woocommerce/sale-badge/template/section_start', $this);

        $this->add_control(
            'value_type',
            [
                'label' => __('Type', 'aw3sm-eep'), // 1 level of ACF group
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => __('Default', 'aw3sm-eep'),
                    'percentage' => __('Percentage', 'aw3sm-eep'),
                ],
            ],
        );

        $this->add_control(
            'prefix_sale',
            [
                'label' => __('Sale Prefix', 'aw3sm-eep'), // 1 level of ACF group
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'default' => '-',
                'condition' => [
                    'value_type' => 'percentage',
                ]
            ],
        );
        $this->add_control(
            'prefix_sale_group',
            [
                'label' => __('Group Sale Prefix', 'aw3sm-eep'), // 1 level of ACF group
                'type' => Controls_Manager::TEXT,
                'default' => __('Up to -', 'aw3sm-eep'),
                'condition' => [
                    'value_type' => 'percentage',
                ]
            ],
        );

        $this->add_control(
            'suffix_sale',
            [
                'label' => __('Sale Suffix', 'aw3sm-eep'), // 1 level of ACF group
                'type' => Controls_Manager::TEXT,
                'default' => '%',
                'condition' => [
                    'value_type' => 'percentage',
                ]
            ],
        );

        do_action('aw3sm_eep/widgets/acf/repeater/template/before_section_end', $this);

        $this->end_controls_section();

        do_action('aw3sm_eep/widgets/acf/repeater/template/after_section_end', $this);

        // styles
        $this->register_style_controls();
    }

    protected function render()
    {

        global $product;
        global $post;

        // if not in sale
        if (!$product->is_on_sale()) {
            return;
        }

        $value_type = $this->get_settings('value_type');
        if ($value_type == 'percentage') {
            echo '<span class="aw3sm-woo-onsale-badge">' . $this->display_percentage_on_sale_badge($product) . '</span>';
        } else {
            // default WC hook
            echo apply_filters('woocommerce_sale_flash',
                '<span class="aw3sm-woo-onsale-badge">' . __('Sale!', 'woocommerce') . '</span>', $post,
                $product);
        }

    }

    /**
     * Display percentage on sale
     *
     * @param $product
     * @return string
     */
    protected function display_percentage_on_sale_badge($product)
    {
        $prefix_sale = $this->get_settings('prefix_sale');
        $prefix_sale_group = $this->get_settings('prefix_sale_group');
        $suffix_sale = $this->get_settings('suffix_sale');

        if ($product->is_type('variable')) {
            $percentages = array();

            // This will get all the variation prices and loop throughout them
            $prices = $product->get_variation_prices();

            foreach ($prices['price'] as $key => $price) {
                // Only on sale variations
                if ($prices['regular_price'][$key] !== $price) {
                    // Calculate and set in the array the percentage for each variation on sale
                    $percentages[] = round(100 - (floatval($prices['sale_price'][$key]) / floatval($prices['regular_price'][$key]) * 100));
                }
            }
            // Displays maximum discount value
            $percentage = $prefix_sale . max($percentages) . $suffix_sale;

        } elseif ($product->is_type('grouped')) {
            $percentages = array();

            // This will get all the variation prices and loop throughout them
            $children_ids = $product->get_children();

            foreach ($children_ids as $child_id) {
                $child_product = wc_get_product($child_id);

                $regular_price = (float)$child_product->get_regular_price();
                $sale_price = (float)$child_product->get_sale_price();

                if ($sale_price != 0 || !empty($sale_price)) {
                    // Calculate and set in the array the percentage for each child on sale
                    $percentages[] = round(100 - ($sale_price / $regular_price * 100));
                }
            }
            // Displays maximum discount value
            $percentage = $prefix_sale_group . max($percentages) . $suffix_sale;

        } else {
            $regular_price = (float)$product->get_regular_price();
            $sale_price = (float)$product->get_sale_price();

            if ($sale_price != 0 || !empty($sale_price)) {
                $percentage = '-' . round(100 - ($sale_price / $regular_price * 100)) . '%';
            } else {
                $percentage = '-0%';
            }
        }
        return $percentage; // If needed then change or remove "up to -" text
    }

    /**
     * Register styles
     *
     * @return void
     */
    protected function register_style_controls()
    {
        $this->start_controls_section(
            'style_template',
            [
                'label' => __('Value', 'aw3sm-eep'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __('Text color', 'aw3sm-eep'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
                'selectors' => [
                    '{{WRAPPER}} .aw3sm-woo-onsale-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background color', 'aw3sm-eep'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .aw3sm-woo-onsale-badge' => 'background: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();

        // Padding
        $this->start_controls_section(
            'section_spacing',
            [
                'label' => __('Spacing', 'aw3sm-eep'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'sale_padding',
            [
                'label' => __('Padding', 'aw3sm-eep'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .aw3sm-woo-onsale-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
                'separator' => 'after',
            ]
        );

        $this->end_controls_section();

        // set border style section
        $this->border_style();
    }

    /**
     * Border style
     *
     * @return void
     */
    protected function border_style()
    {
        $this->start_controls_section(
            'section_border',
            [
                'label' => __('Border', 'elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .aw3sm-woo-onsale-badge',
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'custom'],
                'selectors' => [
                    '{{WRAPPER}} .aw3sm-woo-onsale-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .aw3sm-woo-onsale-badge',
            ]
        );

        $this->end_controls_section();
    }
}