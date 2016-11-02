<?
class Value {
    private $l00n;

    private $explicit;
    private $value;
    private $errors;
    private $indexpath_exists;
    private $optional;

    public function __construct( $value, $optional, $indexpath_exists, $explicit = true, $l00n ) {
        $this->l00n = $l00n;

        $this->explicit = $explicit;
        $this->value = $value;
        $this->optional = $optional;
        $this->indexpath_exists = $indexpath_exists;

        if ( !$this->optional ) {
            $this->required();
        }
    }

    /**
     * @param callable|Rule $rule
     * @param string $message
     * @return $this
     */
    public function with( $rule, $message = null ) {
        if ( $rule instanceof \Rule ) {
            /** @var Rule $rule */
            $valid = $rule->setValue( $this->value )->validate( $this->explicit );
            $message = $this->localize( $rule, $message );
        } else {
            if ( $this->explicit && is_array( $this->value ) ) {
                $checks = [];
                foreach ($this->value as $value) {
                    $checks[] = $rule( $value );
                }

                $valid = !in_array( false, $checks );
            } else {
                $valid = $rule( $this->value );
            }
        }

        if ( $this->indexpath_exists ) {
            $valid ?: $this->errors[] = $message;
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isValid() {
        return count( $this->errors ) ? false : true;
    }

    /**
     * @return mixed
     */
    public function getValue( $emptyValue = false ) {
        if ( $emptyValue ) {
            return $this->value;
        }

        return $this->value==="" ? NULL : $this->value;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return count($this->errors) ? $this->errors : [];
    }

    /**
     * @param \Rule $rule
     * @param string $format
     * @return string $message
     */
    private function localize( $rule, $format = null ) {
        $reflection = new ReflectionClass( $rule );
        $rule_name = strtolower( $reflection->getShortName() );

        $format = $rule->message( !$format ? $this->getLocalizedString( $rule_name ) : $format );

        return $format;
    }

    /**
     * @param string $key
     * @return string
     */
    private function getLocalizedString( $key ) {
        $localization = [];
        $localization_path =  __DIR__ . '/Localization/' . $this->l00n . '.json';
        if ( file_exists( $localization_path ) ) {
            $localization = json_decode( file_get_contents( $localization_path ), true );
        }

        return isset( $localization[$key] ) ? $localization[ $key ] : '';
    }

    /**
     * Default required check
     * Called as first check
     */
    private function required() {
        $this->with( function($value) {
            return $value===""?false:true;
        }, $this->getLocalizedString( 'required' ) );
    }
}