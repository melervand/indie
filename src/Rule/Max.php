<?
namespace Rule;

class Max extends \Rule {
    protected $max;
    public function __construct( $max ) {
        $this->max = $max;
    }

    public function test($value, $key = null)
    {
        return $value <= $this->max;
    }
}