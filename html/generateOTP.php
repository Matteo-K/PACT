<?php
    // function generateOTP() {
    //     $otp = '';

    //     for ($i=0; $i < 6; $i++) { 
    //         $otp .= random_int(0, 9);
    //     };
    //     return $otp;
    // }

    // echo generateOTP();

    require 'vendor/autoload.php';
    use OTPHP\TOTP;

    $otp = TOTP::create();
    $otp->setLabel('AuthentikATOR');

    $secret = $otp->getSecret();
    $code = $otp->now();

    echo "Secret : " . $secret . "\n";
    echo "OTP actuel : " . $code . "\n";
?>