<?php


namespace App\Service;


use Kreait\Firebase\Factory;
use Kreait\Firebase\Database;


class FirebaseService
{
    private Database $database;


    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/../../config/Firebase/firebase_credentials.json')
            ->withDatabaseUri('https://zooarcadia-420b9-default-rtdb.europe-west1.firebasedatabase.app/');


        $this->database = $factory->createDatabase();
    }


    public function saveMessage(array $data): void
    {
        $this->database->getReference('messages')->push($data);
    }


    public function getMessages(): array
    {
        $snapshot = $this->database->getReference('messages')->getSnapshot();
        return $snapshot->getValue() ?? [];
    }


    public function deleteMessage(string $key): void
    {
        $this->database->getReference('messages/' . $key)->remove();
    }


    public function delete(string $path): void
    {
        $this->database->getReference($path)->remove();
    }
}


