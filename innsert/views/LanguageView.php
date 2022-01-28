<?php

namespace innsert\views;

use innsert\lang\Lang;

/**
 * Innsert PHP MVC Framework
 *
 * View class with language
 *
 * @author	izisuario
 * @package	innsert
 * @version	1
 */
class LanguageView extends View
{
	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	$path	Template path
	 * @param	array	$items	Elements from controller to view
	 * @param	string	$lang	Language of template
	 * @throws	TemplateNotFoundException
	 */
	public function __construct(array $path, array $items = [], $lang = null)
	{
		$language = isset($lang) ? $lang : Lang::defaultInstance()->locale;
		$this->fullPath =
			join(DS, array_merge(['public', 'views', $language], $path)) . EXT;
		if (!file_exists($this->fullPath)) {
			$this->fullPath =
				DIRECTORY .
				DS .
				join(
					DS,
					array_merge(
						[
							'public',
							'views',
							Lang::defaultInstance()->defaultLocale,
						],
						$path
					)
				) .
				EXT;
			if (!file_exists($this->fullPath)) {
				throw new TemplateNotFoundException($this->fullPath);
			}
		}
		$this->path = $path;
		$this->items = $items;
	}

	/**
	 * Imports a template to this one
	 *
	 * @access	public
	 * @param	array	$path	Template path
	 * @param	array	$items	Items to send to template (overrided current items)
	 * @return	View
	 */
	public function import(array $path, array $items = null)
	{
		$import = new self($path, isset($items) ? $items : $this->items);
		$import->render();
		return $import;
	}
}
