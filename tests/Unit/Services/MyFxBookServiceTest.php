<?php

namespace Tests\Unit\Services;

use App\Exceptions\MyFXBookException;
use App\Helpers\MyFxBookHelper;
use App\Services\MyFxBookService;
use Tests\TestCase;

class MyFxBookServiceTest extends TestCase
{
    /**
     * Permet de tester la connexion dans un cas nominal
     */
    public function testAuth_NominalCase_Success() {
        // Arrange
        $mockMyFxBookHelper = \Mockery::mock(MyFxBookHelper::class);

        $mockMyFxBookHelper->shouldReceive('auth')->once()->andReturn('token');

        $myFxBookHelper = new MyFxBookService($mockMyFxBookHelper);

        // Act
        $token = $myFxBookHelper->auth(config('myfxbook.username'), config('myfxbook.password'));

        // Assert
        $this->assertEquals($token, 'token');
    }

    /**
     * Permet de tester la connexion avec un erreur
     */
    public function testAuth_BadCredentialCase_ExpectException() {
        // Arrange
        $mockMyFxBookHelper = \Mockery::mock(MyFxBookHelper::class);

        $mockMyFxBookHelper->shouldReceive('auth')->once()->andReturn(null);

        $myFxBookHelper = new MyFxBookService($mockMyFxBookHelper);

        // Assert
        $this->expectException(MyFXBookException::class);

        // Act
        $myFxBookHelper->auth('username', 'password');
    }

    /**
     * Permet de récupérer les données un compte en particulier
     */
    public function testGetAccount_NominalCase_Success() {
        // Arrange
        $mockMyFxBookHelper = \Mockery::mock(MyFxBookHelper::class);

        $mockMyFxBookHelper->shouldReceive('getAccounts')->once()->andReturn(json_decode('{
     "error": false,
     "message": "",
     "accounts":  [
        {
       "id": 12345,
       "name": "Holy Grail",
       "description": "Super duper MA+CCI trading system.",
       "accountId": 1013230,
       "gain": 8.92,
       "absGain": 8.92,
       "daily": "0.04",
       "monthly": "1.25",
       "withdrawals": 0,
       "deposits": 10000,
       "interest": 11.1,
       "profit": 892.45,
       "balance": 10892.45,
       "drawdown": 53.53,
       "equity": 10892.45,
       "equityPercent": 100,
       "demo": true,
       "lastUpdateDate": "03/01/2010 10:14",
       "creationDate": "08/06/2009 08:13",
       "firstTradeDate": "04/21/2008 12:18",
       "tracking": 21,
       "views": 549,
       "commission":0,
       "currency":"USD",
       "profitFactor": 0.3,
       "pips": 81.20,
       "invitationUrl": "http://www.myfxbook.com/members/john101/anyone/347/SDa45X5TSkdIsXg8",
       "server":    {
        "name": "Alpari UK"
       }
      }
     ]
    }', true));

        $myFxBookHelper = new MyFxBookService($mockMyFxBookHelper);

        // Act
        $result = $myFxBookHelper->getAccount(12345);

        // Assert
        $this->assertEquals($result['name'], 'Holy Grail');
    }

    /**
     * Emet une erreur si le compte n'existe pas
     */
    public function testGetAccount_AccountNotFoundCase_ExpectException() {
        // Arrange
        $mockMyFxBookHelper = \Mockery::mock(MyFxBookHelper::class);

        $mockMyFxBookHelper->shouldReceive('getAccounts')->once()->andReturn(json_decode('{
     "error": false,
     "message": "",
     "accounts":  [
        {
       "id": 12345,
       "name": "Holy Grail",
       "description": "Super duper MA+CCI trading system.",
       "accountId": 1013230,
       "gain": 8.92,
       "absGain": 8.92,
       "daily": "0.04",
       "monthly": "1.25",
       "withdrawals": 0,
       "deposits": 10000,
       "interest": 11.1,
       "profit": 892.45,
       "balance": 10892.45,
       "drawdown": 53.53,
       "equity": 10892.45,
       "equityPercent": 100,
       "demo": true,
       "lastUpdateDate": "03/01/2010 10:14",
       "creationDate": "08/06/2009 08:13",
       "firstTradeDate": "04/21/2008 12:18",
       "tracking": 21,
       "views": 549,
       "commission":0,
       "currency":"USD",
       "profitFactor": 0.3,
       "pips": 81.20,
       "invitationUrl": "http://www.myfxbook.com/members/john101/anyone/347/SDa45X5TSkdIsXg8",
       "server":    {
        "name": "Alpari UK"
       }
      }
     ]
    }', true));

        $myFxBookHelper = new MyFxBookService($mockMyFxBookHelper);

        // Assert
        $this->expectException(MyFXBookException::class);

        // Act
        $myFxBookHelper->getAccount(54321);
    }

}
