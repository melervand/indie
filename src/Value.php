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

            if ($message == null) {
                $reflection = new ReflectionClass( $rule );
                $localization_class = sprintf( '\Localization\%s\%s', $this->l00n, $reflection->getShortName() );

                if ( class_exists( $localization_class ) ) {
                    /** @var \Localization\Localization $localization */
                    $localization = new $localization_class($rule);
                    $message = $localization->message();
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