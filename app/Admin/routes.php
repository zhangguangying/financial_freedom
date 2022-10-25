<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.as'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('funds', FundController::class);
    $router->resource('fund-owners', FundOwnerController::class);
});
