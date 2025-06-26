<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\QuoteRequest;
use App\Policies\QuoteRequestPolicy;
use App\Models\Order;
use App\Policies\OrderPolicy;
use App\Models\Address;
use App\Policies\AddressPolicy;
use App\Services\Carriers\CarrierQuoteProviderInterface;
use App\Services\Carriers\GenericQuoteProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     */
    public function register(): void
    {
        // Bind generic quote provider as default
        $this->app->bind(
            CarrierQuoteProviderInterface::class,
            GenericQuoteProvider::class
        );
    }

    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        QuoteRequest::class => QuoteRequestPolicy::class,
        Order::class        => OrderPolicy::class,
        Address::class      => AddressPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
