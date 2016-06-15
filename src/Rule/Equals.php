<?
namespace Rule;

class Equals extends \Rule {
    protected $to;
    protected $field;

    public function __construct( $to, $field = '' ) {
        $this->to = $to;
        $this->field = $field;
    }

    public function test( $value ) {
        return trim($this->value) == $this->to;
    }
}