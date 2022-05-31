<?php

namespace AwwwesomeEEP\Modules\ACF\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Repeater extends Widget_Base {

	public function get_name() {
		return 'acf-repeater';
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
		return __( 'ACF Repeater', 'aw3sm-eep' );
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

	protected function register_controls() {

		// init action
		do_action( 'aw3sm_eep/widgets/acf/repeater/template/before_section_start', $this );

		$this->start_controls_section(
			'section_template',
			[
				'label' => __( 'Text Repeat', 'aw3sm-eep' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		// init action
		do_action( 'aw3sm_eep/widgets/acf/repeater/template/section_start', $this );

		$acf_first_group_level      = [];
		$acf_second_group_level     = [];
		$acf_first_group_level['']  = __( 'None', 'aw3sm-eep' );
		$acf_second_group_level[''] = __( 'None', 'aw3sm-eep' );

		// get all group acf_first_group_level
		$fields = get_fields();
		if ( ! $fields ) {
			$this->add_control(
				'acf_error_description',
				[
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => __( 'This post does not contains any ACF fields for show!', 'aw3sm-eep' ),
					'separator'       => 'none',
					'content_classes' => 'elementor-descriptor',
				]
			);
			return;
		}
		foreach ($fields as $field_key => $field ) {
			if ( have_rows( $field_key ) ) {
				$obj = get_field_object( $field_key );

				if ( $obj['type'] === 'group' ) {

					$acf_first_group_level[ $field_key ] = $obj['label'];

					// get all sub groups
					foreach ( $obj['value'] as $sub_field_key => $sub_field ) {
						$subObj = get_sub_field_object( $sub_field_key );
						if ( $subObj['type'] === 'select' ) {
							$acf_second_group_level[ $sub_field_key ] = $subObj['label'];
						}
					}
				}
			}
		}

		$this->add_control(
			'acf_group',
			[
				'label'   => esc_html__( 'ACF Group', 'aw3sm-eep' ), // 1 level of ACF group
				'type'    => Controls_Manager::SELECT2,
				/*'dynamic'     => [
					'default' => ProPlugin::elementor()->dynamic_tags->tag_data_to_tag_text( null, \AwwwesomeEEP\Modules\ACF\Tags\Repeater::TagName ),
					'active'  => true,
				],*/
				'default' => '',
				'options' => $acf_first_group_level,
			],
		);


		$this->add_control(
			'acf_group_description',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'First level ACF Group Items must have "group" type', 'aw3sm-eep' ),
				'separator'       => 'none',
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->add_control(
			'acf_sub_group',
			[
				'label'   => esc_html__( 'ACF Group Items', 'aw3sm-eep' ), // 1 level of ACF group
				'type'    => Controls_Manager::SELECT2,
				'default' => '',
				'options' => $acf_second_group_level,
			],
		);

		$this->add_control(
			'acf_sub_group_description',
			[
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => __( 'ACF Group Items must have "select" type', 'aw3sm-eep' ),
				'separator'       => 'none',
				'content_classes' => 'elementor-descriptor',
			]
		);

		// init action
		do_action( 'aw3sm_eep/widgets/acf/repeater/template/before_section_end', $this );

		$this->end_controls_section();

		// init action
		do_action( 'aw3sm_eep/widgets/acf/repeater/template/after_section_end', $this );

		$this->start_controls_section(
			'style_template',
			[
				'label' => __( 'Display', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->register_style_controls();

		$this->end_controls_section();

	}

	protected function register_style_controls() {


		$this->add_control(
			'span_color',
			[
				'label'     => esc_html__( 'Text color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .acf-repeater-list span' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'heading_typography',
				'global'   => [
					// 'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .acf-repeater-list span',
			]
		);

		$this->add_control(
			'spacing',
			[
				'label'      => esc_html__( 'Spacing', 'aw3sm-eep' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max'  => 5,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .acf-repeater-list' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'default'    => [
					'size' => 20,
				],
			]
		);

		//
		// Přidám responzivní pro zarovnání slopců
		$this->add_responsive_control(
			'scb_section_horizontal_align',
			[
				'label'     => __( 'Horizontal align', 'aw3sm-eep' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''              => __( 'Default', 'aw3sm-eep' ),
					'flex-start'    => __( 'Start', 'aw3sm-eep' ),
					'flex-end'      => __( 'End', 'aw3sm-eep' ),
					'center'        => __( 'Center', 'aw3sm-eep' ),
					'space-between' => __( 'Space Between', 'aw3sm-eep' ),
					'space-around'  => __( 'Space Around', 'aw3sm-eep' ),
					'space-evenly'  => __( 'Space Evenly', 'aw3sm-eep' ),
				],
				'selectors' => [ // Zde se předají data do CSS
					'{{WRAPPER}} .acf-repeater-list' => 'justify-content: {{VALUE}};',
				],
			]
		);
	}

	protected function render() {
		// get settings
		$acf_group     = $this->get_settings( 'acf_group' );
		$acf_sub_group = $this->get_settings( 'acf_sub_group' );

		if ( '' === $acf_group || '' === $acf_sub_group ) {
			return;
		}

		$_finalContent = '';

		if ( have_rows( $acf_group ) ) {
			$_finalContent .= "<div class=\"acf-repeater-list\" style='display: flex'>";
			while ( have_rows( $acf_group ) ) {
				the_row();
				$subField       = get_sub_field_object( trim( $acf_sub_group ) );
				$subFieldValues = get_sub_field( trim( $acf_sub_group ) );
				foreach ( $subFieldValues as $value ) {
					$name = $subField['choices'][ $value ]; // Get name

					$_finalContent .= "<div class=\"acf-repeater-item\">";
					$_finalContent .= "<span>$name</span>";
					$_finalContent .= "</div>";
				}

			}
			$_finalContent .= "</div>";
		} else {
			$_finalContent = "$acf_group does not have any rows";
		}

		echo $_finalContent;
	}
}