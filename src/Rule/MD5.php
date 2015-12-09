<?
namespace Rule;

class MD5 extends \Rule {
    public function validate() {
        return preg_match('/^[a-f0-9]{32}$/', $this->value);
    }
}