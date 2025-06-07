<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;

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
        // Override Carbon's diffInYears method to always return an integer
        // This is a global fix that will affect all age calculations
        Carbon::macro('diffInYearsInt', function ($date = null, $absolute = true) {
            /** @var Carbon $this */
            return intval($this->diffInYears($date, $absolute));
        });

        // Also create a Blade directive for age calculation
        \Blade::directive('intage', function ($expression) {
            return "<?php echo intval(($expression)->diffInYears(now())); ?>";
        });
    }
}
