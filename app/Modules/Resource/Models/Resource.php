<?php

namespace App\Modules\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\MusicCompany\Models\MusicCompany;
class Resource extends Model
{
    protected $fillable = [
        'title', 'slug', 'file_name', 'file_type','file_size','description', 'path', 'url', 'type_code', 'link_code','resource_code'
    ];

    public function resourceType()
    {
        return $this->belongsTo(ResourceType::class);
    }

    public function resourceLinkType()
    {
        return $this->belongsTo(ResourceLinkType::class);
    }

    public function musicCompanies()
    {
        return $this->belongsToMany(MusicCompany::class, 'music_company_resource', 'resource_id', 'music_company_id');
    }
    
}
