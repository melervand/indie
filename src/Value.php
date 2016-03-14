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

        $this->required();
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

            $reflection = new ReflectionClass( $rule );
            $rule_name = strtolower( $reflection->getShortName() );

            $localization_path =  __DIR__ . '/Localization/' . $this->l00n . '.json';
            if ( file_exists( $localization_path ) ) {
                $localization = json_decode( file_get_contents( $localization_path ), true );

                if ($message == null) {
                    $message = $rule->message( isset($localization[$rule_name])?$localization[$rule_name]:'' );
                } else {
                    $message = $rule->message( $message );
                }
            }
        } else {
            $valid = $rule( $this->value );
        }

        !$valid && !$this->optional ? $this->errors[] = $message:false;

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
    public function getValue() {
        return $this->value;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return count($this->errors) ? $this->errors : [];
    }

    /**
     * Default required check
     * Called as first check
     */
    private function required() {
        $this->with( function($value) {
            return $value==''?false:true;
        } );
    }
}