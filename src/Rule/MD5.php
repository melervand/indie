<?
namespace Rule;

class MD5 extends \Rule {
    public function test( $value ) {
        return preg_match('/^[a-f0-9]{32}$/', $this->value);
    }
}