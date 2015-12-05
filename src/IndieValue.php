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

    public function custom( $function, $message ) {
        //TODO: custom validator
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

        if ($valid !== true) {
            $this->errors[ $this->indexpath ] = $message;
        }

        return $this;
    }
}