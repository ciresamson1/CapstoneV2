<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Task;

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
    View::composer('*', function ($view) {

        $overdueTasks = Task::where('due_date','<',now())
            ->where('status','!=','completed')
            ->count();

        $view->with('overdueTasks', $overdueTasks);

    });
}
}
