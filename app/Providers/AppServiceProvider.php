<?php

namespace App\Providers;

use Illuminate\Routing\ResourceRegistrar as BaseResourceRegistrar;
use App\Overrides\ResourceRegistrar;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->bind(BaseResourceRegistrar::class, ResourceRegistrar::class);
        
        $loader = AliasLoader::getInstance();
        //таблицы
        $loader->alias(Table::class, \App\Overrides\Okipa\Table::class);
        $loader->alias(Column::class, \App\Overrides\Okipa\Column::class);
        $loader->alias(\Okipa\LaravelTable\Livewire\Table::class, \App\Overrides\Okipa\Livewire\Table::class);
        
        $this->app->setLocale('ru');
        Paginator::useBootstrapThree();
    }
}
