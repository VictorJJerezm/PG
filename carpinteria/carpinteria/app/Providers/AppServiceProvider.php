<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManagerStatic as Image; // <-- Añadido aquí

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrando el provider de Intervention Image
        $this->app->bind('Image', function () {
            return new Image();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $pathToCert = storage_path('certs/cacert.pem');
        
        if (file_exists($pathToCert)) {
            putenv('SSL_CERT_FILE=' . $pathToCert);
        }
    }
}
