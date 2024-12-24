<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class LabAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // Previous implementation remains the same
        $institutions = Customer::distinct()->pluck('institution')->toArray();
        $purposes = Customer::distinct()->pluck('purpose_of_usage')->toArray();
        $equipment = Customer::distinct()->pluck('equipment_used')->toArray();

        $query = Customer::select('*');

        if ($request->filled('institution')) {
            $query->where('institution', $request->institution);
        }

        if ($request->filled('purpose')) {
            $query->where('purpose_of_usage', $request->purpose);
        }

        if ($request->filled('equipment')) {
            $query->where('equipment_used', $request->equipment);
        }

        $submissions = $query->get();
        $equipmentUsage = $this->getEquipmentUsageData();
        $institutionDistribution = $this->getInstitutionDistributionData();

        return view('mainpage', compact(
            'institutions', 
            'purposes', 
            'equipment', 
            'submissions', 
            'equipmentUsage',
            'institutionDistribution'
        ));

    }

    public function getCustomerDetails($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        return response()->json($customer);
    }


    public function deleteCustomer($customerId)
    {
        try {
            $customer = Customer::findOrFail($customerId);
            $customer->delete();

            return response()->json([
                'success' => true, 
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting customer: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to delete customer'
            ], 500);
        }
    }
  

    protected function getEquipmentUsageData($query = null)
    {
        $query = $query ?? Customer::query();
        return $query->select('equipment_used', \DB::raw('COUNT(*) as usage_count'))
            ->groupBy('equipment_used')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->equipment_used => $item->usage_count];
            })
            ->toArray();
    }
    
    protected function getInstitutionDistributionData($query = null)
    {
        $query = $query ?? Customer::query();
        return $query->select('institution', \DB::raw('COUNT(*) as visitor_count'))
            ->groupBy('institution')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->institution => $item->visitor_count];
            })
            ->toArray();
    }
    
}