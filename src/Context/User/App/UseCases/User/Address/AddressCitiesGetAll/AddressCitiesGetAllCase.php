<?php

namespace Gso\Ws\Context\User\App\UseCases\User\Address\AddressCitiesGetAll;

use Gso\Ws\Context\User\Domains\User\Interface\UserAddressRepositoryInterface;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

class AddressCitiesGetAllCase
{
    use ResponseError;

    public function __construct(public readonly UserAddressRepositoryInterface $userAddressRepository)
    {
    }

    /*
     * @return OutputBoundaryAddressCitiesGetAll[]
     */
    public function execute(): array
    {
        try {
            $states = $this->userAddressRepository->getAllCities();


            foreach ($states as $state) {
                $statesArray[] = new OutputBoundaryAddressCitiesGetAll(
                    $state->id,
                    $state->cidade,
                    $state->estado,
                );
            }


            return $statesArray;
        } catch (RuntimeException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }
}
