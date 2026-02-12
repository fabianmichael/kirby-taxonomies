<?php

namespace FabianMichael\Taxonomies\Models;

use Kirby\Cms\Page;
use Kirby\Cms\Pages;
use Kirby\Content\Field;
use Kirby\Toolkit\I18n;

/**
 * The taxonomies page is the directory for all taxonomies.
 */
class Taxonomies extends Page {

	/**
	 * Returns the title used for the panel menu from translations
	 */
	public function title(): Field
	{
		return new Field($this, 'title', I18n::translate('taxonomies.panel.label'));
	}

	/**
	 * Removes the children from the Link field
	 */
	public function hasChildren(): bool
	{
		return false;
	}

	/**
	 * Returns all taxonomies
	 * 
	 * @return Pages
	 */
	public function taxonomies(): Pages
	{
		return $this->children();
	}
}
