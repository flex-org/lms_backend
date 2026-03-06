<?php 
namespace App\Modules\V1\Utilities\Support\Contracts\Auth;

use Illuminate\Http\Request;
use App\Modules\V1\Utilities\Presentation\Http\Requests\LoginRequest;

interface AuthenticatableInterface
{
    public function login(LoginRequest $loginRequest);
    
    public function logout(Request $request);
}