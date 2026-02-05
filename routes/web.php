<?php

use App\Models\Tenant;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/create', function () {
    $tenant1 = Tenant::create(['id' => 'foo']);
    $tenant1->domains()->create(['domain' => 'foo.laraums.test']);
    return 1;
});

Route::get('/delete', function () {
    $tenant1 = App\Models\Tenant::find('foo');
    $tenant1->delete();
    return 1;
});
