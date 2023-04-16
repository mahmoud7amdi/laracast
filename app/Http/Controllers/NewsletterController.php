<?php

namespace App\Http\Controllers;

use App\Services\MailchimpNewsletter;
use App\Services\Newsletter;
use Illuminate\Validation\ValidationException;
use Exception;

class NewsletterController extends Controller
{
    public function __invoke(Newsletter $newsletter)
    {
        request()->validate(['email' => 'required|email']);


        try {
            $newsletter->subscribe(request('email'));
        }catch (Exception $e){
            throw  ValidationException::withMessages([
                'email' => 'this email could not be added'
            ]);
        }

        return redirect('/')
            ->with('success','you are new signed up for our newsletter');
    }
}
