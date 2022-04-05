<?php

namespace App\Plugins\Seo\src;

use App\Plugins\Seo\src\Model\SeoUrl;
use App\Plugins\Topic\src\Models\Topic;
use App\Plugins\Topic\src\Models\TopicTag;
use App\Plugins\User\src\Models\User;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\RateLimit\Annotation\RateLimit;
use samdark\sitemap\Sitemap;
use Swoole\Coroutine\System;

#[Controller(prefix:"/seo")]
class Seo
{
	// 获取所有可提交的网址
	#[GetMapping(path:"getUrl")]
	public function getUrl(){
		$urls = array_merge($this->getTopicUrl(),$this->getTagUrl(),$this->getUsersUrl());
		foreach(Itf()->get("SeoUrl") as $value){
			if(count($value)===2 && @method_exists(new $value[0](),$value[1])){
				$method = $value[1];
				$urls[]=(new $value[0])->$method();
			}
		}
		return @array_unique($urls);
	}
	#[GetMapping(path:"makeSitemap")]
	public function makeSitemap(){
		// make all
		if(!is_dir(public_path("plugins"))){
			System::exec("mkdir ".public_path("plugins"));
		}
		if(!is_dir(public_path("plugins/Seo"))){
			System::exec("mkdir ".public_path("plugins/Seo"));
		}
		$sitemap = new Sitemap(public_path('plugins/Seo/sitemap.xml'));
		
		foreach($this->getUrl() as $url){
			$sitemap->addItem($url);
		}
		$sitemap->write();
		
		// make topic
		$sitemap = new Sitemap(public_path('plugins/Seo/sitemap-topic.xml'));
		
		foreach($this->getTopicUrl() as $url){
			$sitemap->addItem($url);
		}
		$sitemap->write();
		
		// make tag
		$sitemap = new Sitemap(public_path('plugins/Seo/sitemap-tag.xml'));
		
		foreach($this->getTagUrl() as $url){
			$sitemap->addItem($url);
		}
		$sitemap->write();
		
		// make users
		$sitemap = new Sitemap(public_path('plugins/Seo/sitemap-users.xml'));
		
		foreach($this->getUsersUrl() as $url){
			$sitemap->addItem($url);
		}
		$sitemap->write();
	}
	
	
	#[GetMapping(path:"getTopicUrl")]
	public function getTopicUrl(): array
	{
		$urls = [];
		foreach(Topic::query()->where("status",'publish')->get() as $value){
			$urls[]=url("/".$value->id.".html");
		}
		return $urls;
		
	}
	
	#[GetMapping(path:"getTagUrl")]
	public function getTagUrl(): array
	{
		$urls = [];
		foreach(TopicTag::query()->get() as $value){
			$urls[]=url("/tags/".$value->id.".html");
		}
		return $urls;
		
	}
	
	#[GetMapping(path:"submit")]
	#[RateLimit(create:1, consume:1, capacity:1)]
	public function submit(){
		$urls = [];
		foreach($this->getUrl() as $url){
			if(!SeoUrl::query()->where(['class' => 'submit','url' => $url])->exists()){
				$urls[]=$url;
				SeoUrl::query()->create([
					'class' => 'submit',
					'url' => $url,
				]);
			}
		}
		$api = 'http://data.zz.baidu.com/urls?site='.get_options_nocache('seo_baidu_url').'&token='.get_options_nocache('seo_baidu_token');
		$ch = curl_init();
		$options =  array(
			CURLOPT_URL => $api,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => implode("\n", $urls),
			CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
		);
		curl_setopt_array($ch, $options);
		return curl_exec($ch);
	}
	
	#[GetMapping(path:"getUsersUrl")]
	public function getUsersUrl(): array
	{
		$urls = [];
		foreach(User::query()->get() as $value){
			$urls[]=url("/users/".$value->username.".html");
		}
		return $urls;
		
	}
}


