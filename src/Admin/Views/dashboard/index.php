<!-- Our admin page content should all be inside .wrap -->
<div class="wrap">
    <!-- Print the page title -->
    <h1><?php echo __( get_admin_page_title() . "<sup><small>$version</small></sup>" ); ?></h1>
    <!-- Here are our tabs -->
    <nav class="nav-tab-wrapper">
        <a href="?page=aw3sm-eep-dashboard" class="nav-tab <?php if ( $tab === null ): ?>nav-tab-active<?php endif; ?>">
            Widgety
        </a>
        <!--<a href="?page=aw3sm-eep-dashboard&tab=settings"
           class="nav-tab <?php if ( $tab === 'settings' ): ?>nav-tab-active<?php endif; ?>">Nastavení</a>-->
        <a href="?page=aw3sm-eep-dashboard&tab=versions"
           class="nav-tab <?php if ( $tab === 'versions' ): ?>nav-tab-active<?php endif; ?>">Kontrola verzí</a>
        <a href="?page=aw3sm-eep-dashboard&tab=credits"
           class="nav-tab <?php if ( $tab === 'credits' ): ?>nav-tab-active<?php endif; ?>">Credits</a>
    </nav>

    <div class="tab-content">
		<?php switch ( $tab ) :
			case 'credits':
				require_once __DIR__ . "/credits.php";
				break;
			case 'versions':
                require_once __DIR__ . "/versions.php";
				break;
			default:
				require_once __DIR__ . "/widgets.php";
				break;
		endswitch; ?>
    </div>
</div>
