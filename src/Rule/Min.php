<?
namespace Rule;

class Min extends \Rule {
    private $min;
    public function __construct( $min ) {
        $this->min = $min;
    }

    public function validate() {
        return $this->value >= $this->min;
    }
}