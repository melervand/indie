<?
namespace Rule;

class Min extends \Rule {
    private $min;
    public function __construct( $min ) {
        $this->min = $min;
    }

    public function test( $value ) {
        return $value >= $this->min;
    }
}