<?php

namespace Gso\Ws\Context\User\App\UseCases\User\Address\AddressStatesGetAll;

use Gso\Ws\Context\User\Domains\User\Interface\UserAddressRepositoryInterface;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

class AddressStatesGetAllCase
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
            $states = $this->userAddressRepository->getAllStates();


            foreach ($states as $state) {
                $statesArray[] = new OutputBoundaryAddressStatesGetAll(
                    $state->id,
                    $state->state,
                    $state->shortName,
                );
            }


            return $statesArray;
        } catch (RuntimeException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }
}
