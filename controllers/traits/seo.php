<?php

trait SEO {
	static function __seoInitialize($obj) {
		$obj->add_view_vars($obj->get_seo_vars());
	}

	function get_seo_title() {
		$page =& $this->page;
		$pages = wire('pages');
		$seo_title = '';

		if ($title = $page->seo_title) {
			$seo_title = $title;
		} else {
			$seo_title = $page->title;
			$seo_title .= ' ' . $pages->get('/settings/')->seo_title_separator;
			$seo_title .= ' ' . $pages->get('/settings/')->seo_title_append;
		}

		return $seo_title;
	}

	function get_seo_vars() {
    $page =& $this->page;

		return array(
			'seo_title'   => $this->get_seo_title(),
			'seo_desc'    => $page->seo_descr,
			'seo_noindex' => $page->seo_noindex
		);
	}
}

