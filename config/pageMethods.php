<?php

use FabianMichael\Taxonomies\TermsCollection;
use Kirby\Cms\Page;

/**
 * Custom methods for the `Kirby\Cms\Page` class.
 */
return [
    /**
     * Returns the terms collection for a given taxonomy.
     */
    'taxonomy' => function (string $name): TermsCollection {
        /** @var Page $this */
        return $this->content()->get($name)->toTerms();
    },
];
