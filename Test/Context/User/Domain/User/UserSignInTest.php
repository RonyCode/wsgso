<?php

namespace Context\User\Domain\User;

require __DIR__ . '/../../../../../vendor/autoload.php';
include __DIR__ . '/../../../../../config/config.php';

use Dotenv\Dotenv;
use Gso\Ws\Context\User\App\UseCases\User\SignInUser\InputBoundaryUserSignIn;
use Gso\Ws\Context\User\App\UseCases\User\SignInUser\UserSignIn;
use Gso\Ws\Context\User\App\UseCases\UserExternal\SignInUserExternal\SignInUserExternal;
use Gso\Ws\Context\User\Domains\User\User;
use Gso\Ws\Context\User\Infra\Repositories\RepositoriesModel\TokenUserMemoryRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserPresentationRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserRepositoryMemory;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Shared\ValuesObjects\Cep;
use Gso\Ws\Shared\ValuesObjects\Phone;
use PHPUnit\Framework\TestCase;


class UserSignInTest extends TestCase
{
    public function testSignInUser(): void
    {
        $dotenv = Dotenv::createUnsafeImmutable('../../../../../');
        $dotenv->load();

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
        $this->assertSame('ronyanderson@gmail.com', (string) $adduser[0]->email);

        $result = (new UserPresentationRepository())->outPut($output);
        $this->assertEquals(202, $result['code']);
    }
}
