<?php

use FabianMichael\Taxonomies\Models\Taxonomies as TaxonomiesPage;
use FabianMichael\Taxonomies\Models\Taxonomy;
use FabianMichael\Taxonomies\Models\Term;
use FabianMichael\Taxonomies\Taxonomies;
use Kirby\Cms\App;

@include_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/helpers.php';

App::plugin('fabianmichael/taxonomies', [
    'options' => [
        'page' => 'taxonomies',
    ],

    'areas' => [
        'site' => fn (App $kirby) => [
            'current' => str_contains(App::instance()->request()->path()->toString(), '/site'),
        ],
        'taxonomies' => fn (App $kirby) => [
            'label' => t('taxonomies.menu.label'),
            'icon' => 'tag',
            'menu' => true,
            'link' => Taxonomies::taxonomiesPanelPath(),
            'current' => Taxonomies::taxonomiesPanelCurrent(),
        ],
    ],
    'blueprints' => [
        'pages/taxonomies' => require __DIR__ . '/blueprints/pages/taxonomies.php',
        'sections/terms' => __DIR__ . '/blueprints/sections/terms.yml',
        'taxonomies/default' => __DIR__ . '/blueprints/taxonomies/default.yml',
        // fields and other additional blueprints are auto-generated
        // from the taxonomy definitions in the `system.loadPlugins:after` hook.
    ],
    'fieldMethods' => require __DIR__ . '/config/fieldMethods.php',
    'hooks' => require __DIR__ . '/config/hooks.php',
    'pageMethods' => require __DIR__ . '/config/pageMethods.php',
    'pagesMethods' => require __DIR__ . '/config/pagesMethods.php',
    'pageModels' => [
        'taxonomies' => TaxonomiesPage::class,
        'taxonomy' => Taxonomy::class,
        'term' => Term::class,
    ],
    'siteMethods' => require __DIR__ . '/config/siteMethods.php',
    'translations' => [
        'en' => require __DIR__ . '/translations/en.php',
        'de' => require __DIR__ . '/translations/de.php',
    ],
]);
