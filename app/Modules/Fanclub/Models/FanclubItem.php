<?php

namespace App\Modules\Fanclub\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Resource\Models\Resource;

class FanclubItem extends Model
{
    use HasFactory;

    protected $fillable = ['resource_id', 'resource_code'];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }
}
