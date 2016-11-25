<?
abstract class Rule {
    protected $value;

    public function setValue( $value ) {
        $this->value = $value;

        return $this;
    }

    /**
     * @param bool $explicit
     * @return bool
     */
    public function validate( $key = null ) {
        $valid = $this->test( $this->value, $key );
        return $valid;
    }

    /**
     * Implement validation rule
     * @param array|string $value
     * @param integer $key
     * @return bool
     */
    abstract public function test( $value, $key = null );

    public function message( $format ) {
        $message = $format;
        preg_match_all( '/\:([a-zA-Z0-9]+)/i', $format, $matches );

        foreach( $matches[1] as $match ) {
            $message = str_replace( ':'.$match, $this->$match, $message );
        }

        return $message;
    }

    public function __get($name) {
        return $this->$name;
    }
}