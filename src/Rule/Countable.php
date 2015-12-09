<?
namespace Rule;

class Countable extends \Rule {
    public function validate() {
        return is_array( $this->value );
    }
}