<?
namespace Rule;

class Min extends \Rule {
    protected $min;
    public function __construct( $min ) {
        $this->min = $min;
    }

    public function test($value, $key = null)
    {
        return $value >= $this->min;
    }
}