<?php

namespace AwwwesomeEEP\Modules\Navigation\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Widget_Base;

class Child_Navigation extends Widget_Base
{


    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        // register CSS for navigation
        wp_register_style('ctb-stylesheet', plugin_dir_url(__FILE__) . '/../../css/child.navigation.css');
    }

    public function get_style_depends()
    {
        return ['ctb-stylesheet'];
    }

    public function get_name()
    {
        return 'child-navigation';
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
        return __('Child Navigation', 'aw3sm-eep');
    }

    /**
     * Get widget icon.
     *
     * Retrieve button widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eicon-editor-list-ul';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the button widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @return array Widget categories.
     * @since 2.0.0
     * @access public
     *
     */
    public function get_categories()
    {
        return ['basic'];
    }

    /**
     * Register controls
     * @return void
     */
    protected function register_controls()
    {

        // init action
        do_action('aw3sm_eep/widgets/navigation/child_navigation/template/before_section_start', $this);

        $this->start_controls_section(
            'section_template',
            [
                'label' => __('Render', 'aw3sm-eep'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // init action
        do_action('aw3sm_eep/widgets/navigation/child_navigation/template/section_start', $this);

        $types = [];
        $types['all'] = __('All pages', 'aw3sm-eep');
        $types['top_active_parent'] = __('Top Active Page Parent', 'aw3sm-eep');

        $this->add_control(
            'child_navigation_type',
            [
                'label' => esc_html__('Show sub-pages of', 'aw3sm-eep'),
                'type' => Controls_Manager::SELECT,
                'default' => 'all',
                'options' => $types,
            ],
        );
        $this->add_control(
            'child_navigation_depth',
            [
                'label' => esc_html__('Sub-pages depth limit', 'aw3sm-eep'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => -1,
            ],
        );

        $this->add_control(
            'child_navigation_depth_description',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => __('Set -1 without hierarchy, 0 for unlimited dept, 1 for only one level depth, 2 for two levels...',
                    'aw3sm-eep'),
                'separator' => 'none',
                'content_classes' => 'elementor-descriptor',
            ]
        );

        // init action
        do_action('aw3sm_eep/widgets/navigation/child_navigation/template/before_section_end', $this);

        $this->end_controls_section();

        // init action
        do_action('aw3sm_eep/widgets/navigation/child_navigation/template/after_section_end', $this);

        $this->start_controls_section(
            'style_global',
            [
                'label' => __('Global Display', 'aw3sm-eep'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_global_style_controls();

        $this->end_controls_section();

        $this->start_controls_section(
            'style_parent',
            [
                'label' => __('Parent Display', 'aw3sm-eep'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->register_parent_style_controls();

        $this->end_controls_section();

        $this->start_controls_section(
            'style_children',
            [
                'label' => __('Children Display', 'aw3sm-eep'),
                'tab' => Controls_Manager::TAB_STYLE,

                'condition' => [
                    'html_style' => 'modern',
                ],
            ]
        );

        $this->register_children_style_controls();

        $this->end_controls_section();

    }


    /**
     * Register style tab
     * @return void
     */
    protected function register_global_style_controls()
    {
        // add style switcher
        $this->add_control(
            'html_style',
            [
                'label' => esc_html__('HTML style', 'aw3sm-eep'),
                'type' => Controls_Manager::SELECT,
                'default' => 'list',
                'options' => [
                    'list' => __('List style', 'aw3sm-eep'),
                    'modern' => __('Modern', 'aw3sm-eep')
                ]
            ]
        );

    }

    /**
     * Register style tab
     * @return void
     */
    protected function register_parent_style_controls()
    {

        // add link style
        $this->start_controls_tabs('text_colors');

        $this->start_controls_tab(
            'link_colors_normal',
            [
                'label' => esc_html__('Normal', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_color_normal',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation li a' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'link_colors_hover',
            [
                'label' => esc_html__('Hover', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_color_hover',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'link_colors_active',
            [
                'label' => esc_html__('Active', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_color_active',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation li.current_page_item a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .child-navigation .children li.current_page_item a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Background color

        $this->start_controls_tabs('background_colors',
            [
                'condition' => [
                    'html_style' => 'modern',
                ],
            ]
        );

        $this->start_controls_tab(
            'link_background_colors_normal',
            [
                'label' => esc_html__('Normal', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_background_color_normal',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation.modern > li > a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'link_background_colors_hover',
            [
                'label' => esc_html__('Hover', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_background_color_hover',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation.modern > li > a:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'link_background_colors_active',
            [
                'label' => esc_html__('Active', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_background_color_active',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation .current_page_item a' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .child-navigation .children .current_page_item a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Border Radius
        $this->add_responsive_control(
            'first_link_border_radius',
            [
                'label' => esc_html__('Border Radius', 'aw3sm-eep'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .child-navigation.modern > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'html_style' => 'modern',
                ],
            ]
        );

        // Link Padding
        $this->add_responsive_control(
            'link_padding',
            [
                'label' => esc_html__('Link padding', 'aw3sm-eep'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .child-navigation.modern > li > a' => '--padding-top: {{TOP}}{{UNIT}}; --padding-right: {{RIGHT}}{{UNIT}}; --padding-bottom: {{BOTTOM}}{{UNIT}}; --padding-left: {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'html_style' => 'modern',
                ],
            ]
        );

        // Link Margin
        $this->add_responsive_control(
            'link_margin',
            [
                'label' => esc_html__('Link margin', 'aw3sm-eep'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .child-navigation.modern > li > a' => '--margin-top: {{TOP}}{{UNIT}}; --margin-right: {{RIGHT}}{{UNIT}}; --margin-bottom: {{BOTTOM}}{{UNIT}}; --margin-left: {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'html_style' => 'modern',
                ],
            ]
        );
    }

    /**
     * Register style tab
     * @return void
     */
    protected function register_children_style_controls()
    {
        // add link style
        $this->start_controls_tabs('text_children_colors');

        $this->start_controls_tab(
            'link_colors_normal_children',
            [
                'label' => esc_html__('Normal', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_color_normal_children',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation .children li a' => 'color: {{VALUE}};',
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'link_colors_hover_children',
            [
                'label' => esc_html__('Hover', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_color_hover_children',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation .children li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'link_colors_active_children',
            [
                'label' => esc_html__('Active', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_color_active_children',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation ul.children li.current_page_item a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Background color

        $this->start_controls_tabs('background_colors_children');

        $this->start_controls_tab(
            'link_background_colors_normal_children',
            [
                'label' => esc_html__('Normal', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_background_color_normal_children',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation .children  li a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'link_background_colors_hover_children',
            [
                'label' => esc_html__('Hover', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_background_color_hover_children',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation .children  li a:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'link_background_colors_active_children',
            [
                'label' => esc_html__('Active', 'aw3sm-eep'),
            ]
        );

        $this->add_control(
            'link_background_color_active_children',
            [
                'type' => Controls_Manager::COLOR,
                'label' => esc_html__('Background Link Color', 'aw3sm-eep'),
                'name' => 'text_color',
                'selectors' => [
                    '{{WRAPPER}} .child-navigation ul.children li.current_page_item a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        // Border Radius
        $this->add_responsive_control(
            'first_link_border_radius_children',
            [
                'label' => esc_html__('Border Radius', 'aw3sm-eep'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .child-navigation .children li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        // Link Padding
        $this->add_responsive_control(
            'link_padding_children',
            [
                'label' => esc_html__('Link padding', 'aw3sm-eep'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .child-navigation .children li a' => '--padding-top: {{TOP}}{{UNIT}}; --padding-right: {{RIGHT}}{{UNIT}}; --padding-bottom: {{BOTTOM}}{{UNIT}}; --padding-left: {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        // Link Margin
        $this->add_responsive_control(
            'link_margin_children',
            [
                'label' => esc_html__('Link margin', 'aw3sm-eep'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%', 'rem'],
                'selectors' => [
                    '{{WRAPPER}} .child-navigation .children li a' => '--margin-top: {{TOP}}{{UNIT}}; --margin-right: {{RIGHT}}{{UNIT}}; --margin-bottom: {{BOTTOM}}{{UNIT}}; --margin-left: {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        // Sub Link Left Padding
        $this->add_responsive_control(
            'sub_link_left_padding_children',
            [
                'label' => esc_html__('Sub Link Left Padding', 'aw3sm-eep'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 10,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .child-navigation .children' => '--sub-padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
    }

    protected function render()
    {
        global $post;
        // Get the current page's ancestors either from existing value or by executing function.
        $post_ancestors = get_post_ancestors( $post );
        // Get the top page id.
        $top_page = $post_ancestors ? end( $post_ancestors ) : $post->ID;

        $type = $this->get_settings('child_navigation_type');
        $depth = $this->get_settings('child_navigation_depth');

        if ($type == 'all') {
            // show all pages
            echo $this->show_all_pages($depth);
        } elseif ($type == 'top_active_parent') {
            // show only top parent
            echo $this->top_active_parent($top_page, $depth);
        } else {
            echo "Nothing to show";
        }
        return true;
    }

    /**
     * Show all pages
     * @param int $depth
     * @param array $exclude
     * @return string
     */
    function show_all_pages(int $depth = 0, array $exclude = [])
    {
        $_finalContent = "<ul class=\"" . $this->get_class() . "\">";

        $_finalContent .= wp_list_pages(array(
            'title_li' => '',
            'depth' => $depth,
            // 'sort_column' => $instance['sort_by'],
            'exclude' => $exclude,
            'echo' => false,
        ));

        $_finalContent .= '</ul>';
        return $_finalContent;
    }

    /**
     * Show all child pages under parent (top page)
     *
     * @param int $top_page Top parent page
     * @param int $depth Max depth
     * @param array $exclude Array of excluded ids
     * @return string
     */
    function top_active_parent(int $top_page, int $depth = 0, array $exclude = [])
    {
        $_finalContent = "<ul class=\"" . $this->get_class() . "\">";

        $_finalContent .= wp_list_pages(array(
            'title_li' => '',
            'depth' => $depth,
            'child_of' => $top_page,
            // 'sort_column' => $instance['sort_by'],
            'exclude' => $exclude,
            'echo' => false
        ));

        $_finalContent .= "</ul>";

        return $_finalContent;
    }

    /**
     * Get classes string to child_navigation container
     * @return string
     */
    private function get_class()
    {
        $classes = [];

        $style = $this->get_settings('html_style');

        $classes[] = "child-navigation";

        if ($style == 'modern') {
            $classes[] = "modern";
        }

        return implode(' ', $classes);
    }
}