<?php

namespace FabianMichael\Taxonomies;

use FabianMichael\Taxonomies\Models\Taxonomies as TaxonomiesPage;
use FabianMichael\Taxonomies\Models\Term;
use Kirby\Cms\App;
use Kirby\Cms\Page;

/**
 * The main plugin class for the Taxonomies plugin.
 */
final class Taxonomies
{
    /**
     * Installs the Taxonomies plugin by registering the taxonomies page and the taxonomies.
     * This is called automatically called by a hook.
     */
    public static function install(): void
    {
        static::registerTaxonomiesPage();
        static::registerTaxonomies();
    }

    /**
     * Returns the Taxonomies page instance.
     */
    public static function page(): TaxonomiesPage
    {
        return App::instance()->page(option('fabianmichael.taxonomies.page'));
    }

    /**
     * Returns the path to the taxonomies page.
     */
    public static function taxonomiesPanelPath(): string
    {
        return static::page()->path() . '/pages/' . option('fabianmichael.taxonomies.page');
    }

    /**
     * Returns whether the current request is on the taxonomies page.
     */
    public static function taxonomiesPanelCurrent(): bool
    {
        return str_contains(
            App::instance()->request()->path()->toString(),
            self::taxonomiesPanelPath()
        );
    }

    /**
     * Creates the taxonomies page if it does not exist.
     */
    private static function registerTaxonomiesPage(): void
    {
        $kirby = kirby();
        $page = option('fabianmichael.taxonomies.page');

        if ($kirby->page("page://{$page}")?->exists()) {
            return;
        }

        // create the page
        $kirby->impersonate(
            'kirby',
            fn () => $kirby->site()->createChild([
                'slug' => $page,
                'template' => 'taxonomies',
                'content' => [
                    'uuid' => $page,
                ]
            ])->changeStatus('unlisted')
        );
    }

    /**
     * Create the taxonomy pages if they do not exist and register the blueprints and page models.
     */
    private static function registerTaxonomies(): void
    {
        $kirby = kirby();
        $taxonomies = $kirby->blueprints('taxonomies');

        $page = static::page();
        $blueprints = [];
        $pageModels = [];

        foreach ($taxonomies as $slug) {
            if ($slug === 'default') {
                continue;
            }

            $taxonomy = $page->find($slug);

            if (!$taxonomy) {
                $taxonomy = $kirby->impersonate('kirby', fn (): Page => $page->createChild([
                    'slug' => $slug,
                    'template' => "taxonomy",
                    'content' => [
                        'uuid' => "taxonomy-{$slug}",
                    ]
                ])->changeStatus('unlisted'));
            }

            $blueprints["fields/taxonomies/{$slug}"] = $taxonomy->getFieldBlueprint();
            $blueprints["pages/{$slug}-term"] = $taxonomy->getTermBlueprint();
            $blueprints["sections/taxonomies/{$slug}-terms"] = $taxonomy->getTermsSectionBlueprint();
            $pageModels["term-{$slug}"] = Term::class;
        }

        $kirby->extend([
            'blueprints' => $blueprints,
            'pageModels' => $pageModels,
        ]);
    }

    /**
     * Returns the root directory of the plugin.
     */
    public static function pluginRoot(): string
    {
        return dirname(__DIR__);
    }

	public static function normalizeTaxonomyFieldName(string $slug): string
	{
		return str_replace('-', '_', $slug);
	}
}
