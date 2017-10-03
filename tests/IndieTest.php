<?

use Indie\Indie;
use Indie\Rule;

class IndieTest extends \PHPUnit\Framework\TestCase
{
    public function testClearFunction()
    {
        $v = new Indie([
            "required"  => "required",
            "_required" => "",
        ]);

        $this->assertInternalType("string", $v->getValue('required'));

        $v->clear();

        $this->assertEquals("", $v->getValue('required'));
    }

    public function testRequiredField()
    {
        $v = new Indie([
            "required"  => "required",
            "_required" => "",
        ]);

        $v->required('required');
        $v->required('_required');

        $this->assertTrue($v->isValid('required'));
        $this->assertFalse($v->isValid('_required'));
    }

    public function testOptionalField()
    {
        $v = new Indie([
            'optional'       => '',
            'optionalEmail'  => 'example@example.com',
            '_optionalEmail' => 'string',
        ]);

        $v->optional('optional');
        $v->optional('optionalEmail')
          ->with(new Rule\Email());
        $v->optional('_optionalEmail')
          ->with(new Rule\Email());

        $this->assertTrue($v->isValid('optional'));
        $this->assertTrue($v->isValid('optionalEmail'));
        $this->assertFalse($v->isValid('_optionalEmail'));
    }

    public function testDotNotation()
    {
        $v = new Indie([
            'dot' => [
                'notation'  => 'required',
                '_notation' => '',
            ],
        ]);

        $v->required('dot.notation');
        $v->required('dot._notation');

        $this->assertTrue($v->isValid('dot.notation'));
        $this->assertFalse($v->isValid('dot._notation'));
    }

    public function testArrayValidation()
    {
        $v = new Indie([
            'dot' => [
                'notation'  => [
                    10, 11, 12, 13, 14, 15,
                ],
                '_notation' => "string",
            ],
        ]);

        $v->key('dot.notation', true)
          ->with(new Rule\Required())
          ->with(new Rule\Numeric());

        $v->key('dot._notation', true)
          ->with(new Rule\Required())
          ->with(new Rule\Numeric());

        $v->key('dot.notation.0', false)
          ->with(new Rule\Required())
          ->with(new Rule\Numeric());

        $this->assertTrue($v->isValid('dot.notation'));
        $this->assertFalse($v->isValid('dot._notation'));
        $this->assertTrue($v->isValid('dot.notation.0'));
    }

    public function testValueGetter()
    {
        $v = new Indie([
            'dot' => [
                'notation'  => [
                    10, 11, 12, 13, 14, 15,
                ],
                '_notation' => "string",
            ],
        ]);

        $v->required('dot.notation');
        $v->required('dot._notation');

        $this->assertEquals([10, 11, 12, 13, 14, 15], $v->getValue('dot.notation'));
        $this->assertEquals("string", $v->getValue('dot._notation'));
    }

    public function testErrorGetter()
    {
        $v = new Indie([
            "required"  => "required",
            "_required" => "",
        ]);

        $v->required('required', "ERROR1");
        $v->required('_required', "ERROR2");

        $this->assertArrayHasKey("_required", $v->getErrors());
        $this->assertNotInternalType("array", $v->getErrors('required'));
        $this->assertContains("ERROR2", $v->getErrors('_required'));
    }
}