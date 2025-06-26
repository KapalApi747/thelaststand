<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/**
 * Middleware die de Livewire update route aanpast op basis van het domein.
 *
 * Wat doet deze middleware?
 * - Controleert of het verzoek gericht is op de Livewire update route ('livewire/update').
 * - Als het verzoek van een centraal domein komt (zoals in config('tenancy.central_domains')),
 *   wordt de Livewire update route ingesteld met alleen de 'web' middleware.
 * - Als het verzoek van een tenant-subdomein komt, wordt de update route ingesteld met extra
 *   tenancy middleware, zodat tenant-initialisatie en toegangsbeperkingen worden toegepast.
 * - Stelt daarnaast dynamisch de 'app.url' config in op basis van het huidige request domein,
 *   zodat URL-generatie binnen de applicatie correct is voor het tenant- of centrale domein.
 *
 * Dit is belangrijk om te zorgen dat Livewire-componenten correct kunnen communiceren via AJAX,
 * zowel voor centrale als tenant-specifieke routes, en dat tenant context correct wordt geïnitialiseerd.
 */

class SetLivewireUpdateRoute
{
    /* handle is de standaard methode in Laravel middleware die elke binnenkomende HTTP-request afhandelt.
    $request is het object dat alle gegevens van het inkomende HTTP-verzoek bevat (zoals URL, headers, body, etc.).
    Closure $next is een callback functie (anonieme functie) die je aanroept om de request door te geven aan de volgende
    middleware in de keten of uiteindelijk aan de controller die het verzoek moet verwerken.
    */
    public function handle($request, Closure $next)
    {
        if ($request->is('livewire/update')) {
            $host = $request->getHost();
            $centralDomains = config('tenancy.central_domains');

            if (in_array($host, $centralDomains)) {
                Livewire::setUpdateRoute(function ($handle) {
                    return Route::middleware('web')->post('/livewire/update', $handle);
                });
            } else {
                Livewire::setUpdateRoute(function ($handle) {
                    return Route::middleware([
                        InitializeTenancyByDomain::class,
                        PreventAccessFromCentralDomains::class,
                        'web',
                    ])->post('/livewire/update', $handle);
                });
            }
        }

        $host = $request->getSchemeAndHttpHost();
        config(['app.url' => $host]);

        return $next($request);
    }

}

