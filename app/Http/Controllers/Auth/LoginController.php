<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RestServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $objRequest
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     */
    public function login(Request $objRequest)
    {
        // Check if the required parameters are provided
        try {
            $this->validateLogin($objRequest);
        } catch (ValidationException $objException) {
            return response($objException->errors(), Response::HTTP_FORBIDDEN);
        }
        // Try to login the user with the provided email and password
        if ($this->attemptLogin($objRequest)) {
            /** @var User $objUser */
            $objUser = $this->guard()->user();

            return $this->authenticated($objUser);
        }

        return response(
            [
                $this->username() => [trans('auth.failed')],
            ],
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * The user has been authenticated.
     *
     * @param  User $user
     * @return mixed
     */
    protected function authenticated($user)
    {
        // Generation of the api_token
        $user->generateToken();

        return $user;
    }


    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }


    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $rules
     * @param  array $messages
     * @param  array $customAttributes
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function validate(
        Request $request,
        array $rules,
        array $messages = [],
        array $customAttributes = []
    ) {
        // Create the validator
        /** @var Validator $objValidator */
        $objValidator = $this
            ->getValidationFactory()
            ->make($request->all(), $rules, $messages, $customAttributes);
        $objValidator->validate();

        return $this->extractInputFromRules($request, $rules);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        /** @var User|null $user */
        $user = Auth::guard('api')->user();
        $mixResult = RestServiceProvider::generateRequiredAuth();
        $intReturnCode = Response::HTTP_FORBIDDEN;
        if ($user) {
            $user->api_token = null;
            $user->save();
            $mixResult = null;
            $intReturnCode = Response::HTTP_NO_CONTENT;
        }

        return response($mixResult, $intReturnCode);
    }
}
