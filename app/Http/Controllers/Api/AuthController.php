<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\Auth\DeleteAccountRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\LogoutRequest;
use App\Http\Requests\Api\Auth\NewPasswordRequest;
use App\Http\Requests\Api\Auth\OtpVerifyRequest;
use App\Http\Requests\Api\Auth\RecoverVerifyRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\SocialRequest;
use App\Http\Requests\Api\Auth\VerifyRequest;
use App\Http\Requests\Api\SocialConnectRequest;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\ForgotOtp;
use App\Mail\OtpSend;
use App\Models\OtpVerify;
use App\Models\SocialConnect;
use App\Models\User;
use App\Models\UserDevice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function verify(VerifyRequest $request)
    {
        $otp = random_int(100000, 999999);
        $mail_details = [
            'body' => $otp
        ];
        // return ($mail_details);
        Mail::to($request->email)->send(new OtpSend($mail_details));
        $user = new OtpVerify();
        $user->email = $request->email;
        $user->otp = $otp;
        $user->save();
        return response()->json([
            'status' => true,
            'action' => 'User verify otp send',
        ]);
    }

    public function otpVerify(OtpVerifyRequest $request)
    {
        $user = OtpVerify::where('email', $request->email)->latest()->first();
        // return ( $user);
        if ($user) {
            if ($request->otp == $user->otp) {
                $user = OtpVerify::where('email', $request->email)->delete();
                return response()->json([
                    'status' => true,
                    'action' => 'OTP verify',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => 'OTP is invalid, Please enter correct OTP',
                ]);
            }
        }
    }

    public function register(RegisterRequest $request)
    {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->timezone = $request->timezone;
        $user->save();

        $userdevice = new UserDevice();
        $userdevice->user_id = $user->uuid;

        $userdevice->device_name = $request->device_name ?? 'No name';
        $userdevice->device_id = $request->device_id ?? 'No ID';
        $userdevice->timezone = $request->timezone ?? 'No Time';
        $userdevice->token = $request->fcm_token ?? 'No token';
        $userdevice->save();

        // $token = $user->createToken('here-token-name')->plainTextToken;
        $new  = User::where('uuid', $user->uuid)->first();
        // $new->token = $token;

        return response()->json([
            'status' => true,
            'action' => 'User registered successfully',
            'data' => $new,
        ]);
    }

    public function login(LoginRequest $request)
    {
        //   return ($request);
        $user = User::Where('email', $request->email)->first();

        // return($user);
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $userdevice = new UserDevice();
                $userdevice->user_id = $user->uuid;
                $userdevice->device_name = $request->device_name ?? 'No name';
                $userdevice->device_id = $request->device_id ?? 'No ID';
                $userdevice->timezone = $request->timezone ?? 'No Time';
                $userdevice->token = $request->fcm_token ?? 'No tocken';
                $userdevice->save();

                return response()->json([
                    'status' => true,
                    'action' => "Login successfully",
                    'data' => $user,
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => 'Password is invalid, Please enter correct Password',
                ]);
            }
        }
        return response()->json([
            'status' => false,
            'action' => "No account found against this email",

        ]);
    }
    public function recoverPassword(RecoverVerifyRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        // return ($user);
        if ($user) {
            $otp = random_int(100000, 999999);
            //     return ($otp);
            $user = new OtpVerify();
            $user->email = $request->email;
            $user->otp = $otp;
            $user->save();
            $mailDetails = [
                'body' => $otp,
                'name' => $user->name
            ];
            // return (  $mailDetails);
            Mail::to($request->email)->send(new ForgotOtp($mailDetails));
            return response()->json([
                'status' => true,
                'action' => 'Otp sent for verification',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'action' => 'Account not found'
            ]);
        }
    }

    public function newPassword(NewPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {

            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'action' => "New password should not be same as old password",
                ]);
            } else {
                $user->update([
                    'password' => Hash::make($request->new_password)
                ]);
                return response()->json([
                    'status' => true,
                    'action' => "Current password is updated"
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => 'This email  is not registered'
            ]);
        }
    }

    public function social(SocialRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user = User::find($user->uuid);
            $user->platform = $request->platform;
            $user->platform_id = $request->platform_id;
            $user->save();
            return response()->json([
                'status' => true,
                'action' => 'Login successfully',
                'date' => $user
            ]);
        } else {
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->platform = $request->platform;
            $user->platform_id = $request->platform_id;
            $user->save();

            $userdevice = new UserDevice();
            $userdevice->user_id = $user->uuid;
            $userdevice->device_name = $request->device_name ?? 'No name';
            $userdevice->device_id = $request->device_id ?? 'No ID';
            $userdevice->timezone = $request->timezone ?? 'No Time';
            $userdevice->token = $request->fcm_token ?? 'No token';
            $userdevice->save();

            $newuser  = User::where('uuid', $user->uuid)->first();

            return response()->json([
                'status' => true,
                'action' => 'User registered successfully',
                'data' => $newuser
            ]);
        }
    }

    public function logout(LogoutRequest $request)
    {
        // return ($request);
        UserDevice::where('user_id', $request->user_id)->where('device_id', $request->device_id)->delete();
        return response()->json([
            'status' => true,
            'action' => 'User logged out successfully'
        ]);
    }

    public function deleteAccount(DeleteAccountRequest $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'action' => "Account deleted successfully",
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'action' => 'Please enter correct password',
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => "User not found"
            ]);
        }
    }


    public function changePassword(ChangePasswordRequest $request)
    {
        $user = User::find($request->user_id);
        if ($user) {
            // return($user->uuid);
            // return($user->password);
            if (Hash::check($request->old_password, $user->password)) {
                if (Hash::check($request->new_password, $user->password)) {
                    return response()->json([
                        'status' => false,
                        'action' => "New password is same as old password",
                    ]);
                } else {
                    $user->update([
                        'password' => Hash::make($request->new_password)
                    ]);
                    return response()->json([
                        'status' => true,
                        'action' => "Password updated successfully",
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'action' => "Current password is wrong",
                ]);
            }
        } else {
            return response()->json([
                'status' => false,
                'action' => 'User not found'
            ]);
        }
    }
}
