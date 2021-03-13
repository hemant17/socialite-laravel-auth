<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Socialite;
use App\Model\Loginhistory;
use Illuminate\Support\Facades\Http;

class SocialAuthController extends Controller
{
    public function redirectToSocial($social_type)
    {
        return Socialite::driver($social_type)->redirect();
    }

    public function handleCallback($social_type)
    {
        $social = Socialite::driver($social_type)->user();
        $id = $social_type.'_id';
        $finduser = User::where($id, $social->id)->first();
        if ($finduser) {
            Auth::login($finduser);
            $this->loginHistory($finduser);
            return redirect('/dashboard');
        } else {
            $findUserEmail = User::where('email', $social->email)->first();
            if($findUserEmail){
                return redirect()->to('/login')->with('error', 'This Email is already present Please connect it with your nextlevebot account');
            }
            else{
                    $newUser = User::create([
                            'name' => $social->name,
                            'email' => $social->email,
                            $id => $social->id,
                            'password' => bcrypt('secret'),
                            'email_verified_at' => now(),
                            'referal_code'=> uniqid(),
                        ]);
                    Auth::login($newUser);
                    $this->loginHistory($newUser);
                    return redirect('/dashboard');
            }
            
        }
    }

    public function loginHistory($user)
    {
        $response = Http::get('http://ip-api.com/json/'.request()->getClientIp());
         $data = json_decode($response->body());
         
         if($data->status == 'success'){
            $user->update([
                'last_login_time' => now(),
                'last_login_ip' => request()->getClientIp(),
            ]);
            Loginhistory::create([
                'user_id' => $user->id,
                'ip' => request()->getClientIp(),
                'device' => request()->userAgent(),
                'country' => $data->country
            ]);
         }
    }
}
