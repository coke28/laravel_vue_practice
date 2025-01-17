<?php
return array(
    // Assets
    'assets' => array(
        'favicon' => 'media/logos/favicon.ico',
        'fonts'   => array(
            'google' => array(
                'Roboto:300,400,500,600,700',
            ),
        ),
        'css'     => array(
            'plugins/global/plugins.bundle.css',
            'plugins/global/plugins-custom.bundle.css',
            'css/style.bundle.css',
        ),
        'js'      => array(
            'plugins/global/plugins.bundle.js',
            'js/scripts.bundle.js',
            'js/custom/widgets.js',
        ),
    ),

    // Layout
    'layout' => array(
        // Main
        'main'      => array(
            'type'              => 'default', // Set layout type: default|blank|none
            'dark-mode-enabled' => false, // Enable optioanl dark mode mode
            'primary-color'     => '#04C8C8',
        ),

        // Docs
        'docs'      => array(
            'logo-path'  => 'logos/logo-1-dark.svg',
            'logo-class' => 'h-15px',
        ),

        // Loader
        'loader'    => array(
            'display' => false,
            'type'    => 'default' // Set default|spinner-message|spinner-logo to hide or show page loader
        ),

        // Scrolltop
        'scrolltop' => array(
            'display' => true // Enable scrolltop
        ),

        // Header
        'header'    => array(
            'display' => true, // Set true|false to show or hide Header
            'width'   => 'fluid', // Set fixed|fluid to change width type
            'fixed'   => array(
                'desktop'           => true,  // Set true|false to set fixed Header for desktop mode
                'tablet-and-mobile' => true // Set true|false to set fixed Header for tablet and mobile modes
            ),
        ),

        // Aside
        'aside'     => array(
            'menu'              => 'main', // Set main|documentation menu for Aside
            'fixed'             => true,  // Set true|false to enabled or disable Fixed Aside mode
            'secondary-display' => true, // Set true|false to show or hide Aside Secondary
            'minimized'         => false, // Set aside default minimized
            'minimize'          => true, // Enable aside minimization,
            'menu-icon'         => 'svg' // Menu icon type(svg|font)
        ),

        // Content
        'content'   => array(
            'width'  => 'fluid', // Set fixed|fluid to change width type
            'layout' => 'default'  // Set content type
        ),

        // Footer
        'footer'    => array(
            'width' => 'fluid' // Set fixed|fluid to change width type
        ),
    ),
);
