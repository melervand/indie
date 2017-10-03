<?

namespace Indie;

use Indie\Interfaces\RuleInterface;

abstract class Rule implements RuleInterface
{
    abstract public function test($value, $key = null);
}