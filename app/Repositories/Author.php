<?php

namespace GistApi\Repositories;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'authors';

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


}
