<?php

namespace App\Plugins\Seo;

/**
 * @name Seo
 * @package 搜索引擎优化插件
 * @version 1.0.1
 * @author zhuchunshu
 * @link https://github.com/zhuchunshu
 */
class Seo
{
	public function handler(){
		require_once __DIR__ . '/vendor/autoload.php';
		$this->bootstrap();
		
	}
	
	private function bootstrap(){
		require_once __DIR__ . '/bootstrap.php';
	}
}