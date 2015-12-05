<?
class IndieValue {
    private $value;
    private $errors;

    public function __construct( $value ) {
        $this->value = $value;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function required( $message ) {
        return $this->pass( function($value) {
            return $value==''?false:true;
        }, $message);
    }


    /**
     * @param callable $function
     * @param string $message
     * @return IndieValue
     */
    public function custom( $function, $message ) {
        return $this->pass( function($value) use ($function) {
            return $function($value);
        }, $message);
    }

    /**
     * @param mixed $to
     * @param string $message
     * @return IndieValue
     */
    public function equals( $to, $message ) {
        return $this->pass( function($value) use ($to) {
             return trim($value) == $to;
        }, $message);
    }

    /**
     * @param integer $min
     * @param string $message
     * @return IndieValue
     */
    public function min( $min, $message ) {
        return $this->pass(function($value) use ($min) {
            return $value >= $min;
        }, $message);
    }

    /**
     * @param integer $max
     * @param string $message
     * @return IndieValue
     */
    public function max( $max, $message ) {
        return $this->pass(function($value) use ($max) {
            return $value <= $max;
        }, $message);
    }

    /**
     * @param $message
     * @return IndieValue
     */
    public function countable( $message ) {
        return $this->pass( function($value) {
            return is_array( $value );
        }, $message );
    }

    public function numeric( $message ) {
        return $this->pass( function($value) {
            return is_numeric( $value );
        }, $message );
    }

    public function url( $message ) {
        return $this->pass( function($value) {
            return filter_var( $value, FILTER_VALIDATE_URL );
        }, $message );
    }

    /**
     * @param string $message
     * @return IndieValue
     */
    public function boolean( $message ) {
        return $this->pass( function( $value ) {
            return $value === true || $value === false;
        }, $message );
    }

    public function isValid() {
        return count( $this->errors ) ? false : true;
    }

    public function getValue() {
        return $this->value;
    }

    public function getErrors() {
        return count($this->errors) ? $this->errors : [];
    }

    protected function pass( $function, $message ) {
        $valid = $function( $this->value );

        if (!$valid) {
            $this->errors[] = $message;
        }

        return $this;
    }
}