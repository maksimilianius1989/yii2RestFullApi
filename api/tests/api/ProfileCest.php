<?php


namespace api\tests\api;


use api\tests\ApiTester;
use common\fixtures\TokenFixture;
use common\fixtures\UserFixture;

class ProfileCest
{
    public function _before(ApiTester $i)
    {
        $i->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
            'token' => [
                'class' => TokenFixture::className(),
                'dataFile' => codecept_data_dir() . 'token.php',
            ],
        ]);
    }

    public function access(ApiTester $i)
    {
        $i->sendGET('/profile');
        $i->seeResponseCodeIs(401);
    }

    public function authenticated(ApiTester $I)
    {
        $I->amBearerAuthenticated('token-corrent');
        $I->sendGET('/profile');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'id' => 1,
            'username' => 'erau',
            'email' => 'sfriesen@jenkins.info',
        ]);
    }

    public function expired(ApiTester $i)
    {
        $i->amBearerAuthenticated('token-expired');
        $i->sendGET('/profile');
        $i->seeResponseCodeIs(401);
    }
}