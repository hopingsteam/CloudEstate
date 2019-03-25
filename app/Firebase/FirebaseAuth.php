<?php
namespace App\Firebase;
use Firebase\Auth\Token\Verifier;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Facade;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use App\Firebase\User;
use Illuminate\Contracts\Auth\UserProvider;

class FirebaseAuth extends Facade
{
    protected static $user;

    public function __construct(){

    }

}