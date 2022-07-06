<?php
namespace BookingSystem\Util;

use BookingSystem\Util\Util;


/******************************
 * Validates a passed input against all called validation checks.
 * Validation should generally be called in the following order
 * inputName(optional)->value()->any()
 * but anything after inputName|value can be set however
 * 
 * sanitize() should always be called to strip tags from an input
 * 
 * When the input is invalid only the first error checked will be returned
 * 
 * When using this class to write feedback on forms the $name variable should be set
 * to the case sensitive [name] attribute of the form input being checked
 *********************************/
class InputValidator
{
    
    private $name = "Value";
    private $value;
    private $required = false; 
    private $invalid = false;

    private $errors = array();

    private $patterns = array(
        'postcode' => "/^[A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}$/",
        'mobile' => "/((\+44(\s\(0\)\s|\s0\s|\s)?)|0)7\d{3}(\s)?\d{6}/",
        'date' => "/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/"
    );
    
    /********
     * @param string $name - name of the form input being validated
     */
    public function inputName($name)
    {
        // Reset variables for new input
        unset($this->name);
        unset($this->value);
        $this->required = false;
        $this->invalid = false;

        $this->name = $name;
        return $this;
    }

    /*************
     * @param mixed $value - sets the validation checker value
     *****/
    public function value($value) 
    {
        $this->value = $value;
        return $this;
    }


    /*************
     * Sanitizes the value using the Util class
     * @param string $type - type of sanitization to be used string|integer|float|email
     */
    public function sanitize($type)
    {
        // only sanitize if has value
        if ($this->value == null && $this->required == false) return $this;

        if ($type == "string") {
            if (Util::valStr($this->value)) {
                $this->value = Util::sanStr($this->value);
            }
            else {
                if (!$this->invalid) {
                    array_push($this->errors, ['input' => $this->name, 'message' => ucwords($this->name). " must be text"]);
                    $this->invalid = true;
                }
            }
        }

        if ($type == "integer") {
            if (Util::valInt($this->value)) {
                $this->value = Util::sanInt($this->value);
            }
            else {
                if (!$this->invalid) {
                    array_push($this->errors, ['input' => $this->name, 'message' => ucwords($this->name). " must be a whole number"]);
                    $this->invalid = true;
                }
            }
        }

        if ($type == "float") {
            if (Util::valFloat($this->value)) {
                $this->value = Util::sanFloat($this->value);
            }
            else {
                if (!$this->invalid) {
                    array_push($this->errors, ['input' => $this->name, 'message' => ucwords($this->name). " must be less a floating point number "]);
                    $this->invalid = true;
                }
            }
        }

        if ($type == "email") {
            if (Util::valEmail($this->value)) {
                $this->value = Util::sanEmail($this->value);
            }
            else {
                if (!$this->invalid) {
                    array_push($this->errors, ['input' => $this->name, 'message' => ucwords($this->name). " must be a valid email format"]);
                    $this->invalid = true;
                }
            }
        }

        return $this;
    }



    /**********
     * Ensures the value matches the specified pattern's regex
     * @param string $pattern - name of the regex for the value to be checked with
     ***************/
    public function match($pattern)
    {
        // return if string empty and is not required
        if ( ($this->value == null || $this->value == "") && $this->required == false) return $this;

        if (!preg_match($this->patterns[$pattern], $this->value)) {
            if (!$this->invalid) {
                array_push($this->errors, ['input' => $this->name, 'message' => ucwords($this->name). " must be a valid $pattern"]);
                $this->invalid = true;
            }
        }
        return $this;
    }


    /************
     * Specifies that the value is required
     * prevents validity when the value is empty
     ******************/
    public function required()
    {
        $this->required = true;

        if ($this->value == "" || $this->value == null) {
            if (!$this->invalid) {
                array_push($this->errors, ['input' => $this->name, 'message' => ucwords($this->name). " is required"]);
                $this->invalid = true;
            }
        }
        return $this;
    }


    /*************
     * Ensures the value is less than the maxLen
     * Prevents validity when value longer than maxLen
     ***/
    public function maxLen($maxLen)
    {
        if (strlen($this->value) > $maxLen) {
            if (!$this->invalid) {
                array_push($this->errors, ['input' => $this->name, 'message' => ucwords($this->name). " must be less than $maxLen characters"]);
                $this->invalid = true;
            }
        }
        return $this;
    }


   
    public function getResult()
    {     
        return $this->value;   
    }

    public function getErrors()
    {
        return $this->errors;
    }

}
?>