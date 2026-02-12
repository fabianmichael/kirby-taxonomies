<?php

namespace FabianMichael\Taxonomies\Models;

use FabianMichael\Taxonomies\TaxonomyBlueprint;
use FabianMichael\Taxonomies\TermsCollection;
use Kirby\Cms\Html;
use Kirby\Cms\Page;
use Kirby\Content\Field;
use Kirby\Toolkit\I18n;

/**
 * Represents a taxonomy in the Taxonomies plugin.
 */
class Taxonomy extends Page
{
    /**
     * Taxonomies use a custom blueprint to define the fields for the terms,
     * which is why we need to return a custom instance of `TaxonomyBlueprint`.
     */
    public function blueprint(): TaxonomyBlueprint
    {
        return $this->blueprint ??= TaxonomyBlueprint::factory(
            'taxonomies/' . $this->slug(),
            null,
            $this
        );
    }

    /**
     * Returns the children terms as a `TermsCollection` instance.
     */
    public function children(): TermsCollection
    {
        return $this->children ??= TermsCollection::factory($this->inventory()['children'], $this);
    }

    /**
     * Returns the blueprint for the taxonomy field to be used in the Kirby panel.
     */
    public function getFieldBlueprint(): array
    {
        return [
            'type' => 'pages',
            'query' => "site.find('{$this->id()}').children",
            'empty' => I18n::translate('taxonomies.terms.empty'),
            'label' => $this->title()->toString(),
            'templates' => ["{$this->uid()}-term"],
            'translate' => false,
            'help' => I18n::template(
                'taxonomies.terms.help',
                [
                    'attr' => Html::attr([
                        'href' => ltrim($this->panel()->url(true), '/'),
                        'onclick' => 'panel.app.$go(this.href); return false;',
                    ]),
                    'title' => $this->title()->toString(),
                    'icon' => '<svg aria-hidden="true" data-type="edit-line" class="k-icon"><use xlink:href="#icon-edit-line"></use></svg>',
                ]
            ),
        ];
    }

    /**
     * Returns the blueprint definition for an individual term page.
     */
    public function getTermBlueprint(): array
    {
        $blueprint = $this->blueprint();
        $fields = $blueprint->termFields();
        $files = $blueprint->files();
        $hierarchical = $blueprint->hierarchical();

        $columns = [
            'main' => [
                'width' => '2/3',
                'fields' => (!empty($fields) && is_array($fields))
                    ? $fields
                    : ['placeholder' => ['type' => 'gap']],
            ],
        ];

        if ($files || $hierarchical) {
            $columns['sidebar'] = [
                'width' => '1/3',
                'sections' => [],
            ];

            if ($hierarchical) {
                $columns['sidebar']['sections']['terms'] = [
                    'extends' => 'sections/terms',
                    'templates' => ["{$this->uid()}-term"],
                ];
            }

            if ($files !== false && $files !== null) {
                $columns['sidebar']['sections']['files'] = (is_array($files) || is_string($files))
                    ? $files
                    : [
                        'type' => 'files',
                        'label' => 'Files',
                    ];
            }
        }

        return [
            'title' => 'Term',
            'icon' => 'tag',
            'image' => [
                'back' => 'var(--item-color-back)',
                'color' => 'var(--item-color-icon)',
                'query' => false,
            ],
            'create' => [
                'redirect' => false,
            ],
            'options' => [
                'move' => false,
                'preview' => false,
            ],
            'status' => [
                'draft' => [
                    'label' => I18n::translate('taxonomies.term.status.draft'),
                    'text' => I18n::translate('taxonomies.term.status.draft.description'),
                ],
                'unlisted' => [
                    'label' => I18n::translate('taxonomies.term.status.published'),
                    'text' => I18n::translate('taxonomies.term.status.published.description'),
                ],
            ],
            'buttons' => [
                'changeSlug' => [
                    'dialog' => '{{ page.panel.url(true) }}/changeTitle?select=slug',
                    'text' => I18n::translate('taxonomies.term.changeSlug'),
                    'icon' => 'url',
                ],

                'delete' => [
                    'dialog' => '{{ page.panel.url(true) }}/delete',
                    'text' => I18n::translate('delete'),
                    'icon' => 'trash',
                    'theme' => 'negative-icon',

                ],
                'languages' => true,
                'status' => true,
            ],
            'columns' => $columns,
        ];
    }

    /**
     * Returns the blueprint for the section displaying all terms of the taxonomy.
     */
    public function getTermsSectionBlueprint(): array
    {
        return [
            'extends' => 'sections/terms',
            'label' => $this->title()->toString(),
            'templates' => ["{$this->uid()}-term"],
        ];
    }

    /**
     * Returns a collection of all published terms of the taxonomy.
     */
    public function terms(): TermsCollection
    {
        return $this->index()
            ->template("{$this->uid()}-term")
            ->published();
    }

    /**
     * Returns the title field for the taxonomy using the blueprint-defined title.
     */
    public function title(): Field
    {
        return new Field($this, 'title', $this->blueprint()->title());
    }
}
