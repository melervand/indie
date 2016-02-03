<?
namespace Rule;

class UUID extends \Rule {
    private $version;
    private $regexes = [
        'v4' => '~^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$~i'
    ];

    public function __construct( $version='v4' ) {
        if ( !array_key_exists( $version, $this->regexes ) ) {
            throw new \InvalidArgumentException(
                sprintf('Unknown UUID version "%s"', $version)
            );
        }

        $this->version = $version;
    }

    public function test( $value ) {
        return preg_match( $this->regexes[$this->version], $value );
    }
}