<?php

namespace Pitangent\Workflow\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Notification;
use Pitangent\Workflow\Notifications\Register;
use Pitangent\Workflow\Notifications\ResetPassword;
use Pitangent\Workflow\Notifications\ChangePassword;
use Pitangent\Workflow\Notifications\RequestPassword;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

trait AuthenticatableTrait
{
    /*
    |-------------------------------------------------------------------------------
    | Login a user
    |-------------------------------------------------------------------------------
    | URL:              /api/auth/login
    | Method:           POST
    | @param $request   Illuminate\Http\Request
    | @return           Illuminate\Http\JsonResponse
    */
    public function authenticate(Request $request) : JsonResponse
    {
        try {
            $rules = [
                'email' => 'required|email',
                'password' => 'required'
            ];
            $validator = Validator::make( $request->all(), $rules );

            if( $validator->fails() )
                return $this->responseMessages( $validator->errors() );

            $credentials = $request->only( 'email', 'password' );

            if ( !$token = JWTAuth::attempt( $credentials ) ) {
                $this->message = 'Invalid credentials';
                return $this->response( NULL, FALSE, Response::HTTP_BAD_REQUEST );
            }

            $this->user = auth()->user();

            return $this->response( [ "user" => $this->user, "token" => $token ] );
        } catch (JWTException $e) {
            $this->message = 'Could not create token';
            return $this->response( NULL, FALSE, Response::HTTP_INTERNAL_SERVER_ERROR );
        }
    }

    /*
    |-------------------------------------------------------------------------------
    | Register a user
    |-------------------------------------------------------------------------------
    | URL:              /api/auth/register
    | Method:           POST
    | @param $request   Illuminate\Http\Request
    | @return           Illuminate\Http\JsonResponse
    */
    public function register( Request $request ) : JsonResponse
    {
        $params = $request->all();
        $validation = Validator::make( $params, $this->User::CREATE_VALIDATIONS );

        if( $validation->fails() )
            return $this->responseMessages( $validation->errors() );

        $params['password'] = Hash::make( $params['password'] );
        $user = $this->User::create( $params );

        //Notification
        $check = config( 'pitangent.NOTIFICATIONS.REGISTER.ENABLE', TRUE );
        if( $check)  {
            Notification::send( $user, new Register( $user ) );
        }

        $this->message = 'Successfully Register';
        return $this->response( $user, TRUE, Response::HTTP_CREATED );
    }

    /*
    |-------------------------------------------------------------------------------
    | Get authenticate user
    |-------------------------------------------------------------------------------
    | URL:              /api/auth/me
    | Method:           GET
    | @param $request   Illuminate\Http\Request
    | @return           Illuminate\Http\JsonResponse
    */
    public function me( Request $request ) : JsonResponse
    {
        $user = auth()->user();

        return $this->response( $user );
    }

    /*
    |-------------------------------------------------------------------------------
    | Refresh the expire token
    |-------------------------------------------------------------------------------
    | URL:              /api/auth/me
    | Method:           POST
    | @param $request   Illuminate\Http\Request
    | @return           Illuminate\Http\JsonResponse
    */
    public function refresh()
    {
        $token = null;
        try {
            $token = JWTAuth::refresh();
        } catch (Exception $e) {
            if ( $e instanceof TokenInvalidException )
                $this->message = "Token is invalid";
            else if ( $e instanceof TokenExpiredException )
                $this->message = "Token is expired";
            else
                $this->message = "Something is wrong";
            return $this->response( NULL, FALSE, 401 );
        }

        $data = [
            'token' => $token,
            'user'  =>  auth()->user()
        ];
        $this->message = 'Token successfully refresh';
        return $this->response($data);
    }
    /*
    |-------------------------------------------------------------------------------
    | Request authenticated user password
    |-------------------------------------------------------------------------------
    | URL:              /api/auth/password/request
    | Method:           GET
    | @param $request   Illuminate\Http\Request
    | @return           Illuminate\Http\JsonResponse
    */
    public function requestPassword( Request $request ) : JsonResponse
    {
        $rules = [
            'email' => 'required|email',
        ];
        $validator = Validator::make( $request->all(), $rules );

        if( $validator->fails() )
            return $this->responseMessages( $validator->errors() );

        $user = $this->User::where( $request->only('email') )->firstOrfail();

        $token = app('auth.password')->createToken( $user );

        //Notification
        $check = config( 'pitangent.NOTIFICATIONS.REQUEST_PASSWORD');
        if( $check)  {
            Notification::send( $user, new RequestPassword( $user, $token ) );
        }

        $this->message = "Reset email successfully sent.";
        return $this->response( null );
    }

    /*
    |-------------------------------------------------------------------------------
    | Reset authenticated user password
    |-------------------------------------------------------------------------------
    | URL:              /api/auth/password/reset
    | Method:           GET
    | @param $request   Illuminate\Http\Request
    | @return           Illuminate\Http\JsonResponse
    */
    public function resetPassword( Request $request ) : JsonResponse
    {
        $rules = [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6',
            'confirmaPassword' => 'required|same:password',
        ];
        $validator = Validator::make( $request->all(), $rules );

        if( $validator->fails() )
            return $this->responseMessages( $validator->errors() );

        $user = $this->User::where( $request->only('email') )->firstOrfail();
        $token = $request->get('token');
        $check = app('auth.password')->tokenExists( $user, $token );

        if( !$check ) {
            $this->message = "Invalid token";
            return $this->response( null , FALSE, 400 );
        }

        $user->password = Hash::make( $request->password );
        $user->save();
        app('auth.password')->deleteToken( $user );
        //Notification
        $check = config( 'pitangent.NOTIFICATIONS.RESET_PASSWORD.ENABLE', TRUE );
        if( $check)  {
            Notification::send( $user, new ResetPassword( $user, $token ) );
        }

        $this->message = "Password reset successfully done";
        return $this->response( null );
    }

    /*
    |-------------------------------------------------------------------------------
    | Change authenticated user password
    |-------------------------------------------------------------------------------
    | URL:              /api/auth/password/change
    | Method:           Post
    | @param $request   Illuminate\Http\Request
    | @return           Illuminate\Http\JsonResponse
    */
    public function changePassword( Request $request ) : JsonResponse
    {
        $rules = [
            'password' => 'required|min:6',
            'newPassword' => 'required|min:6',
            'confirmaNewPassword' => 'required|same:newPassword',
        ];
        $validator = Validator::make( $request->all(), $rules );

        if( $validator->fails() )
            return $this->responseMessages( $validator->errors() );

        $check = Hash::check( $request->password, $this->user->password );

        if( !$check ) {
            $this->message = "Invalid old password";
            return $this->response( null , FALSE, 400 );
        }

        $this->user->password = Hash::make( $request->password );
        $this->user->save();
        //Notification
        $check = config( 'pitangent.NOTIFICATIONS.CHANGE_PASSWORD.ENABLE', TRUE );
        if( $check)  {
            Notification::send( $this->user, new ChangePassword( $this->user ) );
        }
        $this->message = "Password successfully changed";
        return $this->response( null );
    }
}
