<?php

namespace App\Http\Middleware;

use App\Models\Country;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CountryCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $countryId =Auth::user()->last_selected_country_id;

        if(!$countryId){
            return redirect()->route('country.index');
        }
        session(['activeCountry' => Country::find($countryId)]);
        return $next($request);
    }
}
