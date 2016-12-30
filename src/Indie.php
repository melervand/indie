<?
/** @property string $l00n */
/** @property array data */
/** @property Value[] $obj */

class Indie {
    private $l00n;
    private $data;
    private $obj;

    private function __construct( $l00n = 'en_US' ) {
        $this->l00n = $l00n;

        $this->data = [];
        $this->obj = [];
    }

    public static function withLocalization( $l00n ) {
        return new self( $l00n );
    }

    public function clear() {
        $this->data = [];
        $this->obj = [];
    }

    /**
     * @param array $data
     * @return Indie
     */
    public function import( $data = null ) {
        if ( $data !== null ) {
            $this->data = $this->data?array_merge_recursive( $this->data, $data ):$data;
        }

        return $this;
    }

    /**
     * One line key setter
     * @param string $indexpath
     * @return Value
     */
    public function key( $indexpath, $optional = false ) {
        if ( !isset($this->obj[ $indexpath ]) ) {
            $indexpath_array = $this->parseIndexPath( $indexpath );
            $indexpath_exists = $this->isIndexPathExist( $indexpath_array, $this->data );

            $value = $this->getIndexPathValue( $indexpath_array, $this->data );
            $isExplicit = $this->isIndexPathExplicit( $indexpath );

            $this->obj[ $indexpath ] = new Value( $value, $optional, $indexpath_exists, $isExplicit, $this->l00n );
        }

        return $this->obj[ $indexpath ];
    }

    /**
     * Required key setting helper
     * @param string $indexpath
     * @return Value
     */
    public function required( $indexpath ) {
        return $this->key( $indexpath, false );
    }

    /**
     * Optional key setting helper
     * @param string $indexpath
     * @return Value
     */
    public function optional( $indexpath ) {
        return $this->key( $indexpath, true );
    }

    /**
     * @param string $indexpath
     * @return bool
     */
    public function isValid( $indexpath = null ) {
        $result = true;
        if ( $indexpath ) {
            if ( isset( $this->obj[ $indexpath ] ) ) {
                $result = $this->obj[ $indexpath ]->isValid();
            }

            return $result;
        }

        $result = count( $this->getErrors() ) ? false : true;

        return $result;
    }

    /**
     * @param string $indexpath
     * @return string
     */
    public function getValue( $indexpath ) {
        $indexpath_array = $this->parseIndexPath( $indexpath );
        return $this->getIndexPathValue( $indexpath_array, $this->data );
    }

    /**
     * @param bool $emptyValues
     * @return array
     */
    public function getValues( $emptyValues = false ) {
        $values = array_filter($this->obj, function($value) use ($emptyValues) {
            /** @var Value $value */
            return !is_null( $value->getValue( $emptyValues ) );
        });

        return array_map( function($value) use ( $emptyValues ) {
            /** @var Value $value */
            return $value->getValue( $emptyValues );
        }, $values);
    }

    /**
     * @param string $indexpath
     * @return array
     */
    public function getErrors( $indexpath = null ) {
        $errors = [];
        if ( $indexpath ) {
            if ( isset( $this->obj[ $indexpath ] ) ) {
                $errors = $this->obj[ $indexpath ]->getErrors();
            }

            return $errors;
        }

        foreach( $this->obj as $indexpath => $value ) {
            /** @var Value $value */
            $value_errors = $value->getErrors();
            if ( count($value_errors) ) {
                $errors[$indexpath] = $value_errors;
            }
        }

        return $errors;
    }

    /**
     * Parses string indexpath and returns array of keys
     * @param string $indexpath
     * @return string[]
     */
    protected function parseIndexPath( $indexpath ) {
        $key = explode('[', $indexpath)[0];
        preg_match_all('/\[([a-z0-9_-]+)\]/i', $indexpath, $matches);
        $indexpath_array = $matches[1];
        array_unshift( $indexpath_array, $key );

        return $indexpath_array;
    }

    /**
     * Checks whether indexpath exists
     * @param string[] $indexpath_array
     * @param array $root_array
     * @return bool
     */
    protected function isIndexPathExist( $indexpath_array, $root_array ) {
        if ( count( $indexpath_array ) > 1 ) {
            return $this->isIndexPathExist( array_slice($indexpath_array, 1), $root_array[$indexpath_array[0]] );
        } else {
            $indexPathExists = isset( $root_array[ $indexpath_array[0] ] ) && $root_array[ $indexpath_array[0] ] !== "";
            return $indexPathExists;
        }
    }

    /**
     * Checks if user wants to validate array
     * @param string $indexpath
     * @return bool
     */
    protected function isIndexPathExplicit( $indexpath ) {
        return substr( $indexpath, -2 ) == "[]";
    }

    /**
     * Traverses array and returns value by indexpath
     * @param string[] $indexpath_array
     * @param array $root_array
     * @return string
     */
    protected function getIndexPathValue( $indexpath_array, $root_array ) {
        if( count($indexpath_array) > 1 ) {
            return $this->getIndexPathValue(array_slice($indexpath_array, 1), $root_array[$indexpath_array[0]]);
        } else {
            return isset($root_array[ $indexpath_array[0] ])?$root_array[ $indexpath_array[0] ]:'';
        }
    }
}