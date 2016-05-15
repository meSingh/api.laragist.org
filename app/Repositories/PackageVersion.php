<?php

namespace GistApi\Repositories;

use Illuminate\Database\Eloquent\Model;

class PackageVersion extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'package_versions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [];

    public function getLastUpdatedAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['last_updated'])->format('d M, Y');
    }


}
