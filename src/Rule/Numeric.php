<?
namespace Rule;

class Numeric extends \Rule {
    public function test($value, $key = null)
    {
        return is_numeric( $value );
    }
}