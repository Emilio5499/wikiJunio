<?php

use Illuminate\Support\Facades\Route;

Route::get('/docs', '\Knuckles\Scribe\Http\Controllers\DocumentationController');
Route::get('/docs/postman', '\Knuckles\Scribe\Http\Controllers\PostmanCollectionController');
Route::get('/docs/openapi', '\Knuckles\Scribe\Http\Controllers\OpenApiController');
