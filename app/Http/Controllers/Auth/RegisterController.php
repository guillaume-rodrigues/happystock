<?php

namespace App\Http\Controllers\Auth;

use App\Providers\RestServiceProvider;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            User::FIELD_NAME => 'required|string|max:255',
            User::FIELD_EMAIL => 'required|string|email|max:255|unique:users',
            User::FIELD_PASS => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $objValidator = $this->validator($request->all());
        if ($objValidator->fails()) {
            return response(
                RestServiceProvider::generateCustomError($objValidator->errors()),
                Response::HTTP_BAD_REQUEST
            );
        }

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            User::FIELD_NAME => $data[User::FIELD_NAME],
            User::FIELD_EMAIL => $data[User::FIELD_EMAIL],
            User::FIELD_PASS => Hash::make($data[User::FIELD_PASS]),
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    protected function registered(Request $request, $user)
    {
        $user->generateToken();

        return response($user->toArray(), Response::HTTP_CREATED);
    }
}
