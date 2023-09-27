<?php

namespace Gso\Ws\Test\Domain\User;

use Gso\Ws\Domains\User\Events\UserSign;
use Gso\Ws\Domains\ValuesObjects\Email;
use PHPUnit\Framework\TestCase;

class UserSignInTest extends TestCase
{
    public function testEmailNoFormatoInvalidoNaoDevePoderExistir(): void
    {
        $status = new Email('email inválido');
        $this->expectException(\InvalidArgumentException::class);
    }


    public function testEmailDevePoderSerRepresentadoComoString(): void
    {
        $email = new Email('endereco@example.com');
        $this->assertSame('endereco@example.com', (string)$email);
    }

    public function testEventoDominio(): void
    {
        $email       = new Email('endereco@example.com');
        $emailEvento = new UserSign($email);
        $this->assertSame('endereco@example.com', (string)$emailEvento->emailUser());
    }

//    public function testSignInUser(): void
//    {
//        $email = new UserSign('ronyandersonpc@gmail.com','1234567a');
//        $this->assertSame('ronyandersonpc@gmail.com', (string)$email);
//    }


}
