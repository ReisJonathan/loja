<?php

namespace Hcode;

class Model {

    private $values = [];

    public function __call( $name, $args ) {

        $method = substr( $name, 0, 3 ); // pra retirar o set ou get...
        $fieldName = substr( $name, 3, strlen($name) ); /// pega o nome do campo sem o metodo(get, set..). O strlen pra saber o tamanho do campo e pagar todo independentimente do tamanho de caracteres que tiver.

        var_dump( $method, $fieldName ); // até que funciona rsrs
        
        switch ($method) {
            case 'get':
                return $this->values[$fieldName];
                break;

            case 'set':
                $this->values[$fieldName] = $args[0];
                break;
            
        }

    }

    public function setData( $data = array() ) {

        foreach ($data as $key => $value) {
            
            $this->{"set".$key}($value);

        }

    }

    public function getValues() {
        return $this->values;
    }

}

?>