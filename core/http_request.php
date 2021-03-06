<?php
namespace fixate;

/**
 * ProcessWire HttpRequest Module
 *
 * Provides a HttpRequest object
 *
 * ProcessWire 2.x
 * Copyright (C) 2011 by Stan Bondi
 * Licensed under GNU/GPL v2, see LICENSE.TXT
 *
 * http://www.processwire.com
 * http://www.fixate.it
 *
 */

class HttpRequest {
	static $http_methods = array(
		'GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'COPY',
		'HEAD', 'OPTIONS', 'LINK', 'UNLINK', 'PURGE'
	);

	static function instance() {
		static $instance = null;
		if ($instance == null) {
			$instance = new static();
		}

		return $instance;
	}

	protected $headers = null;
	protected $_params = null;

	function headers() {
		if ($headers) {
			return $headers;
		}

		return $headers = $this->get_all_headers();
	}

	function method() {
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}

	function header($name) {
		$headers = $this->headers();
		if (array_key_exists($name, $headers)) {
			return $headers[$name];
		}

		return null;
	}

	function params() {
		if ($this->_params) {
			return $this->_params;
		}

		return $this->_params = $this->get_all_params();
	}

	function param($name) {
		$params = $this->params();
		if (array_key_exists($name, $params)) {
			return $params[$name];
		}

		return null;
	}

	protected function get_all_params() {
		switch($this->method()) {
		case 'GET':
		case 'COPY':
		case 'HEAD':
		case 'OPTIONS':
		case 'PURGE':
			return $_GET;
		case 'PUT':
		case 'DELETE':
      $vars = array();
      parse_str(file_get_contents("php://input"), $vars);
      return $vars;
    case 'POST':
    case 'PATCH':
		case 'LINK':
		case 'UNLINK':
			return array_merge($_GET, $_POST);
		default:
			return array();
		}
	}

	protected function get_all_headers() {
		$headers = '';
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}

	private function __clone() {
		trigger_error("Clone disabled for singleton class.", E_ERROR);
	}
}


