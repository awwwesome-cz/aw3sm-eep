<?php

namespace AwwwesomeEEP\Modules\TablePress\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;

class Tablepress_Table extends Widget_Base {

	public function get_name() {
		return 'tablepress-table';
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
		return __( 'TablePress Table', 'aw3sm-eep' );
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
	public function get_icon() {
		return 'eicon-table';
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
	public function get_categories() {
		return [
			\Elementor\Modules\DynamicTags\Module::BASE_GROUP,
		];
	}

	/**
	 * Get all existing tables in TablePress
	 *
	 * @return array [[id:..., name:..]]
	 */
	protected function get_tables() {
		$tables    = [];
		$table_ids = \TablePress::$model_table->load_all();
		foreach ( $table_ids as $table_id ) {
			$table_data = \TablePress::$model_table->load( $table_id );
			$table_name = $table_data['name'];
			$tables[]   = [
				'id'   => $table_id,
				'name' => $table_name
			];
		}

		return $tables;
	}

	protected function register_controls() {
		do_action( 'aw3sm_eep/widgets/tablepress/table/before_section_start', $this );

		$this->start_controls_section(
			'section_value',
			[
				'label' => __( 'TablePress', 'aw3sm-eep' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		do_action( 'aw3sm_eep/widgets/tablepress/table/section_start', $this );

		// get tables
		$_tables = $this->get_tables();
		$tables  = [
			'' => __( 'Select Table', 'aw3sm-eep' )
		];
		foreach ( $_tables as $_table ) {
			$tables[ $_table['id'] ] = $_table['name'];
		}

		$this->add_control(
			'table_id',
			[
				'label'   => __( 'Table', 'aw3sm-eep' ), // 1 level of ACF group
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $tables,
			],
		);

		do_action( 'aw3sm_eep/widgets/tablepress/table/before_section_end', $this );

		$this->end_controls_section();

		do_action( 'aw3sm_eep/widgets/tablepress/table/after_section_end', $this );

		// styles
		do_action( 'aw3sm_eep/widgets/tablepress/style/before_section_start', $this );
		$this->register_style_controls();
		do_action( 'aw3sm_eep/widgets/tablepress/style/after_section_end', $this );

	}

	protected function render() {
		$attrs = [
			'id'                         => $this->get_settings( 'table_id' ),
			'table_head'                 => $this->get_settings( 'table_head' ) == 'yes',
			'table_foot'                 => $this->get_settings( 'table_foot' ) == 'yes',
			'row_hover'                  => $this->get_settings( 'row_hover' ) == 'yes',
			'alternating_row_colors'     => $this->get_settings( 'alternating_row_colors' ) == 'yes',
			'print_name'                 => $this->get_settings( 'print_name' ) == 'yes',
			'print_name_position'        => $this->get_settings( 'print_name_position' ),
			'print_description'          => $this->get_settings( 'print_description' ) == 'yes',
			'print_description_position' => $this->get_settings( 'print_description_position' ),
		];
		echo tablepress_get_table( $attrs );
	}


	/**
	 * Register styles
	 *
	 * @return void
	 */
	protected function register_style_controls() {
		// Global table
		$this->table_style();

		// header
		$this->style_header();

		// footer
		$this->style_footer();

		// Rows
		$this->style_rows();

		// info texts
		$this->style_info_text();

		/*
		$this->add_control(
			'use_datatables',
			[
				'separator' => 'before',
				'label'     => __( 'Use datatables', 'aw3sm-eep' ), // 1 level of ACF group
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => __( 'On', 'aw3sm-eep' ),
				'label_off' => __( 'Off', 'aw3sm-eep' ),
			],
		);
		// TODO: dokončit atribut dattables Available in PRO version
		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
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
				'label'     => __( 'Background color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
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
				'label' => __( 'Spacing', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'sale_padding',
			[
				'label'      => __( 'Padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .aw3sm-woo-onsale-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator'  => 'after',
			]
		);


		*/

	}

	/**
	 * Table settings style
	 *
	 * @return void
	 */
	protected function table_style() {
		$this->start_controls_section(
			'table',
			[
				'label' => __( 'Table border', 'elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'table_border',
				'selector' => '{{WRAPPER}} table td, {{WRAPPER}} table th',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label'      => __( 'Border Radius', 'elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} table'                                               => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};
					border-collapse: inherit; 
					overflow: hidden;',

					// top, header
					'{{WRAPPER}} table tr:first-of-type th:first-child'               => 'border-top-left-radius: {{TOP}}{{UNIT}}',
					'{{WRAPPER}} table tr:first-of-type td:first-child'               => 'border-top-left-radius: {{TOP}}{{UNIT}}',
					'{{WRAPPER}} table thead tr:first-of-type th:first-child'         => 'border-bottom-left-radius: 0',
					'{{WRAPPER}} table thead tr:first-of-type td:first-child'         => 'border-bottom-left-radius: 0',
					'{{WRAPPER}} table thead + tbody tr:first-of-type td:first-child' => 'border-top-left-radius: 0',
					'{{WRAPPER}} table tbody + tfoot tr:first-of-type th:first-child' => 'border-top-left-radius: 0',

					'{{WRAPPER}} table tr:first-of-type th:last-child'                  => 'border-top-right-radius: {{RIGHT}}{{UNIT}}',
					'{{WRAPPER}} table tr:first-of-type td:last-child'                  => 'border-top-right-radius: {{RIGHT}}{{UNIT}}',
					'{{WRAPPER}} table thead tr:first-of-type th:last-child'            => 'border-bottom-right-radius: 0',
					'{{WRAPPER}} table thead tr:first-of-type td:last-child'            => 'border-bottom-right-radius: 0',
					'{{WRAPPER}} table thead + tbody tr:first-of-type td:last-child'    => 'border-top-right-radius: 0',
					'{{WRAPPER}} table tbody + tfoot tr:first-of-type th:last-child'    => 'border-top-right-radius: 0',

					// bottom, footer
					'{{WRAPPER}} table tr:last-of-type th:first-child'                  => 'border-bottom-left-radius: {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} table tr:last-of-type td:first-child'                  => 'border-bottom-left-radius: {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} table:not(tfoot) tbody tr:last-of-type td:first-child' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}}',
					'{{WRAPPER}} table:has(tfoot) tbody tr:last-of-type td:first-child' => 'border-bottom-left-radius: 0',

					'{{WRAPPER}} table tr:last-of-type th:last-child'                  => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}',
					'{{WRAPPER}} table tr:last-of-type td:last-child'                  => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}',
					'{{WRAPPER}} table:not(tfoot) tbody tr:last-of-type td:last-child' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}}',
					'{{WRAPPER}} table:has(tfoot) tbody tr:last-of-type td:last-child' => 'border-bottom-right-radius: 0',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'selector' => '{{WRAPPER}} table',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @return void
	 */
	protected function style_header(): void {
		$this->start_controls_section(
			'header_template',
			[
				'label' => __( 'Header', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'table_head',
			[
				'label'     => __( 'First line is header', 'aw3sm-eep' ), // 1 level of ACF group
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'On', 'aw3sm-eep' ),
				'label_off' => __( 'Off', 'aw3sm-eep' ),
			],
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'header_typography',
				'selector'  => '{{WRAPPER}} thead th',
				'condition' => [
					'table_head' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_head_text_color',
			[
				'label'     => __( 'Text color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} thead th' => 'color: {{VALUE}};',
				],
				'condition' => [
					'table_head' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_head_background_color',
			[
				'label'     => __( 'Background color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} thead th' => 'background: {{VALUE}};',
				],
				'condition' => [
					'table_head' => 'yes',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'table_head_border',
				'selector'  => '{{WRAPPER}} table thead th',
				'condition' => [
					'table_head' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_head_padding',
			[
				'label'      => __( 'Padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator'  => 'before',
				'condition'  => [
					'table_head' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @return void
	 */
	protected function style_footer(): void {
		$this->start_controls_section(
			'footer_template',
			[
				'label' => __( 'Footer', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'table_foot',
			[
				'label'     => __( 'Last line is footer', 'aw3sm-eep' ), // 1 level of ACF group
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => __( 'On', 'aw3sm-eep' ),
				'label_off' => __( 'Off', 'aw3sm-eep' ),
			],
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'footer_typography',
				'selector'  => '{{WRAPPER}} tfoot th',
				'condition' => [
					'table_foot' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_foot_text_color',
			[
				'label'     => __( 'Text color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tfoot th' => 'color: {{VALUE}};',
				],
				'condition' => [
					'table_foot' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_foot_background_color',
			[
				'label'     => __( 'Background color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tfoot th' => 'background: {{VALUE}};',
				],
				'condition' => [
					'table_foot' => 'yes',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'table_foot_border',
				'selector'  => '{{WRAPPER}} table tfoot th',
				'condition' => [
					'table_foot' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_foot_padding',
			[
				'label'      => __( 'Padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} tfoot th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator'  => 'before',
				'condition'  => [
					'table_foot' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @return void
	 */
	protected function style_rows(): void {
		$this->start_controls_section(
			'rows_template',
			[
				'label' => __( 'Rows', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'row_hover',
			[
				'label'     => __( 'Hover', 'aw3sm-eep' ), // 1 level of ACF group
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'On', 'aw3sm-eep' ),
				'label_off' => __( 'Off', 'aw3sm-eep' ),
			],
		);
		$this->start_controls_tabs( 'row_tabs' );

		$this->start_controls_tab(
			'row_tab',
			[
				'label' => esc_html__( 'Normal', 'aw3sm-eep' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'row_typography',
				'selector' => '{{WRAPPER}} tbody tr td',
			]
		);

		$this->add_control(
			'row_text_color',
			[
				'label'     => __( 'Text color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tbody tr td' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'row_background_color',
			[
				'label'     => __( 'Background color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tbody tr td' => 'background: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// hover tab
		$this->start_controls_tab(
			'row_hover_tab',
			[
				'label'     => esc_html__( 'Hover', 'aw3sm-eep' ),
				'condition' => [
					'row_hover' => 'yes',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'row_typography_hover',
				'selector' => '{{WRAPPER}} tbody.row-hover tr:hover td',
			]
		);

		$this->add_control(
			'row_text_color_hover',
			[
				'label'     => __( 'Text color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tbody.row-hover tr:hover td'     => 'color: {{VALUE}};',
					'{{WRAPPER}} tbody.row-hover tr.odd:hover td' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'row_background_color_hover',
			[
				'label'     => __( 'Background color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tbody.row-hover tr:hover td'     => 'background: {{VALUE}};',
					'{{WRAPPER}} tbody.row-hover tr.odd:hover td' => 'background: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'table_row_border',
				'selector'  => '{{WRAPPER}} table tbody td',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'row_padding',
			[
				'label'      => __( 'Padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} tbody tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'alternating_row_colors',
			[
				'label'     => __( 'Zebra striping (ODD)', 'aw3sm-eep' ), // 1 level of ACF group
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_on'  => __( 'On', 'aw3sm-eep' ),
				'label_off' => __( 'Off', 'aw3sm-eep' ),
			],
		);


		$this->add_control(
			'row_text_color_odd',
			[
				'label'     => __( 'Text color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tbody tr.odd td' => 'color: {{VALUE}};',
				],
				'condition' => [
					'alternating_row_colors' => 'yes',
				]
			]
		);

		$this->add_control(
			'row_background_color_odd',
			[
				'label'     => __( 'Background color', 'aw3sm-eep' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tbody tr.odd td' => 'background: {{VALUE}};',
				],
				'condition' => [
					'alternating_row_colors' => 'yes',
				]
			]
		);

		$this->end_controls_section();
	}

	/**
	 * @return void
	 */
	protected function style_info_text(): void {
		$this->start_controls_section(
			'info_template',
			[
				'label' => __( 'Information texts', 'aw3sm-eep' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'print_name',
			[
				'separator' => 'before',
				'label'     => __( 'Print name of table', 'aw3sm-eep' ), // 1 level of ACF group
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => __( 'On', 'aw3sm-eep' ),
				'label_off' => __( 'Off', 'aw3sm-eep' ),
			],
		);

		$this->add_control(
			'print_name_position',
			[
				'label'     => __( 'Name position', 'aw3sm-eep' ), // 1 level of ACF group
				'type'      => Controls_Manager::SELECT,
				'default'   => 'above',
				'options'   => [
					'above' => __( 'Above', 'aw3sm-eep' ),
					'below' => __( 'Below', 'aw3sm-eep' )
				],
				'condition' => [
					'print_name' => 'yes',
				],
			],
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'print_name_typography',
				'selector'  => '{{WRAPPER}} .tablepress-table-name',
				'condition' => [
					'print_name' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'print_name_align',
			[
				'label'     => esc_html__( 'Text Alignment', 'aw3sm-eep' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'aw3sm-eep' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'aw3sm-eep' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'aw3sm-eep' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tablepress-table-name' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'print_name' => 'yes',
				],
			]
		);

		$this->add_control(
			'print_description',
			[
				'separator' => 'before',
				'label'     => __( 'Print description of table', 'aw3sm-eep' ), // 1 level of ACF group
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'no',
				'label_on'  => __( 'On', 'aw3sm-eep' ),
				'label_off' => __( 'Off', 'aw3sm-eep' ),
			],
		);

		$this->add_control(
			'print_description_position',
			[
				'label'     => __( 'Description position', 'aw3sm-eep' ), // 1 level of ACF group
				'type'      => Controls_Manager::SELECT,
				'default'   => 'above',
				'options'   => [
					'above' => __( 'Above', 'aw3sm-eep' ),
					'below' => __( 'Below', 'aw3sm-eep' )
				],
				'condition' => [
					'print_description' => 'yes',
				],
			],
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'print_description_typography',
				'selector'  => '{{WRAPPER}} .tablepress-table-description',
				'condition' => [
					'print_description' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'print_description_align',
			[
				'label'     => esc_html__( 'Text Alignment', 'aw3sm-eep' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'aw3sm-eep' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'aw3sm-eep' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'aw3sm-eep' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tablepress-table-description' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'print_description' => 'yes',
				],
			]
		);
		$this->add_control(
			'print_description_padding',
			[
				'label'      => __( 'Padding', 'aw3sm-eep' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .tablepress-table-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'condition'  => [
					'print_description' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}
}