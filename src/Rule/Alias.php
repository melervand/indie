<?
namespace Rule;

class Alias extends \Rule {
    public function test($value)
    {
        return preg_match("#^[a-zA-Z_]+$#", $value);
    }
}