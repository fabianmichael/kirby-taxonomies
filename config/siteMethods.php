<?php

/**
 * Custom methods for the `Kirby\Cms\Site` class.
 */

use FabianMichael\Taxonomies\Models\Taxonomies;

return [
    /**
     * Returns the taxonomies collection.
     */
    'taxonomies' => function (): Taxonomies {
        return taxonomies();
    },
];
