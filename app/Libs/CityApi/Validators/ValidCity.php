<?php

namespace App\Libs\CityApi\Validators;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as Validation;
use App\Libs\CityApi\Contracts\ValidateCityContract;
use Exception;
use Str;

class ValidCity implements ValidateCityContract
{
    private bool $dataIsValid = false;
    private Validation $validator;

    private array $validatedData = [];

    private static array $validations = [
        'name' => 'required|string|min:2',
        'ibge_id' => 'required|numeric|min:2',
    ];

    public function __construct(array $cityInfo)
    {
        $this->validation($cityInfo);
    }

    public static function make(array $cityInfo): null|ValidateCityContract
    {
        return new static($cityInfo);
    }

    public function city(bool $toArray = false): null|array|Collection
    {
        return $this->data($toArray);
    }

    public function data(bool $toArray = false): null|array|Collection
    {
        if (!$this->dataIsValid) {
            return null;
        }

        return $toArray ? $this->validatedData : collect($this->validatedData);
    }

    protected function validation(array $cityInfo): void
    {
        $this->validator =  Validator::make($cityInfo, static::$validations);

        if ($this->validator->errors()->count()) {
            $this->dataIsValid = \false;
            throw new Exception($this->validator->errors()->first(), 5080);
        }

        $this->validatedData = $this->validator->validated();
        $this->validatedData['provider'] = config('cities-api.provider');
        $this->dataIsValid = \true;
    }

    public function __get(string $atribute): mixed
    {
        if (!$this->dataIsValid || !trim($atribute)) {
            return null;
        }

        $atributeSnake = Str::snake($atribute);
        $atributeCamel = Str::camel($atribute);

        $data = $this->data(true);

        return $data[$atribute] ?? $data[$atributeSnake] ?? $data[$atributeCamel] ?? \null;
    }
}
