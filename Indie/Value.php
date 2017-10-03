<?

namespace Indie;

use Indie\Exceptions\RuleIsNotInvokableException;

class Value
{
    private $value;
    private $asArray;
    private $errors;

    /**
     * Value constructor.
     *
     * @param mixed   $value
     * @param boolean $asArray
     */
    public function __construct($value, $asArray)
    {
        $this->value = $value;
        $this->asArray = $asArray;
    }

    /**
     * @param callable|\Indie\Interfaces\RuleInterface $callable
     * @param string|null                              $message Localized error message
     * @param boolean                                  $strict  Validate anyway
     *
     * @return \Indie\Value
     */
    public function with($callable, $message = null, $strict = false)
    {
        if ( $this->value !== "" || $strict ) {
            if ( $this->asArray && (is_array($this->value) || $this->value instanceof \Countable) ) {
                foreach ( $this->value as $key => $value ) {
                    if ( !$isValid = $this->testWith($callable, $value, $key) ) {
                        $this->errors[ $key ][] = $message;
                    }
                }
            } else {
                if ( !$isValid = $this->testWith($callable, $this->value) ) {
                    $this->errors[] = $message;
                }
            }
        }

        return $this;
    }

    /**
     * Return validation result
     * @return bool
     */
    public function isValid()
    {
        return count($this->errors) ? false : true;
    }

    /**
     * Return errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Return validation result of test function
     *
     * @param callable|\Indie\Interfaces\RuleInterface $callable
     * @param string                                   $value
     * @param string|null                              $key
     *
     * @return boolean
     */
    private function testWith($callable, $value, $key = null)
    {
        if ( $callable instanceof Rule ) {
            return $callable->test($value, $key);
        } else if ( is_callable($callable) ) {
            return $callable($value, $key);
        } else {
            throw new RuleIsNotInvokableException();
        }
    }
}