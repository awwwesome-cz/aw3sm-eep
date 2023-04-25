<?php

// Register admin pages

use AwwwesomeEEP\Admin\Controllers\Dashboard;
use AwwwesomeEEP\Includes\Core\Menu;

// ADD Main Awwwesome Menu Page
Menu::add_admin_page(
	'Awwwesome',
	'Awwwesome',
	'awwwesome',
	'manage_options', // TODO: pořešit, nevím jaké lze použít,
	'',
	'data:image/svg+xml;base64,' . base64_encode( '<svg width="84" height="78" viewBox="0 0 84 78" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path fill-rule="evenodd" clip-rule="evenodd" d="M83.3304 66.0845L55.2767 17.494L38.097 47.25H48.9048L55.4607 35.8995L67.7924 57.2499H50V57.25H32.3235L27.223 66.0845H83.3304Z" fill="black"/>
				<path fill-rule="evenodd" clip-rule="evenodd" d="M0.796387 77L44.786 1.00006L50.7271 11.3109L12.7055 77H0.796387Z" fill="black"/>
				</svg>
				' )

);

Menu::add_sub_page(
	'Elementor Extension Pack',
	'EEP',
	'dashboard',
	'awwwesome',
	'manage_options', // TODO: pořešit, nevím jaké lze použít
	[ new Dashboard(), 'index' ]
);