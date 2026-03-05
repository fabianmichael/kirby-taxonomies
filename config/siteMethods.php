<?php

/**
 * Custom methods for the `Kirby\Cms\Site` class.
 */

use FabianMichael\Taxonomies\Models\Taxonomies;
use FabianMichael\Taxonomies\Models\Taxonomy;

return [
    /**
     * Returns the taxonomies collection.
     */
    'taxonomies' => function (): Taxonomies {
        return taxonomies();
    },

	/**
	 * Returns a specific taxonomy page.
	 */
	'taxonomy' => function (string $taxonomy): Taxonomy {
		return taxonomies()->taxonomy($taxonomy);
	},
];
