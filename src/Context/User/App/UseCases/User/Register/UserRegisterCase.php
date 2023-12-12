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

final class UserRegisterCase
{
    public function __construct(
        public readonly UserRepositoryInterface $userRepository,
        public readonly UserAuthRepositoryInterface $userAuthRepository,
        public readonly UserAccountRepositoryInterface $userAccountRepository,
        public readonly UserAddressRepositoryInterface $userAddressRepository,
        public readonly UserProfileRepositoryInterface $userProfileRepository,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function execute(InputBoundaryUserRegister $inputBoundaryUserRegister): OutputBoundaryUserRegister|array
    {
        try {
            $userAuth      = UserAuth::userAuthSerialize(
                null,
                $inputBoundaryUserRegister->email,
                $inputBoundaryUserRegister->password,
                0,
                date('Y-m-d H:i:s'),
                0
            );
            $userAuthSaved = $this->userAuthRepository->saveNewUserAuth($userAuth);

            $userAccount      = Account::accountSerialize(
                null,
                $inputBoundaryUserRegister->name,
                $inputBoundaryUserRegister->email,
                $inputBoundaryUserRegister->cpf,
                $inputBoundaryUserRegister->phone,
                null,
                0,
            );
            $userAccountSaved = $this->userAccountRepository->saveNewUserAccount($userAccount);
            $userAddress      = Address::addressSerialize(
                null,
                $inputBoundaryUserRegister->address,
                $inputBoundaryUserRegister->number,
                $inputBoundaryUserRegister->zipCode,
                $inputBoundaryUserRegister->complement,
                $inputBoundaryUserRegister->district,
                $inputBoundaryUserRegister->city,
                $inputBoundaryUserRegister->state,
                $inputBoundaryUserRegister->shortNameState,
                0,
            );
            $userAddressSaved = $this->userAddressRepository->saveNewAddressUser($userAddress);

            $userProfile = Profile::profileSerialize(
                null,
                'user',
                date('Y-m-d H:i:s'),
                (new DateTime('+1 year'))->format('Y-m-d H:i:s'),
                0,
                0,
            );

            $userProfileSaved = $this->userProfileRepository->saveNewUserProfile($userProfile);

            $user = (new User())->addUserAuth(
                $userAuthSaved->id,
                (string)$userAuthSaved->email,
                (string)$userAuthSaved->password,
                $userAuthSaved->isUserExternal,
                (string)$userAuthSaved->dateCriation,
                $userAuthSaved->excluded
            )->addAccount(
                $userAccountSaved->id,
                $userAccountSaved->name,
                (string)$userAccountSaved->email,
                (string)$userAccountSaved->cpf,
                (string)$userAccountSaved->phone,
                $userAccountSaved->image,
                $userAccountSaved->excluded,
            )->addAddress(
                $userAddressSaved->id,
                $userAddressSaved->address,
                $userAddressSaved->number,
                (string)$userAddressSaved->zipCode,
                $userAddressSaved->complement,
                $userAddressSaved->district,
                $userAddressSaved->city,
                $userAddressSaved->state,
                $userAddressSaved->shortName,
                0
            )->addProfile(
                $userProfileSaved->id,
                mb_strtolower($userProfileSaved->role),
                (string)$userProfileSaved->dateGranted,
                (string)$userProfileSaved->dateExpires,
                $userProfileSaved->grantedByIdUser,
                $userProfileSaved->excluded,
            );

            var_dump($user);
            exit();
//            $user = $this->userRepository->saveNewUser(
//                $input->name,
//                $input->cpf,
//                $input->address,
//                $input->number,
//                $input->zipCode,
//                $input->city,
//                $input->state,
//                $input->district,
//                $input->phone,
//                $input->birthsDay,
//                $input->email,
//                $input->password
//            );
        } catch (\RuntimeException $e) {
            throw new \RuntimeException($e->getMessage());
        } catch (\JsonException $e) {
            throw new \JsonException($e->getMessage());
        }
    }
}
