<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Blade::directive('price', function ($money) {
            return "Rp. " . "<?php echo number_format($money, 2); ?>";
        });

        Validator::extend('max_uploaded_file_size_evoria_program', function ($attribute, $value, $parameters, $validator) {
            $total_size = array_reduce($value, function ($sum, $item) {
                // each item is UploadedFile Object
                $sum += filesize($item->path());
                return $sum;
            });

            // $parameters[0] in kilobytes
            return $total_size < $parameters[0] * 1024;
        }, 'Upload Max File Is 10 MB');
    }
}
