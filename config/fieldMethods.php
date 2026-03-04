<?php

use FabianMichael\Taxonomies\TermsCollection;
use Kirby\Content\Field;

/**
 * Custom methods for the `Kirby\Content\Field` class.
 */
return [
    /**
     * Returns the terms collection for a given taxonomy field.
     */
    'toTerms' => function (Field $field): TermsCollection {
        return (new TermsCollection(parent: $field->parent()))->add($field->toPages());
    },
];
