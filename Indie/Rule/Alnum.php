<?

namespace Indie\Rule;

use Indie\Rule;

class Alnum extends Rule
{
    public function test($value, $key = null)
    {
        return ctype_alnum($value);
    }
}