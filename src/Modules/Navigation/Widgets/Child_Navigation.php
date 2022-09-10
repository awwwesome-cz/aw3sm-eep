<?php

namespace AwwwesomeEEP\Modules\Navigation\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Widget_Base;

class Child_Navigation extends Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );

		// register CSS for navigation
		wp_register_style( 'ctb-stylesheet', plugin_dir_url( __FILE__ ) . '/../../css/child.navigation.css' );

		// register JS for navigation
		wp_register_script( 'script-handle', plugin_dir_url( __FILE__ ) . '/../../js/child.navigation.js',
			[ 'elementor-frontend' ], '1.0.0', true );
	}


	public function get_style_depends() {
		return [ 'ctb-stylesheet' ];
	}

	public function get_script_depends() {
		return [ 'script-handle' ];
	}

	public function get_name() {
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
	public function get_title() {
		return __( 'Child Navigation', 'aw3sm-eep' );
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
	public function get_icon() {
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
	public function get_categories() {
		return [ 'basic' ];
	}

	/**
	 * Register controls
	 * @return void
	 */
	protected function register_controls() {

		// init action
		do_action( 'aw3sm_eep/widgets/navigation/child_navigation/template/before_section_start', $this );

		$this->start_controls_section(
			'section_template',
			[
				'label' => __( 'Render', 'aw3sm-eep' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// init action
		do_action( 'aw3sm_eep/widgets/navigation/child_navigation/template/section_start', $this );

		// TODO: add style switcher
		/*$this->add_control(
			'style',
			[
				'label'   => esc_html__( 'Parent style', 'aw3sm-eep' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'vertical',
				'options' => [
					'horizontal'   => __( 'Horizontal', 'aw3sm-eep' ),
					'vertical' => __( 'Vertical', 'aw3sm-eep' )
				]
			]
		);*/

		$this->add_title_control();

		$types                      = [];
		$types['all']               = __( 'All pages', 'aw3sm-eep' );
		$types['top_active_parent'] = __( 'Top Active Page Parent', 'aw3sm-eep' );

		$this->add_control(
			'child_navigation_source',
			[
				'label'   => esc_html__( 'Show sub-pages of', 'aw3sm-eep' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => $types,
			],
		);
		$this->add_control(
			'child_navigation_depth',
			[
				'label'   => esc_html__( 'Sub-pages depth limit', 'aw3sm-eep' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 0,
				'min'     => - 1,
			],
		);

		$this->add_control(
			'child_navigation_depth_description',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'Set -1 without hierarchy, 0 for unlimited depth, 1 for only one level depth, 2 for two levels...',
					'aw3sm-eep' ),
				'separator'       => 'none',
				'content_classes' => 'elementor-descriptor',
			]
		);

		// init action
		do_action( 'aw3sm_eep/widgets/navigation/child_navigation/template/before_section_end', $this );

		$this->end_controls_section();

		// init action
		do_action( 'aw3sm_eep/widgets/navigation/child_navigation/template/after_section_end', $this );

		$this->start_controls_section(
			'style_global',
			[
				'label' => __( 'Global Display', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// register global styles
		$this->add_global_style_controls();

		$this->end_controls_section();

		// register title style section
		$this->add_title_style_control();

		// register toggle style section
		$this->add_toggle_style_control();

		$this->start_controls_section(
			'style_parent',
			[
				'label' => __( 'List style', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->register_parent_style_controls();

		$this->end_controls_section();

		/*$this->start_controls_section(
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
		*/

		//  $this->end_controls_section();

	}


	/**
	 * Register style tab
	 * @return void
	 */
	protected function add_global_style_controls() {
		// add background
		$this->add_control(
			'background_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Background Color', 'aw3sm-eep' ),
				'separator' => "before",
				'selectors' => [
					'{{WRAPPER}} .child-navigation-container' => 'background: {{VALUE}};',
				],
			]
		);

		// add Padding
		$this->add_responsive_control(
			'padding',
			[
				'label'      => __( 'Link padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation-container' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		// add border Radius
		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => __( 'Border Radius', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Register style tab
	 * @return void
	 */
	protected function register_parent_style_controls() {

		// add link style
		$this->start_controls_tabs( 'text_colors' );

		$this->start_controls_tab(
			'link_colors_normal',
			[
				'label' => esc_html__( 'Normal', 'aw3sm-eep' ),
			]
		);

		$this->add_control(
			'link_color_normal',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation li a' => 'color: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'link_background_color_normal',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation.modern li a' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'link_border_normal',
				'label'    => esc_html__( 'Border', 'aw3sm-eep' ),
				'selector' => '{{WRAPPER}} .child-navigation.modern li a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_colors_hover',
			[
				'label' => esc_html__( 'Hover', 'aw3sm-eep' ),
			]
		);

		$this->add_control(
			'link_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link_background_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation.modern li a:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'link_border_hover',
				'label'    => esc_html__( 'Border', 'aw3sm-eep' ),
				'selector' => '{{WRAPPER}} .child-navigation.modern li a:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_colors_active',
			[
				'label' => esc_html__( 'Active', 'aw3sm-eep' ),
			]
		);

		$this->add_control(
			'link_color_active',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation li.current_page_item > a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link_background_color_active',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation li.current_page_item > a' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'link_border_active',
				'label'    => esc_html__( 'Border', 'aw3sm-eep' ),
				'selector' => '{{WRAPPER}} .child-navigation.modern li.current_page_item > a a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Border Radius
		$this->add_responsive_control(
			'first_link_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation.modern li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Link Padding
		$this->add_responsive_control(
			'link_padding',
			[
				'label'      => esc_html__( 'Link padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation.modern li a' => '--padding-top: {{TOP}}{{UNIT}}; --padding-right: {{RIGHT}}{{UNIT}}; --padding-bottom: {{BOTTOM}}{{UNIT}}; --padding-left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Link Margin
		$this->add_responsive_control(
			'link_margin',
			[
				'label'      => esc_html__( 'Link margin', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation.modern li a' => '--margin-top: {{TOP}}{{UNIT}}; --margin-right: {{RIGHT}}{{UNIT}}; --margin-bottom: {{BOTTOM}}{{UNIT}}; --margin-left: {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

	/**
	 * Register style tab
	 * @return void
	 */
	protected function register_children_style_controls() {
		// add link style
		$this->start_controls_tabs( 'text_children_colors' );

		$this->start_controls_tab(
			'link_colors_normal_children',
			[
				'label' => esc_html__( 'Normal', 'aw3sm-eep' ),
			]
		);

		$this->add_control(
			'link_color_normal_children',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation .children li a' => 'color: {{VALUE}};',
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_colors_hover_children',
			[
				'label' => esc_html__( 'Hover', 'aw3sm-eep' ),
			]
		);

		$this->add_control(
			'link_color_hover_children',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation .children li a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_colors_active_children',
			[
				'label' => esc_html__( 'Active', 'aw3sm-eep' ),
			]
		);

		$this->add_control(
			'link_color_active_children',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation ul.children li.current_page_item a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		// Background color

		$this->start_controls_tabs( 'background_colors_children' );

		$this->start_controls_tab(
			'link_background_colors_normal_children',
			[
				'label' => esc_html__( 'Normal', 'aw3sm-eep' ),
			]
		);

		$this->add_control(
			'link_background_color_normal_children',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation .children  li a' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_background_colors_hover_children',
			[
				'label' => esc_html__( 'Hover', 'aw3sm-eep' ),
			]
		);

		$this->add_control(
			'link_background_color_hover_children',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
				'selectors' => [
					'{{WRAPPER}} .child-navigation .children  li a:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'link_background_colors_active_children',
			[
				'label' => esc_html__( 'Active', 'aw3sm-eep' ),
			]
		);

		$this->add_control(
			'link_background_color_active_children',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Background Link Color', 'aw3sm-eep' ),
				'name'      => 'text_color',
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
				'label'      => esc_html__( 'Border Radius', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation .children li a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		// Link Padding
		$this->add_responsive_control(
			'link_padding_children',
			[
				'label'      => esc_html__( 'Link padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation .children li a' => '--padding-top: {{TOP}}{{UNIT}}; --padding-right: {{RIGHT}}{{UNIT}}; --padding-bottom: {{BOTTOM}}{{UNIT}}; --padding-left: {{LEFT}}{{UNIT}};',
				]
			]
		);

		// Link Margin
		$this->add_responsive_control(
			'link_margin_children',
			[
				'label'      => esc_html__( 'Link margin', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation .children li a' => '--margin-top: {{TOP}}{{UNIT}}; --margin-right: {{RIGHT}}{{UNIT}}; --margin-bottom: {{BOTTOM}}{{UNIT}}; --margin-left: {{LEFT}}{{UNIT}};',
				]
			]
		);

		// Sub Link Left Padding
		$this->add_responsive_control(
			'sub_link_left_padding_children',
			[
				'label'      => esc_html__( 'Sub Link Left Padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 10,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation .children' => '--sub-padding-left: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

	protected function render() {
		global $post;
		// Get the current page's ancestors either from existing value or by executing function.
		$post_ancestors = get_post_ancestors( $post );
		// Get the top page id.
		$top_page = $post_ancestors ? end( $post_ancestors ) : $post->ID;

		$source  = $this->get_settings( 'child_navigation_source' );
		$depth = $this->get_settings( 'child_navigation_depth' );

		if ( $source == 'all' ) {
			// show all pages
			echo $this->render_nav( $depth );
		} elseif ( $source == 'top_active_parent' ) {
			// show only top parent
			// $this->print_render_attribute_string( 'dropdown' );
			echo $this->render_nav( $depth, $top_page );
		} else {
			echo "Nothing to show";
		}

		return true;
	}

	/**
	 * Show all pages
	 *
	 * @param int $depth
	 * @param array $exclude
	 *
	 * @return string
	 */
	function show_all_pages( int $depth = 0, array $exclude = [] ) {
		$_finalContent = "<ul class='child-navigation modern'>";

		$_finalContent .= wp_list_pages( array(
			'title_li' => '',
			'depth'    => $depth,
			// 'sort_column' => $instance['sort_by'],
			'exclude'  => $exclude,
			'echo'     => false,
		) );

		$_finalContent .= '</ul>';

		return $_finalContent;
	}

	/**
	 * Show all child pages under parent (top page)
	 *
	 * @param int $depth Max depth
	 * @param int|null $top_page Top parent page
	 * @param array $exclude Array of excluded ids
	 *
	 * @return string
	 */
	function render_nav( int $depth = 0, int $top_page = null, array $exclude = [] ) {
		$title            = $this->get_settings_for_display( 'title' );
		$title_responsive = $this->get_settings_for_display( 'toggle_title' );
		$_finalContent    = "<nav class='child-navigation-container'>";
		// TODO: outside nav
		$_finalContent .= "<div class='menu-toggle'>";
		$_finalContent .= "<div class='title'>$title_responsive</div>";
		$_finalContent .= $this->menu_toggle_icon();
		$_finalContent .= "</div>";


		//set title to hide clas on breakpoint
		$_finalContent .= "<div class='child-navigation-inner'>";
		if ( $title ) {
			$_finalContent .= "<div class='title'>$title</div>";
		}
		$_finalContent .= "<ul class='child-navigation modern'>";

		$_finalContent .= wp_list_pages( array(
			'title_li' => '',
			'depth'    => $depth,
			'child_of' => $top_page,
			// 'sort_column' => $instance['sort_by'],
			'exclude'  => $exclude,
			'echo'     => false
		) );

		$_finalContent .= "</ul>";
		$_finalContent .= "</div>";
		$_finalContent .= "</nav>";

		return $_finalContent;
	}

	/**
	 * Create title control
	 *
	 * @return void
	 */
	protected function add_title_control(): void {
		// TODO: create dynamic tag for top parent (only on child-navigation title field)
		$this->add_control(
			'title',
			[
				'label'   => __( 'Menu title', 'aw3sm-eep' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true
				]
			],
		);

		$this->add_control(
			'toggle_title',
			[
				'label'     => __( 'Toggle title', 'aw3sm-eep' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Toggle menu', 'aw3sm-eep' ),
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
				'dynamic'   => [
					'active' => true
				]
			],
		);

		$this->add_icon_control();

		/*$this->add_control(
			'hide_title',
			[
				'label'        => __( 'Show navigation title on breakpoint', 'aw3sm-eep' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				// 'prefix_class' => 'title-',
				'label_on'     => __( 'Show', 'aw3sm-eep' ),
				'label_off'    => __( 'Hide', 'aw3sm-eep' ),
				// 'return_value' => 'hidden-breakpoint',
				'condition'    => [
					"dropdown_breakpoint!" => 'none',
				]
			]
		);*/

		// show on breakpoints
		$this->break_points();
	}

	/**
	 * Build menu toggle icon for render
	 *
	 * @return string
	 */
	protected function menu_toggle_icon() {
		$settings = $this->get_active_settings();
		$_content = '';
		$_content .= '<div class="menu-toggle-icon">';

		$toggle_icon_hover_animation = ! empty( $settings['toggle_icon_hover_animation'] )
			? ' elementor-animation-' . $settings['toggle_icon_hover_animation']
			: '';

		$open_class  = 'elementor-menu-toggle__icon--open' . $toggle_icon_hover_animation;
		$close_class = 'elementor-menu-toggle__icon--close' . $toggle_icon_hover_animation;

		$normal_icon = ! empty( $settings['toggle_icon_normal']['value'] )
			? $settings['toggle_icon_normal']
			: [
				'library' => 'eicons',
				'value'   => 'eicon-menu-bar',
			];

		if ( 'svg' === $settings['toggle_icon_normal']['library'] ) {
			$_content .= '<span class="' . esc_attr( $open_class ) . '">';
		}
		ob_start();
		Icons_Manager::render_icon(
			$normal_icon,
			[
				'aria-hidden' => 'true',
				'role'        => 'presentation',
				'class'       => $open_class,
			]
		);
		$_content .= ob_get_clean();

		if ( 'svg' === $settings['toggle_icon_normal']['library'] ) {
			$_content .= '</span>';
		}

		$active_icon = ! empty( $settings['toggle_icon_active']['value'] )
			? $settings['toggle_icon_active']
			: [
				'library' => 'eicons',
				'value'   => 'eicon-close',
			];

		if ( 'svg' === $settings['toggle_icon_active']['library'] ) {
			$_content .= '<span class="' . esc_attr( $close_class ) . '">';
		}

		ob_start();
		Icons_Manager::render_icon(
			$active_icon,
			[
				'aria-hidden' => 'true',
				'role'        => 'presentation',
				'class'       => $close_class,
			]
		);
		$_content .= ob_get_clean();


		if ( 'svg' === $settings['toggle_icon_active']['library'] ) {
			$_content .= '</span>';
		}
		$_content .= "</div>";

		return $_content;
	}

	protected function break_points() {
		$breakpoints          = Plugin::instance()->breakpoints->get_active_breakpoints();
		$dropdown_options     = [];
		$excluded_breakpoints = [
			'laptop',
			'widescreen',
		];

		foreach ( $breakpoints as $breakpoint_key => $breakpoint_instance ) {
			// Do not include laptop and widscreen in the options since this feature is for mobile devices.
			if ( in_array( $breakpoint_key, $excluded_breakpoints, true ) ) {
				continue;
			}

			$dropdown_options[ $breakpoint_key ] = sprintf(
			/* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value */
				esc_html__( '%1$s (%2$s %3$dpx)', 'aw3sm-eep' ),
				$breakpoint_instance->get_label(),
				'>',
				$breakpoint_instance->get_value()
			);
		}

		$dropdown_options['none'] = esc_html__( 'None', 'aw3sm-eep' );

		$this->add_control(
			'dropdown_breakpoint',
			[
				'label'        => esc_html__( 'Breakpoint', 'aw3sm-eep' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'tablet',
				'separator'    => 'after',
				'options'      => $dropdown_options,
				'prefix_class' => 'breakpoint-',
				// 'prefix_class' => 'elementor-nav-menu--dropdown-',
				'condition'    => [
					// 'layout!' => 'dropdown',
				],
			]
		);
	}

	/**
	 * Create icon control
	 * @return void
	 */
	protected function add_icon_control(): void {
		$this->add_control(
			'heading_toggle',
			[
				'type'      => Controls_Manager::HEADING,
				'label'     => esc_html__( 'Toggle breakpoint', 'aw3sm-eep' ),
				'separator' => 'before',
			]
		);
		$this->start_controls_tabs( 'nav_icon_options' );

		// normal
		$this->start_controls_tab( 'nav_icon_normal_options', [
			'label'     => esc_html__( 'Normal', 'aw3sm-eep' ),
			'condition' => [
				"dropdown_breakpoint!" => 'none',
			],
		] );
		$this->add_control(
			'toggle_icon_normal',
			[
				'label'            => esc_html__( 'Icon', 'aw3sm-eep' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => [
					'inline' => [
						'none' => [
							'label' => esc_html__( 'Default', 'aw3sm-eep' ),
							'icon'  => 'eicon-menu-bar',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended'      => [
					'fa-solid'   => [
						'plus-square',
						'plus',
						'plus-circle',
						'bars',
					],
					'fa-regular' => [
						'plus-square',
					],
				],
				'condition'        => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);
		$this->end_controls_tab();

		// hover
		$this->start_controls_tab( 'nav_icon_hover_options', [
			'label'     => esc_html__( 'Hover', 'aw3sm-eep' ),
			'condition' => [
				"dropdown_breakpoint!" => 'none',
			],
		] );
		$this->add_control(
			'toggle_icon_hover_animation',
			[
				'label'              => esc_html__( 'Hover Animation', 'aw3sm-eep' ),
				'type'               => Controls_Manager::HOVER_ANIMATION,
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);
		$this->end_controls_tab();
		// TODO: ANIMATION from nav-menu.php
		// active
		$this->start_controls_tab( 'nav_icon_active_options', [
			'label'     => esc_html__( 'Active', 'aw3sm-eep' ),
			'condition' => [
				"dropdown_breakpoint!" => 'none',
			],
		] );
		$this->add_control(
			'toggle_icon_active',
			[
				'label'            => esc_html__( 'Icon', 'aw3sm-eep' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => [
					'inline' => [
						'none' => [
							'label' => esc_html__( 'Default', 'aw3sm-eep' ),
							'icon'  => 'eicon-close',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended'      => [
					'fa-solid'   => [
						'window-close',
						'times-circle',
						'times',
						'minus-square',
						'minus-circle',
						'minus',
					],
					'fa-regular' => [
						'window-close',
						'times-circle',
						'minus-square',
					],
				],
				'condition'        => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	/**
	 * Create title style controls & section
	 *
	 * @return void
	 */
	protected function add_title_style_control(): void {
		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title settings', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		// add style title text
		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .child-navigation-inner .title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_background_style',
			[
				'label'     => esc_html__( 'Background', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .child-navigation-inner .title' => 'background: {{VALUE}};',
				],
			]
		);
		// Border Radius
		$this->add_responsive_control(
			'title_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation-inner .title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		// Link Padding
		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => esc_html__( 'Link padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .child-navigation-inner .title' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_spacer',
			[
				'label'     => esc_html__( 'Space', 'aw3sm-eep' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .child-navigation-inner .title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Create togle style controls & section
	 *
	 * @return void
	 */
	protected function add_toggle_style_control(): void {
		$this->start_controls_section(
			'toggle_style',
			[
				'label'     => __( 'Breakpoint toggle settings', 'aw3sm-eep' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);

		// add style title text
		$this->add_control(
			'toggle_title_color',
			[
				'label'     => __( 'Color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-toggle .title' => 'color: {{VALUE}};',
				],
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);
		$this->add_control(
			'toggle_background_style',
			[
				'label'     => esc_html__( 'Background', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-toggle' => 'background: {{VALUE}};',
				],
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);
		// Border Radius
		$this->add_control(
			'toggle_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .menu-toggle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);
		// Link Padding
		$this->add_control(
			'toggle_padding',
			[
				'label'      => esc_html__( 'Link padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .menu-toggle' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);
		$this->add_control(
			'toggle_spacer',
			[
				'label'     => esc_html__( 'Space', 'aw3sm-eep' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .menu-toggle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);
		$this->add_control(
			'toggle_size',
			[
				'label'     => esc_html__( 'Size', 'aw3sm-eep' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 15,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--nav-menu-icon-size: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);


		$this->add_control(
			'toggle_icon_padding',
			[
				'label'      => esc_html__( 'Padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .menu-toggle-icon' => 'padding-top: {{TOP}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_toggle_style' );

		$this->start_controls_tab(
			'tab_toggle_style_normal',
			[
				'label'     => esc_html__( 'Normal', 'aw3sm-eep' ),
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);

		$this->add_control(
			'toggle_color',
			[
				'label'     => esc_html__( 'Color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-toggle-icon'     => 'color: {{VALUE}}',
					// Harder selector to override text color control
					'{{WRAPPER}} .menu-toggle-icon svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);

		$this->add_control(
			'toggle_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-toggle-icon' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_toggle_style_hover',
			[
				'label'     => esc_html__( 'Hover', 'aw3sm-eep' ),
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);

		$this->add_control(
			'toggle_color_hover',
			[
				'label'     => esc_html__( 'Color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-toggle-icon:hover'     => 'color: {{VALUE}}',
					// Harder selector to override text color control
					'{{WRAPPER}} .menu-toggle-icon:hover svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);

		$this->add_control(
			'toggle_background_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .menu-toggle-icon:hover' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					"dropdown_breakpoint!" => 'none',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}
}