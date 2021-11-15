<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\Contact;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(){
        return view('contact');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
        ]);

        Mail::to('sujanctg15@gmail.com')->send(new Contact($data));
        // Mail::to($request->email)->send(new Contact($data));

        return redirect(route('contact.index'))->with('status', "Thank you, we'll be in touch soon");
    }

}
