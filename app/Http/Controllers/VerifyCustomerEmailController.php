<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

/**
 * Verwerkt de verificatielink die naar een klant wordt gestuurd bij registratie.
 *
 * Deze controller:
 * - Valideert de ondertekende URL en de bijbehorende hash
 * - Controleert of het e-mailadres al geverifieerd is
 * - Verifieert het e-mailadres indien nodig en activeert het account
 * - Logt de klant automatisch in na succesvolle verificatie
 * - Stuurt de klant terug naar de homepage met een bevestigingsmelding
 *
 * Dit wordt aangeroepen via een GET-verzoek naar de verificatielink.
 */

class VerifyCustomerEmailController extends Controller
{
    public function __invoke(Request $request, $id, $hash)
    {
        $customer = Customer::findOrFail($id);

        // Validate the signed URL
        if (! URL::hasValidSignature($request)) {
            abort(403, 'Invalid or expired verification link.');
        }

        // Validate the hash manually
        if (! hash_equals((string) $hash, sha1($customer->getEmailForVerification()))) {
            abort(403, 'Invalid email verification link.');
        }

        // Check if already verified
        if ($customer->hasVerifiedEmail()) {
            return redirect()->route('tenant-homepage')->with('message', 'Email already verified.');
        }

        // Mark email as verified if not yet
        if (! $customer->hasVerifiedEmail()) {
            $customer->markEmailAsVerified();
            event(new Verified($customer));
        }

        // Optionally log them in
        Auth::guard('customer')->login($customer);

        return redirect()->route('tenant-homepage')->with('message', 'Email verified successfully!');
    }
}

