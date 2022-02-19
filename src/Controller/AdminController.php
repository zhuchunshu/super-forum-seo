<?php

namespace App\Plugins\Seo\src\Controller;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use function view;

#[Controller(prefix:"/admin/Seo")]
class AdminController
{
	#[GetMapping(path:"")]
	public function index(){
		return view("Seo::admin");
	}
}