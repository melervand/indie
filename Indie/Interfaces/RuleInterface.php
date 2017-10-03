<?

namespace Indie\Interfaces;

interface RuleInterface
{
    public function test($value, $key = null);
}