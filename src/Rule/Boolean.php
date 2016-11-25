<?
namespace Rule;

class Boolean extends \Rule {
    public function test($value, $key = null)
    {
        return $value === true || $value === false;
    }
}