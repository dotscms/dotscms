<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
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