<?php

use FabianMichael\Taxonomies\Taxonomies;

return [
    'system.loadPlugins:after' => fn () => Taxonomies::install(),
];
