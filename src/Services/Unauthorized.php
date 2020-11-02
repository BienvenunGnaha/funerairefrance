<?php


namespace App\Services;


class Unauthorized
{

    public function responseData(){
        return array(
            'err_code' => 1,
            'err_message' => '',
            'isRefresh' => false,
            'token' => '',
            'refresh_token' => '',
            'u_ic' => '',
            'user' => []);
    }

}