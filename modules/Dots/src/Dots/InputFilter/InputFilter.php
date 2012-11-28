<?php
/**
 * This file is part of DotsCMS
 *
 * (c) 2012 DotsCMS <team@dotscms.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots\InputFilter;

use Zend\InputFilter\InputFilter as BaseInputFilter;
use Zend\InputFilter\Exception;
use Zend\InputFilter\InputInterface;
use Zend\InputFilter\InputFilterInterface;

class InputFilter extends BaseInputFilter
{
    /**
     * Is the data set valid?
     *
     * @throws Exception\RuntimeException
     * @return bool
     */
    public function isValid()
    {
        if (null === $this->data) {
            throw new Exception\RuntimeException(sprintf(
                '%s: no data present to validate!',
                __METHOD__
            ));
        }

        $this->validInputs = array();
        $this->invalidInputs = array();
        $valid = true;

        $inputs = $this->validationGroup ? : array_keys($this->inputs);
        foreach ($inputs as $name) {
            $input = $this->inputs[$name];
            if (!array_key_exists($name, $this->data)
                || (null === $this->data[$name])
                || (is_string($this->data[$name]) && strlen($this->data[$name]) === 0)
            ) {
                //@done: removed automatic validation of inputs based on empty value
                // make sure we have a value (empty) for validation
                $this->data[$name] = '';
            }

            if ($input instanceof InputFilterInterface) {
                if (!$input->isValid()) {
                    $this->invalidInputs[$name] = $input;
                    $valid = false;
                    continue;
                }
                $this->validInputs[$name] = $input;
                continue;
            }
            if ($input instanceof InputInterface) {
                if (!$input->isValid($this->data)) {
                    // Validation failure
                    $this->invalidInputs[$name] = $input;
                    $valid = false;

                    if ($input->breakOnFailure()) {
                        return false;
                    }
                    continue;
                }
                $this->validInputs[$name] = $input;
                continue;
            }
        }

        return $valid;
    }
}
