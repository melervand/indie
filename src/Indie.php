<?
class Indie {
    private $data;
    private $obj;


    public function __construct( $data = null ) {
        $data ?: [];
    }

    /**
     * @param array $data
     * @return Indie
     */
    public function validate( $data = null ) {
        if ( $data !== null ) {
            $this->data = $data;
        }

        return $this;
    }

    /**
     * @param string $indexpath
     * @return IndieValue
     */
    public function key( $indexpath ) {
        $value = $this->parseIndexPath( $indexpath );

        if ( !isset( $this->obj[$indexpath] ) ) {
            $this->obj[ $indexpath ] = new IndieValue( $value );
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
            $errors = array_merge( $errors, $value->getErrors() );
        }

        return $errors;
    }

    protected function parseIndexPath( $indexpath ) {
        $key = explode('[', $indexpath)[0];
        preg_match_all('/\[([a-z0-9_-]+)\]/i', $indexpath, $matches);
        $indexpath_array = $matches[1];

        if ( count( $indexpath_array ) == 0 ) {
            return $this->data[$key];
        } else {
            array_unshift( $indexpath_array, $key );
            return $this->goByIndexPath( $indexpath_array, $this->data );
        }
    }

    protected function goByIndexPath( $indexpath_array, $root_array ) {
        if( count($indexpath_array) > 1 ) {
            return $this->goByIndexPath(array_slice($indexpath_array, 1), $root_array[$indexpath_array[0]]);
        } else {
            return $root_array[ $indexpath_array[0] ];
        }
    }
}