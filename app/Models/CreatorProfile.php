<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreatorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'slug',
        'profile_color',
        'banner_image',
        'custom_css',
    ];

    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }
}