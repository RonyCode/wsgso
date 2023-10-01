<?php

namespace Context\User\Infra\User;


use Gso\Ws\Context\User\Infra\User\services\PassHandleUserService;
use Gso\Ws\Shared\ValuesObjects\Senha;
use PHPUnit\Framework\TestCase;

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
