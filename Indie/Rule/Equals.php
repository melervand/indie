<?
namespace Indie\Rule;

use Indie\Rule;

class Equals extends Rule
{
    protected $to;

    public function __construct($to)
    {
        $this->to = $to;
    }

    public function test($value, $key = null)
    {
        return trim($value) == $this->to;
    }
}