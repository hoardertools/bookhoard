<?php

$menu = [


    'menu' => [[
		'icon' => 'fa fa-sitemap',
		'title' => 'Home',
		'url' => '/',
		'route-name' => 'home'
	],
        [
            'icon' => 'fa fa-notes-medical',
            'title' => 'Log Center',
            'url' => '/logCenter',
            'route-name' => 'logCenter'
        ]
        ,
        [
            'icon' => 'fa fa-search',
            'title' => 'Search',
            'url' => '/search',
            'route-name' => 'search'
        ]
    ]
];




return $menu;