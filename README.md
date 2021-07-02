# Install
Require this package in your composer.json and update composer. This will download the package and the dependencies libraries also.

```Shell
composer require lsnepomuceno/laravel-locasms
```
# Sign up before you start
Register at [LocaSMS](http://locasms.com.br) to obtain login and password.

# Usage
```PHP
<?php

use LSNepomuceno\LaravelLocasms\SMS;

class ExampleController() {
    public function dummyFunction(){
       $sms = new SMS('login', 'password');
       
       // IMPORTANT: 
       //    - Service is limited to 200 characters
       //    - Sending SMS works only in Brazil
       //    - The country code has been discarded
       $response = $sms->send(['+55(27)99999-8888', '2799988-7766'], 'Test message from Laravel integration!!!'));
       
       // See http client for more details: https://laravel.com/docs/8.x/http-client
       dd($respone->body());
    }
}

```
