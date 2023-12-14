<?php

namespace Gso\Ws\Web\Controllers;

use Gso\Ws\Context\User\App\UseCases\User\Register\InputBoundaryUserRegister;
use Gso\Ws\Context\User\App\UseCases\User\Register\UserRegisterCase;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp\InputBoundaryUserAuthSignUp;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp\UserAuthSignUpCase;
use Gso\Ws\Web\Helper\ResponseError;
use Gso\Ws\Web\Presentation\UserPresentationRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserRegisterController
{
    use ResponseError;

    public function __construct(
        private readonly UserRegisterCase $userRegisterCase,
        private readonly UserPresentationRepository $userPresentationRepository
    ) {
    }


    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            if (
                empty($request->getParsedBody()['nome']) ||
                empty($request->getParsedBody()['cpf']) ||
                empty($request->getParsedBody()['endereco']) ||
                empty($request->getParsedBody()['numero']) ||
                empty($request->getParsedBody()['cep']) ||
                empty($request->getParsedBody()['cidade']) ||
                empty($request->getParsedBody()['estado']) ||
                empty($request->getParsedBody()['bairro']) ||
                empty($request->getParsedBody()['telefone']) ||
                empty($request->getParsedBody()['data_nascimento']) ||
                empty($request->getParsedBody()['email']) ||
                empty($request->getParsedBody()['senha'])
            ) {
                throw new \RuntimeException('Parâmetros ausentes');
            }

            // PEGA OS HTTPs
            $nome           = htmlentities($request->getParsedBody()['nome']);
            $cpf            = htmlentities($request->getParsedBody()['cpf']);
            $endereco       = htmlentities($request->getParsedBody()['endereco']);
            $complemento    = htmlentities($request->getParsedBody()['complemento']);
            $sigla          = htmlentities($request->getParsedBody()['sigla']);
            $numero         = htmlentities($request->getParsedBody()['numero']);
            $cep            = htmlentities($request->getParsedBody()['cep']);
            $cidade         = htmlentities($request->getParsedBody()['cidade']);
            $estado         = htmlentities($request->getParsedBody()['estado']);
            $bairro         = htmlentities($request->getParsedBody()['bairro']);
            $telefone       = htmlentities($request->getParsedBody()['telefone']);
            $dataNascimento = htmlentities($request->getParsedBody()['data_nascimento']);
            $email          = htmlentities($request->getParsedBody()['email']);
            $senha          = htmlentities($request->getParsedBody()['senha']);


            $inputBoundary = new InputBoundaryUserRegister(
                $nome,
                $cpf,
                $endereco,
                $complemento,
                $sigla,
                $numero,
                $cep,
                $cidade,
                $estado,
                $bairro,
                $telefone,
                $dataNascimento,
                $email,
                $senha
            );


            $output = $this->userRegisterCase->execute($inputBoundary);

            if (null === $output->id) {
                throw new \RuntimeException('Erro ao cadastrar novo usuário, !', 256 | 64);
            }

            $result = $this->userPresentationRepository->outPut($output);
            $result = json_encode($result, JSON_THROW_ON_ERROR | 64 | 256);
            $response->getBody()->write($result);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\RuntimeException $e) {
            $result = [
                'status'  => 'failure',
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
            $response->getBody()->write(json_encode($result, JSON_THROW_ON_ERROR | 64 | 256));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
    }
}
