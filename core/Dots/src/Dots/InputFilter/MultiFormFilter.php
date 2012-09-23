<?php
/**
 * This file is part of Dots
 *
 * (c) 2012 ZendExperts <team@zendexperts.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Dots\InputFilter;

use Zend\InputFilter\InputFilterInterface;

class MultiFormFilter implements InputFilterInterface
{
    protected $forms = array();
    protected $data;
    protected $invalidForms;
    protected $validationGroup;
    protected $validForms;

    public function __construct($forms)
    {
        foreach($forms as $form){
            $this->forms[$form->getName()] = $form;
        }
    }

    /**
     * Add an input to the input filter
     *
     * @param  InputInterface|InputFilterInterface $input
     * @param  null|string $name Name used to retrieve this input
     * @return InputFilterInterface
     */
    public function add($input, $name = null)
    {
        if (!$input instanceof \Zend\Form\Form){
            throw new \Exception('Invalid input type');
        }
        $this->forms[$name] = $input;
    }

    /**
     * Retrieve a named input
     *
     * @param  string $name
     * @return InputInterface|InputFilterInterface
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->forms)) {
            throw new \Exception(sprintf(
                '%s: no input found matching "%s"', __METHOD__, $name
            ));
        }
        return $this->forms[$name];
    }

    /**
     * Test if an input or input filter by the given name is attached
     *
     * @param  string $name
     * @return bool
     */
    public function has($name)
    {
        return (array_key_exists($name, $this->forms));
    }

    /**
     * Remove a named input
     *
     * @param  string $name
     * @return InputFilterInterface
     */
    public function remove($name)
    {
        if (array_key_exists($name, $this->forms)){
            unset($this->forms[$name]);
        }
        return $this;
    }

    /**
     * Set data to use when validating and filtering
     *
     * @param  array|Traversable $data
     * @return InputFilterInterface
     */
    public function setData($data)
    {
        $this->data = $data;
        $this->populate();
        return $this;
    }

    protected function populate()
    {
        foreach (array_keys($this->forms) as $name) {
            $form = $this->forms[$name];

            if (!isset($this->data[$name])) {
                // No value; clear value in this input
                $form->setData(array());
                continue;
            }

            $value = $this->data[$name];
            $form->setData($value);
        }
    }

    /**
     * Is the data set valid?
     *
     * @return bool
     */
    public function isValid()
    {
        if (null === $this->data) {
            throw new \Exception(sprintf(
                '%s: no data present to validate!',
                __METHOD__
            ));
        }

        $this->validForms = array();
        $this->invalidForms = array();
        $valid = true;

        $inputs = $this->validationGroup ? : array_keys($this->forms);
        foreach ($inputs as $name) {
            $form = $this->forms[$name];

            if (!$form->isValid()) {
                $this->invalidInputs[$name] = $form;
                $valid = false;
                continue;
            }

            $this->validInputs[$name] = $form;
        }

        return $valid;
    }

    /**
     * Provide a list of one or more elements indicating the complete set to validate
     *
     * When provided, calls to {@link isValid()} will only validate the provided set.
     *
     * If the initial value is {@link VALIDATE_ALL}, the current validation group, if
     * any, should be cleared.
     *
     * Implementations should allow passing a single array value, or multiple arguments,
     * each specifying a single input.
     *
     * @param  mixed $name
     * @return InputFilterInterface
     */
    public function setValidationGroup($name)
    {
        if ($name === self::VALIDATE_ALL) {
            $this->validationGroup = null;
            return $this;
        }

        $this->validationGroup = $name;
    }

    /**
     * Return a list of inputs that were invalid.
     *
     * Implementations should return an associative array of name/input pairs
     * that failed validation.
     *
     * @return InputInterface[]
     */
    public function getInvalidInput()
    {
        return (is_array($this->invalidForms) ? $this->invalidForms : array());
    }

    /**
     * Return a list of inputs that were valid.
     *
     * Implementations should return an associative array of name/input pairs
     * that passed validation.
     *
     * @return InputInterface[]
     */
    public function getValidInput()
    {
        return (is_array($this->validForms) ? $this->validForms : array());
    }

    /**
     * Retrieve a value from a named input
     *
     * @param  string $name
     * @return mixed
     */
    public function getValue($name)
    {
        if (!array_key_exists($name, $this->forms)) {
            throw new \Exception(sprintf(
                '%s expects a valid input name; "%s" was not found in the filter',
                __METHOD__,
                $name
            ));
        }
        $form = $this->forms[$name];
        $inputFilter = $form->getInputFilter();
        return $inputFilter->getValues();
    }

    /**
     * Return a list of filtered values
     *
     * List should be an associative array, with the values filtered. If
     * validation failed, this should raise an exception.
     *
     * @return array
     */
    public function getValues()
    {
        $values = array();
        foreach($this->forms as $key => $form){
            $inputFilter = $form->getInputFilter();
            $values[$key] = $inputFilter->getValues();
        }
        return $values;
    }

    /**
     * Retrieve a raw (unfiltered) value from a named input
     *
     * @param  string $name
     * @return mixed
     */
    public function getRawValue($name)
    {
        if (!array_key_exists($name, $this->forms)) {
            throw new \Exception(sprintf(
                '%s expects a valid input name; "%s" was not found in the filter',
                __METHOD__,
                $name
            ));
        }
        $form = $this->forms[$name];
        $inputFilter = $form->getInputFilter();
        return $inputFilter->getRawValues();
    }

    /**
     * Return a list of unfiltered values
     *
     * List should be an associative array of named input/value pairs,
     * with the values unfiltered.
     *
     * @return array
     */
    public function getRawValues()
    {
        $values = array();
        foreach ($this->forms as $key => $form) {
            $inputFilter = $form->getInputFilter();
            $values[$key] = $inputFilter->getRawValues();
        }
        return $values;
    }

    /**
     * Return a list of validation failure messages
     *
     * Should return an associative array of named input/message list pairs.
     * Pairs should only be returned for inputs that failed validation.
     *
     * @return array
     */
    public function getMessages()
    {
        $messages = array();
        foreach ($this->getInvalidInput() as $name => $form) {
            $inputFilter = $form->getInputFilter();
            $messages[$name] = $inputFilter->getMessages();
        }
        return $messages;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->inputs);
    }

}
