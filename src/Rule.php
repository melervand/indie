<?
abstract class Rule {
    protected $value;

    public function setValue( $value ) {
        $this->value = $value;

        return $this;
    }

    abstract public function validate();
}