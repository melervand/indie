<?
namespace Rule;

class Required extends \Rule {
    public function validate() {
        return $this->value==''?false:true;
    }
}