<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Show the form for creating a new customer entry.
     */
    public function create(): View
    {
        return view('create');
    }

    /**
     * Store a newly created customer in the database.
     */
    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        // Validate the request using the StoreCustomerRequest
        $validatedData = $request->validated();

        // Set entry datetime to current time if not provided
        $validatedData['entry_datetime'] = $validatedData['entry_datetime'] ?? now();

        // Create the customer record
        $customer = Customer::create($validatedData);

        // Redirect with a success message
        return redirect()->route('customers.create')
            ->with('success', 'Customer entry created successfully.');
    }
}