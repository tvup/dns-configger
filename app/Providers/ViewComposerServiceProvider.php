<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->titleComposer();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Compose menu items for every view that needs this.
     */
    private function titleComposer() : void
    {
        view()->composer(['fejlvarp::incidents.index', 'fejlvarp::incidents.show'], function ($view) {
            $titles = [
                'fejlvarp::incidents.index'=>'List site errors',
                'fejlvarp::incidents.show'=>'Incident details',
            ];
            $title = $titles[$view->getName()] ?? 'HAL6';
            $view->with('title', $title);
        });
    }
}
