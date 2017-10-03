<?
namespace Indie\Rule;

use Indie\Rule;

class Alpha extends Rule
{
    public function test($value, $key = null)
    {
        return ctype_alpha($value);
    }
}