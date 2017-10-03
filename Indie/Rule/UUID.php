<?
namespace Indie\Rule;

use Indie\Rule;

class UUID extends Rule {
    const UUID_V4 = 'v4';

    protected $version;
    protected $regex = [
        'v4' => '~^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$~i'
    ];

    public function __construct( $version ) {
        if ( !array_key_exists( $version, $this->regex ) ) {
            throw new \InvalidArgumentException(
                sprintf('Unknown UUID version "%s"', $version)
            );
        }

        $this->version = $version;
    }

    public function test($value, $key = null)
    {
        return preg_match( $this->regex[$this->version], $value );
    }

}