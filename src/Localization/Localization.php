<?
namespace Localization;

abstract class Localization {
    protected $rule;
    public function __construct($rule) {
        $this->rule = $rule;
    }

    abstract public function message();
}