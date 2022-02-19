<?php

declare (strict_types=1);
namespace App\Plugins\Seo\src\Model;

use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id 
 * @property string $class 
 * @property string $url 
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SeoUrl extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seo_url';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','class','url','created_at','updated_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}