<?php

namespace FabianMichael\Taxonomies;

use Kirby\Cms\PageBlueprint;
use Kirby\Toolkit\Str;

/**
 * A custom blueprint for taxonomy pages.
 */
class TaxonomyBlueprint extends PageBlueprint
{
    /**
     * Loads the blueprint for a taxonomy page and supports unsupported properties.
     */
    public static function load(string $name): array
    {
        $props = parent::load($name);

        // always extend the default taxonomy blueprint
        $props['extends'] = 'taxonomies/default';

        // taxonomy blueprints must not define any of these
        unset($props['sections']);
        unset($props['columns']);
        unset($props['tabs']);
        unset($props['preset']);

        // move fields to termFields, so we can make it available
        // for the term blueprints but prevent the fields from
        // appearing in the taxonomy blueprint
        $props['termFields'] = $props['fields'] ?? null;
        unset($props['fields']);

		$props['sections'] = [
			'terms' => [
				'extends' => 'sections/terms',
				'templates' => [Str::after($name, '/') . '-term'],
			],
		];

		return $props;
	}
}
