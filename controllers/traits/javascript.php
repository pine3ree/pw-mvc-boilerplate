<?php

use fixate as f8;

trait Javascript {
	static function __jsInitialize($obj) {
		$obj->helper('js_add_vendor');
		$obj->helper('js_add_script');
		$obj->helper('js_add_cdn');
		$obj->helper('render_scripts');
	}

	private $__js_scripts = array();

	function js_add_vendor($vendor) {
		$main = $this->__load_bower_main($vendor);
		if (is_array($main)) {
			$main = current($main);
		}

		$this->__js_scripts[] = array('vendor', $vendor, "vendor/%%/{$main}");
	}

	function js_add_script($script, $path_tmpl = 'js/%%') {
		$this->__js_scripts[] = array('user', $script, $path_tmpl);
	}

	function js_add_cdn($url, $detect = false, $fallback = false) {
		$this->__js_scripts[] = array('cdn', $url, array($detect, $fallback));
	}

	function render_scripts() {
		$html = '';
		foreach ($this->__js_scripts as $script) {
			list($type, $url) = $script;
			switch ($type) {
			case 'user':
			case 'vendor':
				$path = $this->view->assets(str_replace('%%', $url, array_pop($script)));
				$html .= $this->__script_tag($path);
				break;
			case 'cdn':
				$html .= $this->__cdn_tag($url, array_pop($script));
				break;
			}
		}

		return $html;
	}

	private function __cdn_tag($url, $fallback = false) {
		$html = $this->__script_tag($url);
		if ($fallback) {
			list($detect, $asset) = $fallback;
			$asset = $this->view->assets($asset);
			$html .= "<script>({$detect}) || document.write('<script src=\"{$asset}\">\\x3C/script>')</script>";
		}
		return $html;
	}

	private function __load_bower_main($vendor) {
		$file = f8\Paths::join(TEMPLATE_DIR, "assets/vendor/{$vendor}/.bower.json");
		$manifest = json_decode(file_get_contents($file), true);
		return $manifest['main'];
	}

	private function __script_tag($src) {
		return "<script type=\"text/javascript\" src=\"{$src}\"></script>";
	}
}
