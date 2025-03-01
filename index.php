<?php

use Faker\Factory;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\Writer;
use Source\Models\User;

require __DIR__ . "/vendor/autoload.php";

$output = false;
if ($output) {
  $users = (new User())->find()->fetch(true);
  $csv = Writer::createFromString("");
  $csv->insertOne([
    "first_name",
    "last_name",
    "genre"
  ]);

  foreach ($users as $user) {
    $csv->insertOne([
      $user->first_name,
      $user->last_name,
      $user->genre
    ]);
  }

  $csv->output("users.csv");
}

$create = false;
if ($create) {
  $users = (new User())->find()->fetch(true);
  $stream = fopen(__DIR__ . "/csvs/users.csv", "w");

  $csv = Writer::createFromStream($stream);
  $csv->insertOne([
    "first_name",
    "last_name",
    "genre"
  ]);

  foreach ($users as $user) {
    $csv->insertOne([
      $user->first_name,
      $user->last_name,
      $user->genre
    ]);
  }

  echo true;
}

$edit = true;
if ($edit) {
  $stream = fopen(__DIR__ . "/csvs/users.csv", "a+");
  $csv = Writer::createFromStream($stream);
  $faker = Factory::create("pt_br");

  $genre = ["male", "female"][rand(0, 1)];

  $csv->insertOne([
    $faker->firstName($genre),
    $faker->lastName($genre),
    strtoupper(substr($genre, 0, 1))
  ]);
}

$read = true;
if ($read) {
  $stream = fopen(__DIR__ . "/csvs/users.csv", "r");
  $csv = Reader::createFromStream($stream);

  $csv->setDelimiter(",");
  $csv->setHeaderOffset(0);

  $stmt = (new Statement());
  $users = $stmt->process($csv);

  foreach ($users as $user) {
    var_dump($user);
  }
}
