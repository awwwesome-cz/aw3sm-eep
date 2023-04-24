<?php

// Register admin pages

use AwwwesomeEEP\Admin\Controllers\Dashboard;
use AwwwesomeEEP\Includes\Core\Admin_Menu;

Admin_Menu::add_admin_page(
	'Elementor Extension Pack',
	'EEP',
	'dashboard',
	'manage_options', // TODO: pořešit, nevím jaké lze použít
	[ new Dashboard(), 'index' ]
);