<?php

namespace Gso\Ws\Test\Context\User\Domain\User;

require __DIR__ . '/../../../../../vendor/autoload.php';
include __DIR__ . '/../../../../../config/config.php';

use Dotenv\Dotenv;
use Gso\Ws\Context\User\App\UseCases\User\SignInUser\InputBoundaryUserSignIn;
use Gso\Ws\Context\User\App\UseCases\User\SignInUser\UserSignIn;
use Gso\Ws\Context\User\Domains\User\User;
use Gso\Ws\Context\User\Infra\Connection\GlobalConnection;
use Gso\Ws\Context\User\Infra\User\Repository\TokenUserRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserAuthRepository;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Web\Message\BrokerConsumerMessager;
use Gso\Ws\Web\Message\BrokerMessager;
use Gso\Ws\Web\Presentation\UserPresentationRepository;
use PHPUnit\Framework\TestCase;

class UserSignInTest extends TestCase
{
    /**
     * @throws \JsonException
     * @throws \Exception
     */
    public function testSignInUser(): void
    {
        $dotenv = Dotenv::createUnsafeImmutable('../../../../../');
        $dotenv->load();


        $broker = new BrokerMessager();
//        $broker
//            ->addQueue('teste')
//            ->sentMessageBroker(
//                'teste',
//                '',
//                'teste de loggin com class borker id ' . random_int(
//                    0,
//                    99
//                )
//            );
        $broker->consumeMessageBroker('teste', '', false, true, false, false);



































//        $adduser =
//            (new User()
//            )
//                ->addAccount(
//                    25,
//                    3,
//                    'denis',
//                    'ronyanderson@gmail.com',
//                    '01680562169',
//                    '63981270951',
//                    '',
//                    0
//                )->addProfile(
//                    1,
//                    'admin',
//                    '2023-01-01',
//                    '2023-01-01',
//                    22,
//                    0
//                )->addAddress(
//                    'RUA DA SAUDADE',
//                    '123',
//                    '77060046',
//                    'CASA',
//                    'CENTRO',
//                    'SAO PAULO',
//                    'SP',
//                    0
//                )->addUserLogin(
//                    1,
//                    'ronyanderson@gmail.com',
//                    '1234567a',
//                    null,
//                    '2023-01-01',
//                    0
//                )->userSignIn(
//                    'ronyanderson@gmail.com',
//                    '1234567a',
//                );
//
//        $obj    = $adduser->getUserAuth()::userAuthSerialize(
//            1,
//            'ronyanderson@gmail.com',
//            '1234567a',
//            null,
//            '2023-01-01',
//            0
//        );
//        $objAcc = $adduser->getAccount()::accountSerialize(
//            2,
//            $adduser->getUserAuth()->id,
//            'denis',
//            'ronyanderson@gmail.com',
//            '01680562169',
//            '63981270951',
//            '',
//            0
//        );
//
//        $inputBoundary = new InputBoundaryUserSignIn(
//            $obj->email,
//            $obj->password,
//            0,
//            date('Y-m-d H:i:s'),
//            $objAcc->excluded
//        );
//
//        $globalConnection = new GlobalConnection();
//        $output           = (new UserSignIn(
//            new UserAuthRepository($globalConnection),
//            new TokenUserRepository($globalConnection),
//            new PublishEvents(),
//        ))->execute($inputBoundary);
//        $this->assertSame('ronyanderson@gmail.com', (string)$obj->email);
//        $result = (new UserPresentationRepository())->outPut($output);
//        $this->assertEquals(202, $result['code']);
    }
}
