<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// command that creates new scss file in resources/sass
// and writes
// @import 'bootstrap/scss/bootstrap';
//
//// Variables
//@import './_variables';

Artisan::command('make:scss {name}', function () {
    $name = $this->argument('name');

    $dirs = explode('/', $name);
    array_pop($dirs);

    $dir = resource_path('sass');
    $returnDots = '';
    foreach ($dirs as $d) {
        $dir .= '/' . $d;
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        $returnDots .= '../';
    }

    $path = resource_path('sass/' . $name . '.scss');
    if (file_exists($path)) {
        $this->error('File already exists!');
        return;
    }
    $this->info('File created: ' . $path);
})->purpose('Create new scss file in resources/sass');
