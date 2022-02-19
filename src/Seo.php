<?php

namespace App\Plugins\Seo\src;

use App\Plugins\Seo\src\Model\SeoUrl;
use App\Plugins\Topic\src\Models\Topic;
use App\Plugins\Topic\src\Models\TopicTag;
use App\Plugins\User\src\Models\User;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use samdark\sitemap\Sitemap;
use Swoole\Coroutine\System;

#[Controller(prefix:"/seo")]
class Seo
{
	// 获取所有可提交的网址
	#[GetMapping(path:"getUrl")]
	public function getUrl(){
		$urls = array_merge($this->getTopicUrl(),$this->getTagUrl(),$this->getUsersUrl());
		foreach($urls as $url) {
			if(!SeoUrl::query()->where("url",$url)->exists()){
				SeoUrl::query()->create([
					'url' => $url,
					'class' => 'all'
				]);
			}
		}
		$arr = [];
		foreach(SeoUrl::query()->where("class",'all')->get(['url']) as $value){
			$arr[]=$value->url;
		}
		return $arr;
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
		foreach($urls as $url) {
			if(!SeoUrl::query()->where("url",$url)->exists()){
				SeoUrl::query()->create([
					'url' => $url,
					'class' => 'topic'
				]);
			}
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
		foreach($urls as $url) {
			if(!SeoUrl::query()->where("url",$url)->exists()){
				SeoUrl::query()->create([
					'url' => $url,
					'class' => 'tag'
				]);
			}
		}
		return $urls;
		
	}
	
	#[GetMapping(path:"getUsersUrl")]
	public function getUsersUrl(): array
	{
		$urls = [];
		foreach(User::query()->get() as $value){
			$urls[]=url("/users/".$value->username.".html");
		}
		foreach($urls as $url) {
			if(!SeoUrl::query()->where("url",$url)->exists()){
				SeoUrl::query()->create([
					'url' => $url,
					'class' => 'users'
				]);
			}
		}
		return $urls;
		
	}
}


