<?php

// Register admin pages

use AwwwesomeEEP\Admin\Controllers\Dashboard;
use AwwwesomeEEP\Includes\Core\Admin_Menu;

// ADD Main Awwwesome Menu Page
Admin_Menu::add_admin_page(
	'Awwwesome',
	'Awwwesome',
	'awwwesome',
	'manage_options' // TODO: pořešit, nevím jaké lze použít
);

Admin_Menu::add_sub_page(
	'Elementor Extension Pack',
	'EEP',
	'dashboard',
	'awwwesome',
	'manage_options', // TODO: pořešit, nevím jaké lze použít
	[ new Dashboard(), 'index' ]
);