<?php

use FabianMichael\Taxonomies\Models\Taxonomies;
use FabianMichael\Taxonomies\Models\Taxonomy;
use FabianMichael\Taxonomies\Taxonomies as Plugin;

if (!function_exists('taxonomies')) {
    /**
     * Returns the main Taxonomies page instance.
     */
    function taxonomies(): Taxonomies
    {
        return Plugin::page();
    }
}

if (!function_exists('taxonomy')) {
    /**
     * Returns the taxonomy page instance for a given taxonomy or name.
     */
    function taxonomy(Taxonomy|string $taxonomyOrName): ?Taxonomy
    {
        if ($taxonomyOrName instanceof Taxonomy) {
            return $taxonomyOrName;
        }

        return taxonomies()->find($taxonomyOrName);
    }
}
