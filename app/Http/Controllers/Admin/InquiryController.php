<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inquiry;
use Illuminate\Support\Facades\DB;

class InquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $inquiries = Inquiry::orderBy('inquiry_id', 'DESC')->where(['iStatus' => 1, 'isDelete' => 0])->paginate(env('PER_PAGE_COUNT'));
            return view('admin.inquiries.index', compact('inquiries'));
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
        public function view($id)
    {
        try
        {
            $inquiry = Inquiry::orderBy('inquiry_id', 'DESC')->where(['iStatus' => 1, 'isDelete' => 0,'inquiry_id'=>$id])->first();
            return view('admin.inquiries.view', compact('inquiry'));
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }



    public function delete(Request $request)
    {
        try{
            DB::table('inquiry')->where(['iStatus' => 1, 'isDelete' => 0, 'inquiry_id' => $request->inquiry_id])->delete();

            return redirect()->route('Inquiry.index')->with('success', 'Deleted Successfully!.');
        } catch (\Exception $e) {

        report($e);
 
        return false;
    }
    }
}
