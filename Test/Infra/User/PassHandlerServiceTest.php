<?php

namespace Gso\Ws\Test\Infra\User;


use Gso\Ws\Domains\ValuesObjects\Senha;
use Gso\Ws\Infra\User\services\PassHandleUserService;
use PHPUnit\Framework\TestCase;

use function DI\string;

class PassHandlerServiceTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function testPassHandlerEncode(): void
    {
        $senha  = new Senha('1234567a');
        $status = (new PassHandleUserService())->encodePassUser($senha);
        $this->assertSame('1234567a', (string)$senha);
    }

    /**
     * @throws \JsonException
     */
    public function testPassHandlerVerify(): void
    {
        $senha  = new Senha('1234567a');
        $status = (new PassHandleUserService())->encodePassUser($senha);
        $status = (new PassHandleUserService())->verifyPassUser($senha, $status);
        if ($status) {
            $this->assertTrue(true);
        }
        $this->assertSame('1234567a', (string)$senha);
    }


}
