<?

use Indie\Indie;
use Indie\Rule;

class RuleTest extends \PHPUnit\Framework\TestCase
{

    public function testAlnumField()
    {
        $v = new Indie([
            'alnum'  => 'alpha12345',
            '_alnum' => 'alpha%$',
        ]);

        $v->key('alnum')
          ->with(new Rule\Alnum());
        $v->key('_alnum')
          ->with(new Rule\Alnum());

        $this->assertTrue($v->isValid('alnum'));
        $this->assertFalse($v->isValid('_alnum'));
    }

    public function testUUIDField()
    {
        $v = Indie::instance([
            'uuid'  => '58fb8dcb-4453-40d5-97c5-fc4ce291f74d',
            '_uuid' => 'string',
        ]);

        $v->key('uuid')
          ->with(new Rule\UUID(Rule\UUID::UUID_V4));
        $v->key('_uuid')
          ->with(new Rule\UUID(Rule\UUID::UUID_V4));

        $this->assertTrue($v->isValid('uuid'));
        $this->assertFalse($v->isValid('_uuid'));
    }

}