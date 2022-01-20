<?php

namespace app\extensions;
use innsert\mvc\Controller,
	\stdClass,
	innsert\lang\Lang,
	innsert\jwt\Membership,
	innsert\jwt\Payload,
	innsert\resp\HeadersOnlyResponse,
	innsert\views\LanguageView,
	innsert\db\DB;
/**
 * Clase base para los controladores del sitio
 *
 * @author	isaac
 * @version	1
 */
class MainController extends Controller
{
	/**
	 * Objeto del lenguaje
	 *
	 * @access	public
	 * @var		Language
	 */
	public $lang;

	/**
	 * Objeto de la membresía, éste administra los permisos de los usuarios
	 *
	 * @access	public
	 * @var		Membership
	 */
	public $membership;

	/**
	 * Secreto para los tokens JWT
	 *
	 * @access	public
	 * @var		string
	 */
	public $jwtSecret = '###';

	/**
	 * Secreto para encripción reversible del payload del JWT
	 *
	 * @access	public
	 * @var		string
	 */
	public $payloadSecret = '###';

	/**
	 * Constructor
	 *
	 * Importa la clase de lenguaje y de membresía
	 *
	 * @access	public
	 */
	public function __construct()
	{
		parent::__construct();
		$this->items = new stdClass();
		$this->lang = Lang::defaultInstance();
		$this->membership = (new Membership(
			$this->jwtSecret,
			$this->request->apacheHeader('authorization'),
			new Payload($this->payloadSecret)
		))->setNotAuthorizedResponse(
			$this->json(['status' => 'SESSION', 'message' => 'Unauthorized'])
		);
	}

	/**
	 * OPTION check para cors
	 *
	 * @access	public
	 * @return	JsonResponse
	 */
	public function _middleware()
	{
		if ($this->request->method === 'OPTIONS') {
			return new HeadersOnlyResponse();
		}
	}

	/**
	 * Devuelve la instancia default de la base de datos
	 *
	 * @access	protected
	 * @return	DBInterface
	 */
	protected function dbDefaultInstance()
	{
		return DB::defaultInstance();
	}

	/**
	 * Develve el contenido de una vista como texto (Para correos principalmente)
	 *
	 * @access	protected
	 * @param	array		$path	El path donde se encuentra la vista
	 * @param	array		$items	Los elementos a usar en la vista
	 * @return	string
	 */
	protected function renderView(array $path, array $items = [])
	{
		$view = new LanguageView($path, $items);
		$view->render();
		return $view->draw();
	}
}
