<?php

namespace Gso\Ws\Web\Helper;

use DomainException;

class ValidateParams
{
    use ResponseError;

    private string $date;

    public function __construct(
        private ?string $zipCode = null
    ) {
        try {
            if (($this->zipCode !== null && $this->zipCode !== '') && ! $this->validateZipCode()) {
                throw new \DomainException();
            }
        } catch (DomainException) {
            echo json_encode([
                "code"    => 404,
                'status'  => 'ERROR',
                'message' => 'Cep inválido',
            ], JSON_THROW_ON_ERROR | 64 | 256);
            exit();
        }
    }

    public function validateZipCode(): string
    {
        if (preg_match('/^[0-9]{5}\-?[0-9]{3}$/', $this->zipCode) === 0) {
            throw new \DomainException('Cep inválido');
        }
        $this->zipCode       = preg_replace('/\D{0,9}/', '', $this->zipCode);

        return $this->zipCode;
    }


    public function dateFormatDbToBr($objDate): string
    {
        try {
            \DateTimeImmutable::createFromFormat('Y-m-d', $objDate) ?
                $date = \DateTimeImmutable::createFromFormat('Y-m-d', $objDate) :
                $date = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $objDate);
            if (! $date) {
                throw new \RuntimeException();
            }

            return $date->format('d/m/Y');
        } catch (\Exception) {
            $this->responseCatchError(
                'Os dados referente a data dever ser exatamente assim XXXX-XX-XX vindo do banco de dados.'
            );

            return '';
        }
    }

    public function validateEmail(null|string $email): ?string
    {
        try {
            $valitedEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
            false === $valitedEmail ? throw new \Exception() : '';

            return $valitedEmail;
        } catch (\Exception) {
            $this->responseCatchError('Email inválido, favor digitar um email válido');

            return '';
        }
    }

    public function validatePass(string $pass): string
    {
        try {
            $regex = '/^\\S*(?=\\S{8,})(?=\\S*[a-zA-Z])(?=\\S*[\\d])\\S*$/';
            if (! preg_match($regex, $pass, $match)) {
                throw new \RuntimeException();
            }

            return $pass;
        } catch (\Exception) {
            $this->responseCatchError('Erro, senha dever ter pelo menos 1 letra, 1 numero e no mínimo 8 caracteres.');

            return '';
        }
    }

    public function validateName(string $name): string
    {
        try {
            $regex = '/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]+$/';
            if (! preg_match($regex, $name, $match)) {
                throw new \Exception();
            }

            $name       = explode(' ', $name);
            $nametrated = [];
            foreach ($name as $nameSepared) {
                $nametrated[] = ucfirst(mb_strtolower($nameSepared));
            }

            return implode(' ', $nametrated);
        } catch (\Exception) {
            $this->responseCatchError(
                'Digite apenas letras no campo nome, números ou caracteres especiais não serão aceitos.'
            );

            return '';
        }
    }

    public function validateAddress(string $address): string
    {
        try {
            $regex = '/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ 0-9]+$/';
            if (! preg_match($regex, $address, $match)) {
                throw new \RuntimeException();
            }

            return mb_strtoupper($address);
        } catch (\Exception) {
            $this->responseCatchError(
                'Digite apenas letras ou numeros no campo endereço, caracteres especiais não serão aceitos'
            );

            return '';
        }
    }

    public function validatePhone(string $phone): string
    {
        try {
            $regex = '/^\\(?[1-9]{2}\\)? ?(?:[2-8]|9[1-9])[0-9]{3}\\-?[0-9]{4}$/';
            if (! preg_match($regex, $phone, $match)) {
                throw new \RuntimeException();
            }

            return $phone;
        } catch (\Exception) {
            $this->responseCatchError(
                'Error: numero de Telefone inválido, use exatamente esse formato (99) 99999-9999.'
            );

            return '';
        }
    }

    public function validateAge(string $birthday): string
    {
        try {
            $this->date = $birthday;
            $date       = $this->dateFormatBrToDb($birthday);

            $dateFormated = new \DateTime($date);

            return $dateFormated->diff(new \DateTime(date('Y/m/d')))->format('%Y');
        } catch (\Exception) {
            $this->responseCatchError('"Os dados referente a data dever ser exatamente neste formato XX/XX/XXXX.');

            return '';
        }
    }

    public function dateFormatBrToDb($objDate): string
    {
        try {
            $date = \DateTimeImmutable::createFromFormat('d/m/Y', $objDate);
            if (! $date) {
                throw new \RuntimeException();
            }

            return $date->format('Y-m-d');
        } catch (\Exception) {
            $this->responseCatchError('"Os dados referente a data dever ser exatamente neste formato XX/XX/XXXX.');

            return '';
        }
    }

    public function validateBirthday(string $birthday): string
    {
        try {
            return $this->dateFormatBrToDb($birthday);
        } catch (\Exception) {
            $this->responseCatchError('"Os dados referente a data dever ser exatamente neste formato XX/XX/XXXX.');

            return '';
        }
    }

    public function validateInteger(int $numeral): int
    {
        try {
            $regex = '/^[1-9]\\d*$/';
            if (! preg_match($regex, $numeral, $match)) {
                throw new \RuntimeException();
            }

            return $numeral;
        } catch (\Exception) {
            $this->responseCatchError(
                'Error: Somente números serão aceito para esse formulário.'
            );

            return 0;
        }
    }
}
