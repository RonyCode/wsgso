<?php

namespace Gso\Ws\Test\Domain\User;

use Gso\Ws\App\UseCases\User\SignInUser\InputBoundaryUserSignIn;
use Gso\Ws\App\UseCases\User\SignInUser\UserSignIn;
use Gso\Ws\App\UseCases\UserExternal\SignInUserExternal\SignInUserExternal;
use Gso\Ws\Domains\Event\PublishEvents;
use Gso\Ws\Domains\User\Events\LogUserSignIn;
use Gso\Ws\Domains\User\Events\UserSignIn as UserSignInEvent;
use Gso\Ws\Domains\User\Token;
use Gso\Ws\Domains\User\User;
use Gso\Ws\Domains\ValuesObjects\Email;
use Gso\Ws\Infra\Repositories\RepositoriesModel\TokenUserMemoryRepository;
use Gso\Ws\Infra\User\Repository\UserPresentationRepository;
use Gso\Ws\Infra\User\Repository\UserRepositoryMemory;
use PHPUnit\Framework\TestCase;

class UserSignInTest extends TestCase
{
    public function testSignInUser(): void
    {
        $publicador = new PublishEvents();
        $publicador->addListener(new LogUserSignIn());

        $adduser = (new UserRepositoryMemory())->adicionar(
            User::userSerialize(
                123,
                '01680562169',
                'ronyanderson',
                'ronyanderson@gmail.com',
                '1234567a',
                null,
                '2020-01-01',
                '',
                0
            )
        );

        $inputBoundary = new InputBoundaryUserSignIn(
            $adduser[0]->email,
            $adduser[0]->senha,
            $adduser[0]->nome,
            $adduser[0]->image,
            0
        );

        $output = (new UserSignIn(
            new UserRepositoryMemory(),
            new TokenUserMemoryRepository(),
            new SignInUserExternal(
                new UserRepositoryMemory(),
                new TokenUserMemoryRepository()
            ),
            new PublishEvents(),
        ))->execute($inputBoundary);

        $result = (new UserPresentationRepository())->outPut($output);
        $publicador->publish(
            new UserSignInEvent(new Email('ronyanderson@gmail.com'))
        );
    }
}
