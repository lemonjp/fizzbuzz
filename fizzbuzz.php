<?php
namespace Lucky;

require_once 'FizzBuzzSpecification.php';
require_once 'FizzBuzzApplication.php';

$app = new FizzBuzzApplication();
$app->addSpecAndMessage(new FizzBuzzSpecification(15), 'fizzbuzz');
$app->addSpecAndMessage(new FizzBuzzSpecification(3), 'fizz');
$app->addSpecAndMessage(new FizzBuzzSpecification(5), 'buzz');

$data = range(1,30);

$app->run($data);

