<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\BranchMaster; // assuming you have this

class VendorMasterController extends Controller
{
    public function index(Request $request)
    {
        try{

           $query = Vendor::query(); // Eager load branch

            if ($request->filled('search')) {
                $query->where('contact_person', 'like', '%' . $request->search . '%');
            }
            $vendor = $query->orderBy('vendor_id','desc')->paginate(env('PER_PAGE_COUNT'));
            
            $search=$request->search;
            return view('admin.vendor.index', compact('vendor','search'));
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try{

            return view('admin.vendor.add');
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'       => 'required|string|max:255',
            'contact_person'       => 'required|string|max:255',
            'phone'      => 'required|digits:10|unique:vendor_master,phone',
        ]);
        try{
            
                Vendor::create([
                    'contact_person'     => $request->contact_person,
                    'company_name'     => $request->company_name,
                    'phone'    => $request->phone,
                    'email'    => $request->email,
                    'phone2'      => $request->phone2,
                ]);
            

            return redirect()->route('vendorMaster.index')->with('success', 'Vendor created successfully.');
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try{

            $vendor = Vendor::findOrFail($id);
            return view('admin.vendor.edit', compact('vendor'));
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'company_name'       => 'required|string|max:255',
            'contact_person'       => 'required|string|max:255',
            'phone'      => 'required|digits:10|unique:vendor_master,phone,' . $id . ',vendor_id',
        ]);
         try{   
            $vendor->update($request->all());
            return redirect()->route('vendorMaster.index')->with('success', 'Vendor updated successfully.');
         } catch (\Exception $e) 
         {
                 return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
         }
    }

    public function destroy(Request $request)
    {
        try{
            
            $employee = Vendor::findOrFail($request->vendor_id);
            $employee->delete();

            return redirect()->route('vendorMaster.index')->with('success', 'Vendor deleted successfully.');
        } catch (\Exception $e) 
        {
                return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
