<?php

namespace App\Providers;

use App\Models\User;
use App\Services\MailchimpNewsletter;
use App\Services\Newsletter;
use Illuminate\Support\Facades\Gate ;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use MailchimpMarketing\ApiClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(Newsletter::class,function (){
            $clint = (new ApiClient())->setConfig([

                'apiKey' => config('services.mailchimp.key',),
                'server' => 'us21'
            ]);
            return new MailchimpNewsletter($clint);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::unguard();

        Gate::define('admin', function (User $user){
            return $user->username == 'mahmoud25';
        });
        Blade::if('admin',function (){
            return request()->user()?->can('admin');
        });
    }
}
