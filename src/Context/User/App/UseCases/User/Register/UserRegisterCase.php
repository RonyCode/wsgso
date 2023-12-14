<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\App\UseCases\User\Register;

use DateTime;
use Gso\Ws\Context\User\Domains\User\Account;
use Gso\Ws\Context\User\Domains\User\Address;
use Gso\Ws\Context\User\Domains\User\Interface\UserAccountRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserAddressRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserProfileRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Interface\UserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Profile;
use Gso\Ws\Context\User\Domains\User\User;
use Gso\Ws\Context\User\Domains\User\UserAuth;
use Gso\Ws\Context\User\Infra\Connection\GlobalConnection;

final readonly class UserRegisterCase
{
    public function __construct(
        public UserRepositoryInterface $userRepository,
        public UserAuthRepositoryInterface $userAuthRepository,
        public UserAccountRepositoryInterface $userAccountRepository,
        public UserAddressRepositoryInterface $userAddressRepository,
        public UserProfileRepositoryInterface $userProfileRepository,
    ) {
    }

    /**
     * @param InputBoundaryUserRegister $inputBoundaryUserRegister
     *
     * @return OutputBoundaryUserRegister
     * @throws \JsonException
     */
    public function execute(InputBoundaryUserRegister $inputBoundaryUserRegister): OutputBoundaryUserRegister
    {
        try {
            $conn = GlobalConnection::conn();
            $conn->beginTransaction();

            $userToSave = (new User())->saveNewUser(
                (new UserAuth(
                    null,
                    $inputBoundaryUserRegister->email,
                    $inputBoundaryUserRegister->password,
                    0,
                    date('Y-m-d H:i:s'),
                    0
                ))->sanitize(),
                (new Account(
                    null,
                    $inputBoundaryUserRegister->name,
                    $inputBoundaryUserRegister->email,
                    $inputBoundaryUserRegister->cpf,
                    $inputBoundaryUserRegister->phone,
                    null,
                    0
                ))->sanitize(),
                (new Address(
                    null,
                    $inputBoundaryUserRegister->address,
                    $inputBoundaryUserRegister->number,
                    $inputBoundaryUserRegister->zipCode,
                    $inputBoundaryUserRegister->complement,
                    $inputBoundaryUserRegister->district,
                    $inputBoundaryUserRegister->city,
                    $inputBoundaryUserRegister->state,
                    $inputBoundaryUserRegister->shortNameState,
                    0
                ))->sanitize(),
                (new Profile(
                    null,
                    'user',
                    date('Y-m-d H:i:s'),
                    (new DateTime('+1 year'))->format('Y-m-d H:i:s'),
                    0,
                    0,
                ))->sanitize()
            );

            $userAuthSaved    = $this->userAuthRepository->saveNewUserAuth($userToSave['user_auth']);
            $userAccountSaved = $this->userAccountRepository->saveNewUserAccount($userToSave['account']);
            $userAddressSaved = $this->userAddressRepository->saveNewAddressUser($userToSave['address']);
            $userProfileSaved = $this->userProfileRepository->saveNewUserProfile($userToSave['profile']);

            $user = new User(
                null,
                $userAuthSaved->id,
                $userAccountSaved->id,
                $userAddressSaved->id,
                $userProfileSaved->id,
                0,
            );

            $result = $this->userRepository->saveNewUser($user);
            if (empty($result)) {
                throw new \RuntimeException('Erro ao cadastrar usuario');
            }

            $conn->commit();

            return new OutputBoundaryUserRegister(
                $result->id,
                $result->getUserAuthId(),
                $result->getAccountId(),
                $result->getAddressId(),
                $result->getProfileId(),
                $result->excluded,
            );
        } catch (\RuntimeException $e) {
            $conn->rollBack();
            throw new \RuntimeException($e->getMessage());
        } catch (\JsonException $e) {
            throw new \JsonException($e->getMessage());
        }
    }
}
