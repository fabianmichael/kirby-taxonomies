<?php

use FabianMichael\Taxonomies\TermsCollection;
use Kirby\Cms\Pages;
use Kirby\Toolkit\A;

/**
 * Custom methods for the `Kirby\Cms\Pages` class.
 */
return [
    /**
     * Filter the pages by the given taxonomy uuid(s).
     *
     * @param string $taxonomy The taxonomy slug.
     * @param Pages|array<string>|string $uuids The taxonomy uuids.
     * @param bool $all Whether to filter by all uuids or any of them.
     * @return Pages The filtered pages.
     */
    'filterByTaxonomyUuids' => function (
        string $taxonomy,
        Pages|string|array $uuids,
        bool $all = false
    ): Pages {
        /** @var Pages $this */

        if ($uuids instanceof Pages) {
            $uuids = $uuids->pluck('uuid');
        }

        $uuids = A::wrap($uuids);
        $uuidsCount = count($uuids);

        return $this->filter(function ($page) use ($taxonomy, $uuids, $uuidsCount, $all): bool {
            $pageUuids = $page->content()->get($taxonomy)->toTerms()->uuids();

            if ($all) {
                return count(array_intersect($pageUuids, $uuids)) ===  $uuidsCount;
            } else {
                return count(array_intersect($pageUuids, $uuids)) > 0;
            }
        });
    },

    /**
     * Filter the pages by the given taxonomy slug(s).
     *
     * @param string $taxonomy The taxonomy slug.
     * @param Pages|string|array $slugs The taxonomy slugs.
     * @param bool $all Whether to filter by all slugs or any of them.
     * @return Pages The filtered pages.
     */
    'filterByTaxonomySlugs' => function (
        string $taxonomy,
        Pages|string|array $slugs,
        bool $all = false
    ): Pages {
        /** @var Pages $this */

        if ($slugs instanceof Pages) {
            $slugs = $slugs->pluck('slug');
        }

        $slugs = A::wrap($slugs);
        $terms = taxonomy($taxonomy)?->terms();

        if ($terms === null) {
            return $this;
        }

        $uuids = array_map(fn ($slug) => $terms->findBy('slug', $slug)?->uuid()->id(), $slugs);
        $uuids = array_filter($uuids);

        return $this->filterByTaxonomyUuids($taxonomy, $uuids, $all);
    },

    /**
     * Get the terms used on the pages for a given taxonomy.
     *
     * @param string $taxonomy The taxonomy slug.
     * @return TermsCollection The terms used on the pages.
     */
    'terms' => function (string $taxonomy): TermsCollection {
        /** @var Pages $this */

        $termsUsed = TermsCollection::factory([]);

        foreach ($this as $page) {
            $termsUsed->add($page->content()->get($taxonomy)->toPages());
        }

        return $termsUsed->sortBy('title', 'asc');
    }
];
