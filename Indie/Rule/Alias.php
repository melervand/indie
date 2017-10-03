<?
namespace Indie\Rule;

use Indie\Rule;

class Alias extends Rule {

    public function test($value, $key = null)
    {
        return preg_match("#^[a-zA-Z0-9_]+$#", $value);
    }

}