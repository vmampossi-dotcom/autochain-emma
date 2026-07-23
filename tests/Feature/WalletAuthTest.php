<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_wallet_login_can_create_a_user_from_metamask_address(): void
    {
        $response = $this->withSession(['wallet_nonce' => 'demo-nonce'])
            ->post('/wallet/login', [
                'address' => '0x1234567890abcdef1234567890abcdef12345678',
                'signature' => '0xabc',
                'nonce' => 'demo-nonce',
            ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'wallet_address' => '0x1234567890abcdef1234567890abcdef12345678',
        ]);
        $this->assertAuthenticated();
    }
}
