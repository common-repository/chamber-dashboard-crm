<?php

// Require options stuff
require_once( plugin_dir_path( __FILE__ ) . 'options.php' );

// Require views
require_once( plugin_dir_path( __FILE__ ) . 'views.php' );

foreach ( glob( plugin_dir_path( __FILE__ ) . "blocks/*.php" ) as $file ) {
    require_once $file;
}
?>