<?php

namespace Gso\Ws\Test\Context\User\Domain\User;

require __DIR__ . '/../../../../../vendor/autoload.php';
include __DIR__ . '/../../../../../config/config.php';

use Dotenv\Dotenv;
use Gso\Ws\Context\User\App\UseCases\User\SignInUser\InputBoundaryUserSignIn;
use Gso\Ws\Context\User\App\UseCases\User\SignInUser\UserSignIn;
use Gso\Ws\Context\User\App\UseCases\UserExternal\SignInUserExternal\SignInUserExternal;
use Gso\Ws\Context\User\Domains\User\Account;
use Gso\Ws\Context\User\Domains\User\Profile;
use Gso\Ws\Context\User\Domains\User\User;
use Gso\Ws\Context\User\Domains\User\UserAuth;
use Gso\Ws\Context\User\Infra\Repositories\RepositoriesModel\TokenUserMemoryRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserPresentationRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserRepositoryMemory;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Shared\ValuesObjects\Cep;
use Gso\Ws\Shared\ValuesObjects\Phone;
use PHPUnit\Framework\TestCase;


class UserSignInTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function testSignInUser(): void
    {
        $dotenv = Dotenv::createUnsafeImmutable('../../../../../');
        $dotenv->load();

        $adduser =
            (new User())
                ->addAccount(
                    25,
                    3,
                    'denis',
                    'ronyanderson@gmail.com',
                    '01680562169',
                    '63981270951',
                    '',
                    0
                )->addProfile(
                    'admin',
                    '2023-01-01',
                    '2023-01-01',
                    22,
                    0
                )->addAddress(
                    'RUA DA SAUDADE',
                    '123',
                    '77060046',
                    'CASA',
                    'CENTRO',
                    'SAO PAULO',
                    'SP',
                    0
                )->addUserLogin(
                    1,
                    'ronyanderson@gmail.com',
                    '1234567a',
                    null,
                    '2023-01-01',
                    0
                )->userSignIn(
                    'ronyanderson@gmail.com',
                    '1234567a',
                );


        var_dump($adduser);

//        $inputBoundary = new InputBoundaryUserSignIn(
//            $adduser->getUserAuth()->email,
//            $adduser->getUserAuth()->password,
//            $adduser->getUserAuth()->passwordExternal,
//            $adduser->getAccount()->image,
//            $adduser->getAccount()->excluded
//        );
//
//        $output = (new UserSignIn(
//            new UserRepositoryMemory(),
//            new TokenUserMemoryRepository(),
//            new SignInUserExternal(
//                new UserRepositoryMemory(),
//                new TokenUserMemoryRepository()
//            ),
//            new PublishEvents(),
//        ))->execute($inputBoundary);
//        $this->assertSame('ronyanderson@gmail.com', (string)$adduser[0]->email);
//
//        $result = (new UserPresentationRepository())->outPut($output);
//        $this->assertEquals(202, $result['code']);
//
//
//        var_dump($adduser);
    }
}
