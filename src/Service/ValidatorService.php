<?php

namespace App\Service;


use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    private $validator;
    private $object;
    private $stringManipulator;
    private $errors = null;

    public function __construct(ValidatorInterface $validator, StringManipulator $stringManipulator)
    {
        $this->validator = $validator;
        $this->stringManipulator = $stringManipulator;
    }

    public function setObject($object): self
    {
        $this->object = $object;
        return $this;
    }

    public function ValidateObject():bool
    {
        if(count($this->validator->validate($this->object)) > 0)
        {
            $this->errors = $this->validator->validate($this->object);
            return false;
        }
        else
        {
            return true;
        }
    }

    public function GetErrorsAsArray():array
    {
        $errors_return = [];
        $errors = $this->errors;
        foreach ($errors as $error)
        {
            array_push($errors_return, $error->getMessage());
        }
        return $errors_return;
    }

}