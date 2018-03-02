<?php

namespace App\Helpers;

class MyFxBookHelper {
    public function auth(string $username, string $password) {
        $content = file_get_contents("https://www.myfxbook.com/api/login.json?email=$username&password=$password");

        $data = json_decode($content, true);

        return $data['error'] ? null : $data['session'];
    }

    public function getAccounts(string $token) {
        $content = file_get_contents("https://www.myfxbook.com/api/get-my-accounts.json?session=$token");

        return json_decode($content, true);
    }
}