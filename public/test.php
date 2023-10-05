<?php

require __DIR__ . '/../vendor/autoload.php';

use Gso\Ws\Context\User\Domains\User\Account;
use Gso\Ws\Context\User\Domains\User\User;

$teste = (new User(
    123,
    null,
    null,
    null,
    0
))->signInUserAuth(
    'ronyanderson@gmail.com',
    '1234567a',
    '1234567a',
    '2020-01-01',
    0
)->addProfile('user', '2020-01-01', '2020-01-01', 1234, 0)
  ->addAccount(
      'Rony',
      'ronyanderson@gmail.com',
      '01680562169',
      '63981270951',
      'null',
      'Rua dos bobos',
      '123',
      '77060060',
      'null',
      'null',
      'null',
      'SP',
      0,
  );
var_dump($teste->getAccount());
