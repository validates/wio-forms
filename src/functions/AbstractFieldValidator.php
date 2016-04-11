<?php
namespace WioForms;


abstract class AbstractFieldValidator
{
    /*
    functions used to validate, other for each validator type
    */
    abstract function validatorPHP( );
    abstract function validatorJS( );

}

?>
