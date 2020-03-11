<?php

if (!class_exists('ProgressAllyBeaverBuilderModule')) {
	class ProgressAllyBeaverBuilderModule extends FLBuilderModule {
		public function __construct() {
			parent::__construct(array(
				'name'            => 'ProgressAlly',
				'description'     => 'Display ProgressAlly controls',
				'category'        => 'AccessAlly',
				'dir'             => dirname(__FILE__) . '/',
				'url'             => dirname(__FILE__) . '/',
				'editor_export'   => true,
				'enabled'         => true,
				'partial_refresh' => false,
			));
		}
	}
}