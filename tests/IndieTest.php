<?
//run tests from 'indie' folder like "vendor/bin/phpunit --bootstrap vendor/autoload.php tests/

class IndieTest extends PHPUnit_Framework_TestCase {
    private $POST = [
        'required_e' => '',
        'required_n' => 'Hello World!',
        'first' => [
            'first_e' => '',
            'first_n' => 'Hello World!',
            'second' => [
                'second_e' => '',
                'second_n' => 'Hello World!'
            ]
        ],
        "array" => [
            "valid" => [
                10,
                11,
                12,
                13
            ],
            "notvalid" => [
                10,
                11,
                12,
                "text"
            ]
        ],
        "import" => [
            "example" => "example"
        ],
        'minmax' => [
            'min' => 10,
            'max' => 10
        ],
        "url" => [
            'valid' => "http://google.com",
            'notvalid' => "15c1ds"
        ],
        "boolean" => [
            "valid" => true,
            "notvalid" => "12a"
        ],
        "email" => [
            "valid" => "example@example.com",
            "notvalid" => "example"
        ],
        "uuid" => [
            "valid" => "f47ac10b-58cc-4372-a567-0e02b2c3d479",
            "notvalid" => "f47cdscdsDWF58cc-4372-a567-0e02b2c3d479"
        ]
    ];

    public function testEmptyPost() {
        $validator = new Indie();
        $validator->import([]);

        $validator->key( 'empty' )
            ->with( new Rule\Required(), "Required" );

        $this->assertFalse( $validator->isValid() );
    }

    public function testEmptyValidations() {
        $validator = new Indie();
        $this->assertTrue( $validator->isValid() );
    }

    public function testNotPresentField() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key( 'notpresent' )
            ->with( new Rule\Required(), "Required" )
            ->with( new Rule\Countable(), "Countable" );

        $this->assertArrayHasKey( 'notpresent', $validator->getErrors() );
    }

    public function testFullFormValidation() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key('required_e')
            ->with( new Rule\Required(), "Required");

        $validator->key('required_n')
            ->with( new Rule\Required(), "Required");

        $this->assertFalse( $validator->isValid() );
    }

    public function testMultidimensionalValidation() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key('first[second][second_e]')
            ->with( new Rule\Required(), 'Field is required');
        $validator->key('first[second][second_n]')
            ->with( new Rule\Required(), 'Field is required');

        $this->assertFalse( $validator->isValid( 'first[second][second_e]' ) );
        $this->assertTrue( $validator->isValid( 'first[second][second_n]' ) );
    }

    public function testValuesGetter() {
        $validator = new Indie();
        $validator->import($this->POST);

        $this->assertEquals('', $validator->key('required_e')->getValue() );
        $this->assertEquals('Hello World!', $validator->key('required_n')->getValue() );

        $this->assertEquals('', $validator->getValue('first[first_e]'));
        $this->assertEquals('Hello World!', $validator->getValue('first[first_n]') );
    }

    public function testChaining() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key('first[second]')
            ->with( new Rule\Required(), 'Required')
            ->with( new Rule\Countable(), 'Countable');
        $validator->key('required_n')
            ->with( new Rule\Required(), 'Required')
            ->with( new Rule\Countable(), 'Countable');

        $this->assertTrue($validator->isValid('first[second]'));
        $this->assertFalse($validator->isValid('required_n'));
    }

    public function testRequiredField() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key('required_e')->with( new Rule\Required(), 'Field is required');
        $validator->key('required_n')->with( new Rule\Required(), 'Field is required');

        $this->assertFalse( $validator->key('required_e')->isValid() );
        $this->assertTrue( $validator->key('required_n')->isValid() );
    }

    public function testArrayValidation() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key( 'array[valid][]' )
            ->with( new Rule\Numeric(), "Should be numeric" );

        $validator->key( 'array[notvalid][]' )
            ->with( new Rule\Numeric(), "Should be numeric" );

        $this->assertTrue( $validator->isValid( 'array[valid][]' ) );
        $this->assertFalse( $validator->isValid( 'array[notvalid][]' ) );
    }

    public function testImport() {
        $validator = new Indie();
        $validator->import($this->POST);
        $validator->import( [
            "import" => [
                "example2" => "example2"
            ]
        ]);

        $validator->key('import[example]')
            ->with( new Rule\Equals( 'example' ), "Not Valid" );
        $validator->key( 'import[example2]' )
            ->with( new Rule\Equals( 'example2' ), "Not Valid" );

        $this->assertTrue( $validator->isValid( 'import[example]' ) );
        $this->assertTrue( $validator->isValid( 'import[example2]' ) );
    }

    public function testMinMaxValidator() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key('minmax[min]')->with( new Rule\Min( 9 ), "Min9" );
        $validator->key('minmax[min]')->with( new Rule\Min( 11 ), "Min11" );

        $validator->key('minmax[max]')->with( new Rule\Max( 9 ), "Max9" );
        $validator->key('minmax[max]')->with( new Rule\Max( 11 ), "Max11" );

        $this->assertEquals( 2 , count( $validator->getErrors() ) );
    }

    public function testCustomValidation() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key('required_n')->with( function($value) {
            return $value == 'Hello World!';
        }, "Custom" );

        $this->assertTrue( $validator->isValid('required_n') );
    }

    public function testURLValidation() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key( 'url[valid]' )->with( new Rule\Url(), "Valid URL" );
        $validator->key( 'url[notvalid]' )->with( new Rule\Url(), "Not Valid URL" );

        $this->assertTrue( $validator->isValid('url[valid]') );
        $this->assertFalse( $validator->isValid('url[notvalid]') );
    }

    public function testBooleanValidator() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key('boolean[valid]')->with( new Rule\Boolean(), "Valid Boolean");
        $validator->key('boolean[notvalid]')->with( new Rule\Boolean(), "Not Valid Boolean");

        $this->assertTrue($validator->isValid('boolean[valid]'));
        $this->assertFalse($validator->isValid('boolean[notvalid]'));
    }

    public function testEqualsValidator() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key( 'required_n' )->with( new Rule\Equals( 'Hello World!' ), "Not Equal" );
        $validator->key( 'required_e' )->with( new Rule\Equals( 'Hello World!' ), "Not Equal" );

        $this->assertTrue( $validator->isValid( 'required_n' ) );
        $this->assertFalse( $validator->isValid( 'required_e' ) );
    }

    public function testEmailValidator() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key( 'email[valid]' )->with( new Rule\Email(), "Valid" );
        $validator->key( 'email[notvalid]' )->with( new Rule\Email(), "Not Valid" );

        $this->assertTrue( $validator->isValid( 'email[valid]' ) );
        $this->assertFalse( $validator->isValid( 'email[notvalid]' ) );
    }

    public function testUUIDValidator() {
        $validator = new Indie();
        $validator->import($this->POST);

        $validator->key( 'uuid[valid]' )->with( new Rule\UUID('v4'), "Valid" );
        $validator->key( 'uuid[notvalid]' )->with( new Rule\UUID('v4'), "Not Valid" );

        $this->assertTrue( $validator->isValid( 'uuid[valid]' ) );
        $this->assertFalse( $validator->isValid( 'uuid[notvalid]' ) );
    }

    public function testLocalization() {
        $validator = new Indie('ru_RU');
        $validator->import($this->POST);

        $validator->key( 'uuid[valid]' )->with( new Rule\UUID('v4') );
        $validator->key( 'uuid[notvalid]' )->with( new Rule\UUID('v4') );

        $this->assertTrue( $validator->isValid( 'uuid[valid]' ) );
        $this->assertFalse( $validator->isValid( 'uuid[notvalid]' ) );

        $this->assertEquals( 'Неверный UUID версии v4', $validator->getErrors('uuid[notvalid]')[0], "Localization Failed" );
    }
}