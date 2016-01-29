<?
namespace Rule;

class Countable extends \Rule {
    public function test( $value ) {
        return is_array( $value );
    }
}