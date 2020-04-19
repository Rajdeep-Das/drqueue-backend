<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class PhoneLogin extends Model{

    public $fillable = ['phone', 'otp'];

    public function user()
    {
        return $this->hasOne(\App\Models\User::class, 'phone', 'phone');
    }

    // Custom OTP Generation
    public static function createForPhoneLogin($phone)
    {
        return self::create([
            'phone' => $phone,
            'otp' => self::generateOTP()
        ]);
    }


    public static function validFromOtp($otp,$phone)
    {
        return self::where('otp', $otp)
            ->where('phone',$phone)
            ->where('created_at', '>', Carbon::parse('-15 minutes'))
            ->first();
    }


    // generate OTP
    protected static function generateOTP(){
        $min = str_pad(1, 4, 0);
        $max = str_pad(9, 4, 9);
        return random_int($min, $max);
    }


}
