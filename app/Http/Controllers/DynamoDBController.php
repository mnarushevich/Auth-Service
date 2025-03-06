<?php

namespace App\Http\Controllers;

use App\Services\AWS\DynamoDBService;
use Illuminate\Http\Request;

class DynamoDBController extends Controller
{
    private DynamoDBService $dynamoDBService;

    private const string TABLE = 'audit-logs';

    public function __construct(DynamoDBService $dynamoDBService)
    {
        $this->dynamoDBService = $dynamoDBService;
    }

    public function createTable()
    {
        $this->dynamoDBService->createTable(
            tableName: self::TABLE
        );

        return response()->json(['message' => 'Table created successfully']);
    }

    public function store(Request $request)
    {
        $this->dynamoDBService->insertItem(tableName: self::TABLE, data: $request->all());

        return response()->json([
            'message' => 'Item inserted successfully',
        ]);
    }

    public function show($id)
    {
        return response()->json($this->dynamoDBService->getItem(tableName: self::TABLE, id: $id));
    }

    public function destroy($id)
    {
        $this->dynamoDBService->deleteItem(tableName: self::TABLE, id: $id);

        return response()->json(['message' => 'Item deleted successfully']);
    }
}
