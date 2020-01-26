<?php

namespace App\Http\Controllers\Google2FA;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Google2FA;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '2fa']);
    }

    /**
     * @param Request $request
     * @param Google2FA $google2fa
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     */
    public function enable(Request $request, Google2FA $google2fa)
    {
        $user = $request->user();

        $secretKey = $request->session()->get('secretKey') ?? $google2fa->generateSecretKey(config('google2fa.secret_key_length'));
        $qrCode = $google2fa->getQRCodeInline(config('app.name'), $user->email, $secretKey);

        $request->session()->flash('secretKey', $secretKey);

        return view('google2fa.enable')->with([
            'qrCode' => $qrCode,
        ]);
    }

    public function check(Request $request, Google2FA $google2fa)
    {
        $user = $request->user();

        $secretKey = $request->session()->get('secretKey');
        $error = 'You entered an invalid key, please try again.';

        try {
            if ($google2fa->verifyKey($secretKey, $request->get('key'))) {
                $user->{config('google2fa.otp_secret_column')} = $secretKey;
                $user->save();

                $google2fa->login();

                return redirect()->route('home');
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        $request->session()->reflash();

        return redirect()->back()->with('warning', trans($error));
    }

    public function authenticate()
    {
        return redirect()->route('home');
    }

    public function disable(Request $request)
    {
        $user = $request->user();
        $user->{config('google2fa.otp_secret_column')} = null;
        $user->save();

        return redirect()->back();
    }
}