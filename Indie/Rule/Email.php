<?

namespace Indie\Rule;

use Indie\Rule;

class Email extends Rule
{
    public function test($value, $key = null)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}