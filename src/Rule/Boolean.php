<?
namespace Rule;

class Boolean extends \Rule {
    public function test( $value ) {
        return $value === true || $value === false;
    }
}