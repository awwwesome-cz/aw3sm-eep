<?php

namespace AwwwesomeEEP;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

/**
 * Adding widget border style for register in register_controls() in widget
 */
trait Widget_Border {

	/**
	 * Adding default border style
	 *
	 * @return void
	 */
	function add_border_style_controls(array $selector) {
		$this->start_controls_section(
			'border',
			[
				'label' => esc_html__( 'Border', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_border' );

		$this->start_controls_tab(
			'tab_border_normal',
			[
				'label' => esc_html__( 'Normal', 'aw3sm-eep' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'border',
				'selector' => $selector['border'],
			]
		);


		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => $selector['border_radius'],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => $selector['box_shadow'],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_border_hover',
			[
				'label' => esc_html__( 'Hover', 'aw3sm-eep' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'border_hover',
				'selector' => $selector['border_hover'],
			]
		);


		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label'      => esc_html__( 'Border Radius', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => $selector['border_radius_hover'],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow_hover',
				'selector' => $selector['box_shadow_hover'],
			]
		);

		$this->add_control(
			'border_hover_transition',
			[
				'label'     => esc_html__( 'Transition Duration', 'aw3sm-eep' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default'   => [
					'size' => 0.3,
				],
				'range'     => [
					'px' => [
						'max'  => 3,
						'step' => 0.1,
					],
				],
				/*'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'background_background',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'border_border',
							'operator' => '!==',
							'value' => '',
						],
					]
				],*/
				'selectors' => $selector['border_hover_transition'],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

}