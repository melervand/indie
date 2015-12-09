<?
class Value {
    private $value;
    private $errors;

    public function __construct( $value ) {
        $this->value = $value;
    }

    /**
     * @param callable|Rule $rule
     * @param string $message
     * @return $this
     */
    public function with( $rule, $message ) {
        $valid = false;

        if ( $rule instanceof \Rule ) {
            $valid = $rule->setValue( $this->value )->validate();
        } else {
            $valid = $rule( $this->value );
        }

        $valid ?: $this->errors[] = $message;

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid() {
        return count( $this->errors ) ? false : true;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return count($this->errors) ? $this->errors : [];
    }
}