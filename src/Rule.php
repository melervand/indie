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
    public function validate( $explicit = true ) {
        if ( $explicit && is_array( $this->value ) ) {
            $checks = [];
            foreach ($this->value as $value) {
                $checks[] = $this->test( $value );
            }

            $valid = !in_array( false, $checks );
        } else {
            $valid = $this->test( $this->value );
        }

        return $valid;
    }

    /**
     * Implement validation rule
     * @param array|string $value
     * @return bool
     */
    abstract public function test( $value );

    public function __get($name) {
        return $this->$name;
    }
}