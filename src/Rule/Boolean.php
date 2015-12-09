<?
namespace Rule;

class Boolean extends \Rule {
    public function validate() {
        return $this->value === true || $this->value === false;
    }
}