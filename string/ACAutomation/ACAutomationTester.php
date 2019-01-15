<?php
/**
 * Created by PhpStorm.
 * User: lineofsky2017 copyright(c) 2019
 * Date: 2019/1/13
 * Time: 21:45
 */

require 'ACAutomation.php';

$aca = new ACAutomation();

$aca->setPattern('he');
$aca->setPattern('hehe');
$aca->setPattern('she');
$aca->setInputString('0sheheis');

var_dump($aca->getOccurs());