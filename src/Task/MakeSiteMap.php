<?php

namespace App\Plugins\Seo\src\Task;

use Hyperf\Crontab\Annotation\Crontab;

#[Crontab(name:"makeSitemap",rule:"* * * * *",callback:"handler",memo:"生成sitemap")]
class MakeSiteMap
{
	public function handler(){
		var_dump(http('raw')->get(url("/seo/makeSitemap"))->getBody());
	}
}