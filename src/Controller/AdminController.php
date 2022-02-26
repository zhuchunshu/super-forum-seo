<?php

namespace App\Plugins\Seo\src\Controller;

use App\Model\AdminOption;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use function view;

#[Controller(prefix:"/admin/Seo")]
class AdminController
{
	#[GetMapping(path:"")]
	public function index(){
		return view("Seo::admin");
	}
	
	#[PostMapping(path:"baidu")]
	public function baidu_submit(){
		$url = request()->input('url');
		$token = request()->input('token');
		if(!$token || !$url){
			return redirect()->back()->with('error','请求参数不足!')->go();
		}
		$this->setOption([
			'seo_baidu_url' => $url,
			'seo_baidu_token' => $token
		]);
		return redirect()->back()->with('success','修改成功!')->go();
	}
	
	private function setOption($data = []): void
	{
		foreach($data as $key =>$value){
			if(AdminOption::query()->where("name",$key)->exists()){
				AdminOption::query()->where("name",$key)->update(['value'=>$value]);
			}else{
				AdminOption::query()->create(['name' => $key,'value'=>$value]);
			}
		}
	}
}