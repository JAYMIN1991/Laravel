<?php
use Symfony\Component\Process\Process;

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

Artisan::command('flinnt:optimize', function () {
	$process = new Process('composer install');
	$process->run();
	$process = new Process('gulp --local');
	$process->run();
	$this->call('twig:clean');
	$this->call('cache:clear');
	$this->call('laroute:generate');
	$this->call('ide-helper:generate');
	$process = new Process('composer dump-autoload');
	$process->run();
	$this->call('module:optimize');
	$this->call('optimize');
	$this->info("Command Executed:composer install, gulp, twig:clean, cache:clear, laroute:generate, ide-helper:generate, composer dump-autoload, module:optimize, optimize!");
})->describe('Execute Commands: composer install, gulp, twig:clean, cache:clear, laroute:generate, ide-helper:generate, composer dump-autoload, module:optimize, optimize');