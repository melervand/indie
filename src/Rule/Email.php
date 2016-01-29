<?
namespace Rule;

class Email extends \Rule {
    public function test( $value ) {
        return filter_var( $value, FILTER_VALIDATE_EMAIL );
    }
}