<?php
/**
 * Created by PhpStorm.
 * User: minhhoangdang
 * Date: 20/02/19
 * Time: 14:43
 */

namespace App\Twig;


use App\Entity\GenericEntity;
use phpDocumentor\Reflection\Types\Mixed_;

class InstanceOfExtension extends \Twig_Extension
{
    /*public function getTests()
    {
        return array(
            'instanceof' => new \Twig_SimpleTest('instanceof', array($this, 'isInstanceOf')),
        );
    }

    public function isInstanceof($var, $instance) {
        return  $var instanceof $instance;
    }*/

    public function getTests ()
    {
        return [
            new \Twig_SimpleTest('Entity', function ($object) { return $object instanceof GenericEntity; }),
        ];
    }

}