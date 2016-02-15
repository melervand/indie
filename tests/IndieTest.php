<?
//run tests from 'indie' folder like "vendor/bin/phpunit --bootstrap vendor/autoload.php tests/

class IndieTest extends PHPUnit_Framework_TestCase {
    /** @var  Indie $v */
    private $v;
    private $post;

    public function setUp()
    {
        $this->post = json_decode( file_get_contents( __DIR__.'/data.json' ), true );
        $this->v = new Indie();
        $this->v->import( $this->post );
    }

    public function testClearAndEmptyPOST() {
        $this->v->key( 'required[valid]' )->with( new \Rule\Required() );
        $this->assertTrue( $this->v->isValid( 'required[valid]' ) );

        $this->v->clear();

        $this->v->key( 'required[valid]' )->with( new \Rule\Required() );
        $this->assertFalse( $this->v->isValid( 'required[valid]' ) );

        //Reimport for next tests
        $this->v->import( $this->post );
    }

    public function testNotPresentField() {
        $this->v->key( 'notpresent' )
            ->with( new Rule\Required(), "Required" );

        $this->assertArrayHasKey( 'notpresent', $this->v->getErrors() );
    }

    public function testMultidimensionalValidation() {
        $this->v->key('mdim[valid][valid]')
            ->with( new Rule\Equals( 'valid' ) );

        $this->assertTrue( $this->v->isValid( 'mdim[valid][valid]' ) );
    }

    public function testValuesGetter() {
        $this->assertEquals('value', $this->v->key('value')->getValue() );
        $this->assertEquals('value', $this->v->getValue('value'));
    }

    public function testChaining() {
        $v = $this->v->key('required[valid]')
            ->with( new Rule\Required() )
            ->with( new Rule\Equals( 'string' ) );

        $this->assertInstanceOf( get_class( $this->v->key('required[valid]') ), $v );
    }

    public function testArrayValidation() {
        $this->v->key( 'countable[valid][]' )
            ->with( new Rule\Numeric() );

        $this->v->key( 'countable[notvalid][]' )
            ->with( new Rule\Numeric() );

        $this->assertTrue( $this->v->isValid( 'countable[valid][]' ) );
        $this->assertFalse( $this->v->isValid( 'countable[notvalid][]' ) );
    }

    public function testMultipleImport() {
        $this->v->import( [
            "import" => "import"
        ]);

        $this->v->key('import')
            ->with( new Rule\Equals( 'import' ) );

        $this->assertTrue( $this->v->isValid( 'import' ) );
    }

    public function testCustomValidation() {
        $this->v->key('countable[valid]')->with( function($value) {
            return $value[-1] == 15;
        } );

        $this->assertTrue( $this->v->isValid('countable[notvalid]') );
    }

    public function testLocalization() {
        $v = new Indie('ru_RU');
        $v->import( $this->post );

        $v->key( 'uuid[valid]' )->with( new Rule\UUID('v4') );
        $v->key( 'uuid[notvalid]' )->with( new Rule\UUID('v4') );

        $this->assertEquals( 'Неверный UUID версии v4', $v->getErrors('uuid[notvalid]')[0], "Localization Failed" );
    }
}