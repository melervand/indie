<?
require 'vendor/autoload.php';

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
        'minmax' => [
            'min' => 10,
            'max' => 10
        ],
        "url" => [
            'valid' => "http://google.com",
            'notvalid' => "15c1ds"
        ]
    ];

    public function testNotPresentField() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $validator->key( 'notpresent' )->required( 'Required' )->countable('Countable');

        $this->assertArrayHasKey( 'notpresent', $validator->getErrors() );
    }

    public function testFullFormValidation() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $validator->key('required_e')->required( 'Field is required' );
        $validator->key('required_n')->required( 'Field is required' );

        $this->assertFalse( $validator->isValid() );
    }

    public function testMultidimensionalValidation() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $validator->key('first[second][second_e]')->required('Field is required');
        $validator->key('first[second][second_n]')->required('Field is required');

        $this->assertFalse( $validator->isValid( 'first[second][second_e]' ) );
        $this->assertTrue( $validator->isValid( 'first[second][second_n]' ) );
    }

    public function testValuesGetter() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $this->assertEquals('', $validator->key('required_e')->getValue() );
        $this->assertEquals('Hello World!', $validator->key('required_n')->getValue() );

        $this->assertEquals('', $validator->getValue('first[first_e]'));
        $this->assertEquals('Hello World!', $validator->getValue('first[first_n]') );
    }

    public function testChaining() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $validator->key('first[second]')->required('Required')->countable('Countable');
        $validator->key('required_n')->required('Required')->countable('Countable');

        $this->assertTrue($validator->isValid('first[second]'));
        $this->assertFalse($validator->isValid('required_n'));
    }

    public function testRequiredField() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $validator->key('required_e')->required('Field is required');
        $validator->key('required_n')->required('Field is required');

        $this->assertFalse( $validator->key('required_e')->isValid() );
        $this->assertTrue( $validator->key('required_n')->isValid() );
    }

    public function testMinMaxValidator() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $validator->key('minmax[min]')->min( 9, "Min9" );
        $validator->key('minmax[min]')->min( 11, "Min11" );

        $validator->key('minmax[max]')->max( 9, "Max9" );
        $validator->key('minmax[max]')->max( 11, "Max11" );

        $this->assertEquals( 2 , count( $validator->getErrors() ) );
    }

    public function testCustomValidation() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $validator->key('required_n')->custom( function($value) {
            return $value == 'Hello World!';
        }, "Custom" );

        $this->assertTrue( $validator->isValid('required_n') );
    }

    public function testURLValidation() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $validator->key( 'url[valid]' )->url( "Valid URL" );
        $validator->key( 'url[notvalid]' )->url( "Not Valid URL" );

        $this->assertTrue( $validator->isValid('url[valid]') );
        $this->assertFalse( $validator->isValid('url[notvalid]') );
    }
}