<?php


namespace api\tests\api;


use api\tests\ApiTester;
use common\fixtures\PostFixture;
use common\fixtures\UserFixture;

class PostCast
{
    public function _before(ApiTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php',
            ],
            'post' => [
                'class' => PostFixture::className(),
                'dataFile' => codecept_data_dir() . 'post.php',
            ],
        ]);
    }

    public function index(ApiTester $I)
    {
        $I->sendGET('/posts');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            ['title' => "First Post"],
            ['title' => "Second Post"],
            ['title' => "Third Post"],
        ]);
        $I->seeHttpHeader('X-Pagination-Total-Count', 3);
    }

    public function search(ApiTester $I)
    {
        $I->sendGET('/post?s[title]=First');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            ['title' => 'First Post'],
        ]);
        $I->dontSeeResponseContainsJson([
            ['title' => 'Second Post'],
        ]);
        $I->seeHttpHeader('X-Pagination-Total-Count', 1);
    }

    public function viewNotFound(ApiTester $I)
    {
        $I->sendGET('/posts/15');
        $I->seeResponseCodeIs(404);
    }

    public function readonly(ApiTester $I)
    {
        $I->sendPATCH('/posts/15', [
            'title' => 'New Title',
        ]);
        $I->seeResponseCodeIs(405);
    }
}