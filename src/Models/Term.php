<?php

namespace FabianMichael\Taxonomies\Models;

use Exception;
use FabianMichael\Taxonomies\TermsCollection;
use Kirby\Cms\Page;

/**
 * Represents a taxonomy term page.
 */
class Term extends Page
{
    /**
     * Returns the children terms as a `TermsCollection` instance.
     */
    public function children(): TermsCollection
    {
        return $this->children ??= TermsCollection::factory($this->inventory()['children'], $this);
    }

    /**
     * Returns the parent Taxonomy of this term.
     *
     * @throws Exception If no parent taxonomy is found.
     */
    public function taxonomy(): Taxonomy
    {
        foreach ($this->parents() as $parent) {
            if ($parent instanceof Taxonomy) {
                return $parent;
            }
        }

        throw new Exception('Term has no parent taxonomy.');
    }

    /**
     * Returns the collection of child terms, filtered by template and published status.
     */
    public function terms(): TermsCollection
    {
        return $this->children()
            ->intendedTemplate("{$this->uid()}-term")
            ->published();
    }
}
