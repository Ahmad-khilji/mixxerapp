<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function list(Request $request)
    {
        $user = User::get();
        return view('user.index', compact('user'));
    }

    public function faqs()
    {
        $faqs = Faq::all();
        return view('faq', compact('faqs'));
    }

    public function addFaq(Request $request)
    {
        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        return redirect()->back();
    }

    public function editFaq(Request $request, $id)
    {
        $faq = Faq::find($id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
        return redirect()->back();
    }

    public function deleteFaq($id)
    {
        $faq = Faq::find($id);
        $faq->delete();
        return redirect()->back();
    }
}
