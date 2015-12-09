<?
namespace Rule;

class Numeric extends \Rule {
    public function validate() {
        return is_numeric( $this->value );
    }
}