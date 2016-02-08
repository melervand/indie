<?
class Indie {
    private $data;
    private $obj;


    public function __construct( $data = null ) {
        $this->data ?: [];
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
     * @param string $indexpath
     * @return Value
     */
    public function key( $indexpath ) {
        $value = $this->parseIndexPath( $indexpath );
        $isExplicit = $this->isIndexPathExplicit( $indexpath );

        if ( !isset( $this->obj[$indexpath] ) ) {
            $this->obj[ $indexpath ] = new Value( $value, $isExplicit );
        }

        return $this->obj[ $indexpath ];
    }

    /**
     * @param string $indexpath
     * @return bool
     */
    public function isValid( $indexpath = null ) {
        if ( $indexpath ) {
            return $this->key( $indexpath )->isValid();
        }

        return count( $this->getErrors() ) ? false : true;
    }

    /**
     * @param string $indexpath
     * @return string
     */
    public function getValue( $indexpath ) {
        return $this->key($indexpath)->getValue();
    }

    /**
     * @return array
     */
    public function getValues() {
        return array_map( function($value) {
            /** @var Value $value */
            return $value->getValue();
        }, $this->obj);
    }


    /**
     * @param string $indexpath
     * @return array
     */
    public function getErrors( $indexpath = null ) {
        if ( $indexpath ) {
            return $this->key( $indexpath )->getErrors();
        }

        $errors = [];
        foreach( $this->obj as $indexpath => $value ) {
            /** @var Value $value */
            $value_errors = $value->getErrors();
            if ( count($value_errors) ) {
                $errors[$indexpath] = $value->getErrors();
            }
        }

        return $errors;
    }

    protected function parseIndexPath( $indexpath ) {
        $key = explode('[', $indexpath)[0];
        preg_match_all('/\[([a-z0-9_-]+)\]/i', $indexpath, $matches);
        $indexpath_array = $matches[1];

        if ( count( $indexpath_array ) == 0 ) {
            return isset($this->data[$key])?$this->data[$key]:false;
        } else {
            array_unshift( $indexpath_array, $key );
            return $this->goByIndexPath( $indexpath_array, $this->data );
        }
    }

    protected function isIndexPathExplicit( $indexpath ) {
        return substr( $indexpath, -2 ) == "[]";
    }

    protected function goByIndexPath( $indexpath_array, $root_array ) {
        if( count($indexpath_array) > 1 ) {
            return $this->goByIndexPath(array_slice($indexpath_array, 1), $root_array[$indexpath_array[0]]);
        } else {
            return $root_array[ $indexpath_array[0] ];
        }
    }
}