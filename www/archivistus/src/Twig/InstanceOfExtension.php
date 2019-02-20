<?php
/**
 * Created by PhpStorm.
 * User: minhhoangdang
 * Date: 20/02/19
 * Time: 14:43
 */

namespace App\Twig;


class InstanceOfExtension extends \Twig_Extension
{
    public function getTests()
    {
        return array(
            new \Twig_SimpleTest('instanceof', array($this, 'isInstanceOf')),
        );
    }

    public function isInstanceof($var, $instance) {
        return  $var instanceof $instance;
    }

}