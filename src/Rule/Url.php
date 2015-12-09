<?
namespace Rule;

class Url extends \Rule {
    public function validate() {
        return filter_var( $this->value, FILTER_VALIDATE_URL );
    }
}