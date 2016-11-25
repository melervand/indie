<?
namespace Rule;

class Equals extends \Rule {
    protected $to;
    protected $field;

    public function __construct( $to, $field = '' ) {
        $this->to = $to;
        $this->field = $field;
    }

    public function test($value, $key = null)
    {
        return trim($this->value) == $this->to;
    }
}