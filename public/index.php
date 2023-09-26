<?php

(require __DIR__ . '/../config/frontManager.php')->run();
$t    = "test nao validou";



$testeSenha = password_hash('1234567a',PASSWORD_ARGON2I);
$testeSenhaDecript = password_verify('1234567a', $testeSenha);
$test = 3;
