<?php
namespace Dots\Validator;
use Zend\Validator\AbstractValidator;

class Url extends AbstractValidator
{
    const INVALID_URL = 'invalidUrl';

    protected $_messageTemplates = array(
        self::INVALID_URL => "'%value%' is not a valid URL.",
    );

    public function isValid($value)
    {
        $valueString = (string)$value;
        $this->setValue($valueString);

        if (!preg_match('#https?://([-\w\.])+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?#', $valueString)) {
            $this->error(self::INVALID_URL);
            return false;
        }
        return true;
    }

}