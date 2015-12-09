<?
namespace Rule;

class Equals extends \Rule {
    private $to;

    public function __construct( $to ) {
        $this->to = $to;
    }

    public function validate() {
        return trim($this->value) == $this->to;
    }
}