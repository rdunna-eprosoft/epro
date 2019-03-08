<?php
/**
 * Created by PhpStorm.
 * User: Karthik
 * Date: 12/27/2018
 * Time: 3:08 PM
 */

namespace Epro\Crm;


interface CrmInterface
{
    public function __call($name, array $arguments);
}