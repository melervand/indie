<?
namespace Rule;

class Numeric extends \Rule {
    public function test( $value ) {
        return is_numeric( $value );
    }
}