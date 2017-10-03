<?
namespace Indie\Rule;

use Indie\Rule;

class MD5 extends Rule {
    public function test($value, $key = null)
    {
        return preg_match('/^[a-f0-9]{32}$/', $value);
    }
}