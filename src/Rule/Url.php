<?
namespace Rule;

class Url extends \Rule {
    public function test($value, $key = null)
    {
        return filter_var( $value, FILTER_VALIDATE_URL );
    }
}