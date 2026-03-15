<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerLookupController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $partners = Partner::where('name', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'code', 'phone', 'email', 'contact_person', 'tax_code', 'address']);
            
        return response()->json($partners);
    }
}
