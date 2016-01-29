<?
namespace Rule;

class Required extends \Rule {

    public function test( $value ) {
        return $value==''?false:true;
    }
}