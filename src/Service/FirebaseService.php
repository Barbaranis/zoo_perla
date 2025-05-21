<?php

namespace App\Service;

use Kreait\Firebase\Factory;

class FirebaseService
{
    private $database;

    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(__DIR__.'./../config/firebase/firebase_credentials.json')
            ->withDatabaseUri('https://zooarcadia-420b9-default-rtdb.europe-west1.firebasedatabase.app/');

        $this->database = $factory->createDatabase();
    }

    public function saveMessage(array $data): void
    {
        $this->database->getReference('messages')->push($data);
    }
}

