<?php

namespace App\Providers;

use App\Models\General;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        $general = general();
        


        \Config::set("services.facebook.client_id", $general->fb_app_id);
        \Config::set("services.facebook.client_secret", $general->fb_app_secret);
        \Config::set("services.facebook.redirect", $general->fb_app_secret);

        \Config::set("services.google.client_id", $general->google_client_id);
        \Config::set("services.google.client_secret", $general->google_client_secret);
        \Config::set("services.google.redirect", $general->google_client_redirect_url);
        
        \Config::set("mail.mailers.smtp.transport", $general->mail_driver);
        // Symfony Mailer (Laravel 9+) needs a string host and an int port; an empty
        // setting must fail as a normal exception (caught by try/catch), not a TypeError.
        \Config::set("mail.mailers.smtp.host", (string) $general->mail_host);
        \Config::set("mail.mailers.smtp.port", is_numeric($general->mail_port) ? (int) $general->mail_port : null);
        \Config::set("mail.mailers.smtp.encryption", $general->mail_encryption);
        \Config::set("mail.mailers.smtp.username", $general->mail_username);
        \Config::set("mail.mailers.smtp.password", $general->mail_password);



    }
}
