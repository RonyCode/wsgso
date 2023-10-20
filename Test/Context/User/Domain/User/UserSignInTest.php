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
use Gso\Ws\Web\Message\Consumer;
use Gso\Ws\Web\Message\QueueAMQP;
use Gso\Ws\Web\Message\ProducerCommand;
use Gso\Ws\Web\Message\RabbitTopic;
use Gso\Ws\Web\Presentation\UserPresentationRepository;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

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


        $broker = new RabbitTopic();

        $broker->emit();

//        $broker->sendMessageAMPQ('email', 'Hello World!');


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
