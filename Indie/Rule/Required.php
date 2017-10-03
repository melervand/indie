<?

namespace Indie\Rule;

use Indie\Rule;

class Required extends Rule
{
    public function test($value, $key = null)
    {
        return $value == "" ? false : true;
    }
}