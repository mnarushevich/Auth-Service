<?php

declare(strict_types=1);

namespace App\Services\AWS;

use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;

class DynamoDBService
{
    private DynamoDbClient $client;

    private Marshaler $marshaler;

    public function __construct()
    {
        $this->client = new DynamoDbClient([
            'version' => 'latest',
            'region' => config('database.connections.dynamodb.region'),
            'credentials' => [
                'key' => config('database.connections.dynamodb.key'),
                'secret' => config('database.connections.dynamodb.secret'),
            ],
            'endpoint' => 'http://localstack:4566',
        ]);

        $this->marshaler = new Marshaler;
    }

    public function createTable(string $tableName): void
    {
        try {
            $this->client->createTable([
                'TableName' => $tableName,
                'KeySchema' => [
                    ['AttributeName' => 'id', 'KeyType' => 'HASH'], // Partition key
                ],
                'AttributeDefinitions' => [
                    ['AttributeName' => 'id', 'AttributeType' => 'S'],
                ],
                'ProvisionedThroughput' => [
                    'ReadCapacityUnits' => 5,
                    'WriteCapacityUnits' => 5,
                ],
            ]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function insertItem(string $tableName, array $data): void
    {
        $data['id'] = uniqid(); // Generate unique ID
        $item = $this->marshaler->marshalItem($data);

        $this->client->putItem([
            'TableName' => $tableName,
            'Item' => $item,
        ]);
    }

    public function getItem(string $tableName, $id): ?array
    {
        $response = $this->client->getItem([
            'TableName' => $tableName,
            'Key' => $this->marshaler->marshalItem(['id' => $id]),
        ]);

        return isset($response['Item']) ? $this->marshaler->unmarshalItem($response['Item']) : null;
    }

    public function deleteItem(string $tableName, $id): void
    {
        $this->client->deleteItem([
            'TableName' => $tableName,
            'Key' => $this->marshaler->marshalItem(['id' => $id]),
        ]);
    }
}
