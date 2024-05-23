<?php

namespace App\Providers;


use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        if ($this->app->environment('production') || $this->app->environment('staging')) {
            URL::forceScheme('https');
        }
        View::share('uptPusatId', env('UPT_PUSAT_ID', 1000));
        Blade::directive('statusimport', function ($expression) {
            $statuses = [
                25 => 'Importir Umum',
                26 => 'Importir Produsen',
                27 => 'Importir Terdaftar',
                28 => 'Agen Tunggal',
                29 => 'BULOG',
                30 => 'PERTAMINA',
                31 => 'DAHANA',
                32 => 'IPTN',
            ];
            $status = $statuses[$expression] ?? null;
            return "<?php echo {$status} ?>";
        });

        Blade::directive('aktifitas', function ($expression) {
            $data = [
                1 => 'Import',
                2 => 'Domestik Masuk',
                3 => 'Export',
                4 => 'Domestik Keluar',
            ];
            $array = explode(',', $expression);
            $filteredData = array_filter($data, function ($key) use ($array) {
                return in_array($key, $array);
            }, ARRAY_FILTER_USE_KEY);

            $value = implode(', ', $filteredData);

            return "<?php echo {$value} ?>";
        });
    }
}



