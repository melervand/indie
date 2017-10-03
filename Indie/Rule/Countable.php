<?

namespace Indie\Rule;

use Indie\Rule;

class Countable extends Rule
{
    public function test($value, $key = null)
    {
        return is_array($value) || $value instanceof \Countable;
    }
}