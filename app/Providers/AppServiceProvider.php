<?php

namespace App\Providers;

use App\Attributes\CheckFeatureFlag;
use App\Proxy\ProxyGenerator;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class AppServiceProvider extends ServiceProvider
{
    private array $extendedClasses = [];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ProxyGenerator::class);

        $this->app->beforeResolving(function (string $abstract) {
            if (isset($this->extendedClasses[$abstract]) || ! class_exists($abstract)) {
                return;
            }

            $reflection = new ReflectionClass($abstract);

            // Пропускаємо final-класи та вже згенеровані проксі
            if ($reflection->isFinal() || str_starts_with($abstract, 'App\\GeneratedProxies\\')) {
                return;
            }

            if ($this->hasFeatureFlagAttribute($reflection)) {
                $this->extendedClasses[$abstract] = true;

                $this->app->extend($abstract, function ($service, $app) {
                    return $app->make(ProxyGenerator::class)->make($service);
                });
            }
        });
    }

    private function hasFeatureFlagAttribute(ReflectionClass $reflection): bool
    {
        foreach ($reflection->getMethods() as $method) {
            if (! empty($method->getAttributes(CheckFeatureFlag::class))) {
                return true;
            }
        }

        return false;
    }
}
