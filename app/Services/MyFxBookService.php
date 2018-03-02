<?php
namespace App\Services;

use App\Exceptions\MyFXBookException;
use App\Helpers\MyFxBookHelper;

class MyFxBookService
{
    private $myFxBookHelper;

    private $token;

    public function __construct(MyFxBookHelper $myFxBookHelper) {
        $this->myFxBookHelper = $myFxBookHelper;
    }

    public function auth(string $username, string $password) {
        $this->token = $this->myFxBookHelper->auth($username, $password);

        if(is_null($this->token)) {
            throw new MyFXBookException('bad_credentials');
        }

        return $this->token;
    }

    public function getAccount(int $id) {
        $result = null;
        $list = $this->myFxBookHelper->getAccounts($this->token);

        foreach ($list['accounts'] as $account) {
            if($account['id'] == $id) {
                $result = $account;
                break;
            }
        }

        if(is_null($result)) {
            throw new MyFXBookException('account_not_found');
        }

        return $result;
    }
}