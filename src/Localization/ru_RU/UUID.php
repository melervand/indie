<?
namespace Localization\ru_RU;

use Localization\Localization;

class UUID extends Localization {
    public function message()
    {
        return sprintf('Неверный UUID версии %s', $this->rule->version);
    }
}