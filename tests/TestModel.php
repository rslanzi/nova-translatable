<?php

namespace Rslanzi\NovaTranslatable\Tests;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = "tests";

    protected $fillable = ['name'];

    public $translatedAttributes = ['title'];
}
