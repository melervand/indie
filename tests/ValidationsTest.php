<?
class ValidationsTest extends PHPUnit_Framework_TestCase {
    /** @var  Indie $v */
    private $v;

    public function setUp()
    {
        $post = json_decode( file_get_contents( __DIR__.'/data.json' ), true );
        $this->v = Indie::withLocalization('en_US');
        $this->v->import( $post );
    }

    public function testAlpha() {
        $this->validate( 'alpha', new \Rule\Alpha() );
    }

    public function testBoolean() {
        $this->validate( 'boolean', new \Rule\Boolean() );
    }

    public function testCountable() {
        $this->validate( 'countable', new \Rule\Countable() );
    }

    public function testEmail() {
        $this->validate( 'email', new \Rule\Email() );
    }

    public function testEqual() {
        $this->validate( 'equal', new \Rule\Equals('equal', 'equal[valid]') );
    }

    public function testMax() {
        $this->validate( 'max', new \Rule\Max( 10 ) );
    }

    public function testMin() {
        $this->validate( 'min', new \Rule\Min( 10 ) );
    }

    public function testMD5() {
        $this->validate( 'md5', new \Rule\MD5() );
    }

    public function testNumeric() {
        $this->validate( 'numeric', new \Rule\Numeric() );
    }

    public function testURL() {
        $this->validate( 'url', new \Rule\Url() );
    }

    public function testUUID() {
        $this->validate( 'uuid', new \Rule\UUID('v4') );
    }

    private function validate( $key, $rule ) {
        $this->v->key( $key.'[valid]' )
            ->with( $rule );
        $this->v->key( $key.'[notvalid]' )
            ->with( $rule );

        $this->assertTrue( $this->v->isValid( $key.'[valid]' ) );
        $this->assertFalse( $this->v->isValid( $key.'[notvalid]' ) );
    }
}