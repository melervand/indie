<?
namespace Rule;

class Alpha extends \Rule {
    public function test($value)
    {
        return ctype_alpha( $value );
    }
}