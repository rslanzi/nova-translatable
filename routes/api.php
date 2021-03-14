<?php

use Illuminate\Support\Facades\Route;

Route::post('/slug/generate', 'Rslanzi\NovaTranslatable\Http\Controllers\SlugController@getSlug');
