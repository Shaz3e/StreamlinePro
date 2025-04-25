<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Download extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'version',
        'file_path',
        'is_active',
        'download_link',
    ];

    // SoftDeletes
    protected $dates = ['deleted_at'];

    // public function getFilePathAttribute($value)
    // {
    //     return asset('storage/' . $value);
    // }

    /**
     * User's downloads
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'download_users');
    }
}
