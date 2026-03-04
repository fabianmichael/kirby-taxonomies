<?php

namespace FabianMichael\Taxonomies;

use Kirby\Cms\Pages;
use Stringable;

/**
 * A collection of taxonomy terms with extra methods
 */
class TermsCollection extends Pages implements Stringable
{
    /**
     * Returns the HTML-escaped string representation of the collection as a comma-separated list of titles.
     */
    public function toHtml(): string
    {
        return html($this->toString());
    }

    /**
     * Returns the string representation of the collection as a comma-separated list of titles.
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * Returns the string representation of the collection as a comma-separated list of titles.
     */
    public function toString(): string
    {
        return implode(', ', $this->pluck('title'));
    }

    /**
     * Returns the UUIDs of the terms in the collection as plain strings.
     */
    public function uuids(): array
    {
        return array_map(fn ($term) => $term->id(), $this->pluck('uuid'));
    }
}
