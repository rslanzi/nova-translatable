<?php

namespace Rslanzi\NovaTranslatable\Tests;

use Illuminate\Database\Eloquent\Model;

class TestModelTranslation extends Model
{
    protected $table = "test_translations";

    protected $fillable = ['test_id', 'locale', 'title', 'slug'];

    public $timestamps = false;
}
