<?php

namespace innsert\lang;

use innsert\core\Defaults,
	innsert\lib\HttpRequest;

/**
 * Innsert PHP MVC Framework
 *
 * Language class
 *
 * @author	izisaurio
 * @package	innsert
 * @version	1
 */
class Language
{
	/**
	 * Path to label files folder
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $labelsFolder = ['app', 'configs', 'labels'];

	/**
	 * Current locale
	 *
	 * @access	public
	 * @var		string
	 */
	public $locale;

	/**
	 * Default locale
	 *
	 * @access	public
	 * @var		string
	 */
	public $defaultLocale;

	/**
	 * Storage for labels
	 *
	 * @access	protected
	 * @var		array
	 */
	protected $storage = [];

	/**
	 * Constructor
	 *
	 * Sets current locale values
	 *
	 * @access	public
	 */
	public function __construct()
	{
		$this->locale = Defaults::defaultInstance()['language'];
		$this->defaultLocale = $this->locale;
	}

	/**
	 * Sets locale from browser
	 *
	 * @access	public
	 * @param	HttpRequest		$request	Request instance
	 */
	public function setFromBrowser(HttpRequest $request)
	{
		$language = substr($request->server('HTTP_ACCEPT_LANGUAGE', 'none'), 0, 2);
		if (in_array($language, Defaults::defaultInstance()['languages'])) {
			$this->locale = $language;
		}
	}

	/**
	 * Returns the given label
	 *
	 * @access	public
	 * @param	string	$message	{labelFile}.{key}
	 * @param	array	$params		Label params, label needs vsprintf placeholders
	 * @param	string	$lang		Locale, if missing uses current
	 * @return	string
	 * @throws	LabelNotFoundException
	 */
	public function get($message, array $params = array(), $lang = null)
	{
		list($file, $key) = strpos($message, '.') === false ? ['_default', $message] : explode('.', $message);
		$labels = $this->loadStorage($file);
		$locale = isset($lang) ? $lang : $this->locale;
		if (isset($labels[$key][$locale])) {
			return $this->format($labels[$key][$locale], $params);
		}
		if (isset($labels[$key])) {
			return $this->format((is_array($labels[$key]) ? $labels[$key][0] : $labels[$key]), $params);
		}
		throw new LabelNotFoundException($file, $key, $locale);
	}

	/**
	 * Returns all labels of the same language from a file
	 *
	 * @access	public
	 * @param	string	$file	Labels file
	 * @param	string	$lang	Locale, if missing uses current
	 * @return	array
	 * @throws	LabelsFileNotFoundException
	 */
	public function getFileLanguage($file, $lang = null)
	{
		$labels = $this->loadStorage($file);
		$locale = isset($lang) ? $lang : $this->locale;
		$languageLabels = [];
		foreach ($labels as $key => $value) {
			$languageLabels[$key] = $value[$locale];
		}
		return $languageLabels;
	}

	/**
	 * Returns a label with specific language
	 *
	 * @access	public
	 * @param	string	$message	{labelFile}.{key}
	 * @param	string	$lang		Locale, required
	 * @param	array	$params		Label params, label needs vsprintf placeholders
	 * @return	string
	 * @throws	LabelNotFoundException
	 */
	public function getWithLang($message, $lang, array $params = array())
	{
		return $this->get($message, $params, $lang);
	}

	/**
	 * Returns label text formatted
	 *
	 * @access	protected
	 * @param	string	$label		Label to format if params are present
	 * @param	array	$params		vsprintf params
	 * @return	string
	 */
	protected function format($label, array $params = array())
	{
		return !empty($params) ? vsprintf($label, $params) : $label;
	}

	/**
	 * Loads label file from storage, if not stored, stores it
	 *
	 * @access	protected
	 * @param	string	$file		Label file
	 * @return	array
	 * @throws	LabelsFileNotFoundException
	 */
	protected function loadStorage($file)
	{
		if (!isset($this->storage[$file])) {
			$path = join(DS, $this->labelsFolder) . DS . $file . EXT;
			if (!file_exists($path)) {
				throw new LabelsFileNotFoundException($file);
			}
			$this->storage[$file] = require $path;
		}
		return $this->storage[$file];
	}
}