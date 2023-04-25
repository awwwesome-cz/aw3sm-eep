<?php

// Register admin pages

use AwwwesomeEEP\Admin\Controllers\Dashboard;
use AwwwesomeEEP\Includes\Core\Menu;

// ADD Main Awwwesome Menu Page
Menu::add_admin_page(
	'Awwwesome',
	'Awwwesome',
	'awwwesome',
	'manage_options' // TODO: pořešit, nevím jaké lze použít
);

Menu::add_sub_page(
	'Elementor Extension Pack',
	'EEP',
	'dashboard',
	'awwwesome',
	'manage_options', // TODO: pořešit, nevím jaké lze použít
	[ new Dashboard(), 'index' ]
);