<?

namespace Indie;

use Indie\Exceptions\NoRuleAssignedException;
use Indie\Rule\Required;

class Indie
{
    private $post = [];
    private $data = [];

    /**
     * Indie constructor.
     *
     * @param array $post
     */
    public function __construct($post = [])
    {
        return $this->import($post);
    }

    /**
     * Return instance
     *
     * @param array $post
     *
     * @return \Indie\Indie
     */
    public static function instance($post = [])
    {
        return new self($post);
    }

    /**
     * Import POST data
     *
     * @param array $post
     *
     * @return $this
     */
    public function import($post)
    {
        $this->post = array_replace_recursive($this->post, $post);

        return $this;
    }

    /**
     * Reset validator
     */
    public function clear()
    {
        $this->post = [];
        $this->data = [];
    }

    /**
     * Shorthand for "key" method with "required" rule applied
     *
     * @param string      $path    Path to array element using "dot" notation
     * @param string|null $message Localized error message
     * @param boolean     $asArray Validate value as array
     *
     * @return \Indie\Value
     */
    public function required($path, $message = null, $asArray = false)
    {
        return $this->key($path, $asArray)->with(new Required(), $message, true);
    }

    /**
     * Shorthand for "key" method
     *
     * @param string  $path    Path to array element using "dot" notation
     * @param boolean $asArray Validate value as array
     *
     * @return \Indie\Value
     */
    public function optional($path, $asArray = false)
    {
        return $this->key($path, $asArray);
    }

    /**
     * @param string  $path    Path to array element using "dot" notation
     * @param boolean $asArray Validate value as array
     *
     * @return \Indie\Value
     */
    public function key($path, $asArray = false)
    {
        if ( isset($this->data[ $path ]) ) {
            return $this->data[ $path ];
        }

        return $this->data[ $path ] = new Value($this->getValueByPath($path), $asArray);
    }

    /**
     * Return validation result
     *
     * @param string|null $path
     *
     * @return bool
     */
    public function isValid($path = null)
    {
        if ( $path ) {
            if ( isset($this->data[ $path ]) ) {
                return ($this->data[ $path ])->isValid();
            }

            throw new NoRuleAssignedException();
        }

        return count($this->getErrors()) ? false : true;
    }

    /**
     * Return value by path using "dot" notation
     *
     * @param string  $path   Path to array element using "dot" notation
     * @param boolean $escape Try to escape string value
     *
     * @return array|string
     */
    public function getValue($path, $escape = true)
    {
        $value = $this->getValueByPath($path);

        if ( $escape && is_string($value) ) {
            return htmlspecialchars($value);
        }

        return $value;
    }

    /**
     * Return errors
     *
     * @param string|null $path
     *
     * @return array
     */
    public function getErrors($path = null)
    {
        if ( $path ) {
            if ( isset($this->data[ $path ]) ) {
                return ($this->data[ $path ])->getErrors();
            }

            throw new NoRuleAssignedException();
        }

        return array_filter(array_map(function($value) {
            /** @var \Indie\Value $value */
            return $value->getErrors();
        }, $this->data), function($element) {
            return $element ? true : false;
        });
    }

    /**
     * Return value by path using "dot" notation
     *
     * @param string $path
     *
     * @return array|string
     */
    private function getValueByPath($path)
    {
        if ( is_null($path) ) {
            return $this->post;
        }

        if ( array_key_exists($path, $this->post) ) {
            return $this->post[ $path ];
        }

        $value = $this->post;
        foreach ( explode('.', $path) as $segment ) {
            if ( is_array($value) && isset($value[ $segment ]) ) {
                $value = $value[ $segment ];
            } else {
                return "";
            }
        }

        return $value;
    }
}