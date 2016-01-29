<?
class Value {
    private $explicit;
    private $value;
    private $errors;

    public function __construct( $value, $explicit = true ) {
        $this->explicit = $explicit;
        $this->value = $value;
    }

    /**
     * @param callable|Rule $rule
     * @param string $message
     * @return $this
     */
    public function with( $rule, $message ) {
        if ( $rule instanceof \Rule ) {
            /** @var Rule $rule */
            $valid = $rule->setValue( $this->value )->validate( $this->explicit );
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