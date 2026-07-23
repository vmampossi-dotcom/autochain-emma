<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class BlockchainProofService
{
    public function createProof(string $entityType, array $data): string
    {
        $payload = json_encode([
            'entity' => $entityType,
            'data' => $data,
            'timestamp' => now()->toIso8601String(),
        ], JSON_THROW_ON_ERROR);

        return Hash::make($payload);
    }
}
