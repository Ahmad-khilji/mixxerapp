<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;

class AdminInterestController extends Controller
{
    public function interests()
    {
        $interests = Interest::all();
        return view('interest', compact('interests'));
    }

    public function addInterest(Request $request)
    {
        $interests = new Interest();
        $interests->interest = $request->interest;
        $interests->save();

        return redirect()->back();
    }

    public function editInterest(Request $request, $id)
    {
        $interests = Interest::find($id);
        $interests->interest = $request->interest;
        $interests->save();
        return redirect()->back();
    }

    public function deleteInterest($id)
    {
        $interests = Interest::find($id);
        $interests->delete();
        return redirect()->back();
    }
}
