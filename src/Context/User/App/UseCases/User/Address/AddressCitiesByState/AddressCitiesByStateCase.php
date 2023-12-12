<?php

namespace Gso\Ws\Context\User\App\UseCases\User\Address\AddressCitiesByState;

use Gso\Ws\Context\User\Domains\User\Interface\UserAddressRepositoryInterface;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

class AddressCitiesByStateCase
{
    use ResponseError;

    public function __construct(public readonly UserAddressRepositoryInterface $userAddressRepository)
    {
    }

    /**
     * @return OutPutBoundaryAddressCitiesByState[]
     * @throws \JsonException
     */
    public function execute(InputBoundaryAddressCitiesByState $input): array
    {
        try {
            $cities = $this->userAddressRepository->getAllCitiesByState($input->state);
            foreach ($cities as $city) {
                $citiesArray[] = new OutPutBoundaryAddressCitiesByState(
                    $city->id,
                    $city->city,
                    $city->state,
                );
            }


            return $citiesArray;
        } catch (RuntimeException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }
}
