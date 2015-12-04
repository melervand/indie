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
        10 => [
            0 => '',
            1 => 'Hello World!'
        ]
    ];

    public function testFullFormValidation() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $validator->key('required_e')->required( 'Field is required' );
        $validator->key('required_n')->required( 'Field is required' );

        $this->assertFalse( $validator->isValid() );
    }

    public function testRequiredField() {
        $validator = new Indie();
        $validator->validate($this->POST);

        $validator->key('required_e')->required('Field is required');
        $validator->key('required_n')->required('Field is required');
        $validator->key('first[first_e]')->required('Field is required');
        $validator->key('first[first_n]')->required('Field is required');
        $validator->key('first[second][second_e]')->required('Field is required');
        $validator->key('first[second][second_n]')->required('Field is required');

        $validator->key('10[0]')->required('Field is required');
        $validator->key('10[1]')->required('Field is required');

        $this->assertFalse( $validator->key('required_e')->isValid() );
        $this->assertTrue( $validator->key('required_n')->isValid() );

        $this->assertFalse( $validator->isValid( 'first[first_e]' ) );
        $this->assertTrue( $validator->isValid( 'first[first_n]' ) );
        $this->assertFalse( $validator->isValid( 'first[second][second_e]' ) );
        $this->assertTrue( $validator->isValid( 'first[second][second_n]' ) );

        $this->assertFalse( $validator->isValid( '10[0]' ) );
        $this->assertTrue( $validator->isValid( '10[1]' ) );
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

        $validator->key( 'first[second]' )->required('Required')->countable('Countable');
        $validator->key( 'required_n' )->required('Required')->countable('Countable');

        $this->assertTrue( $validator->isValid( 'first[second]' ) );
        $this->assertFalse( $validator->isValid( 'required_n' ) );
    }
}