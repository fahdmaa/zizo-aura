<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_renders_successfully(): void
    {
        $response = $this->get('/admin/login');
        $response->assertOk();
        $response->assertSee('zizo aura');
        $response->assertSee('Mot de passe administrateur');
    }

    public function test_admin_login_with_valid_password_authenticates_and_redirects(): void
    {
        $response = $this->post('/admin/login', [
            'password' => 'zizoaura2025!',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $response->assertSessionHas('admin_authenticated', true);

        // Follow redirect to dashboard
        $dashResponse = $this->withSession(['admin_authenticated' => true])->get('/admin');
        $dashResponse->assertOk();
    }

    public function test_admin_login_with_invalid_password_fails(): void
    {
        $response = $this->from('/admin/login')->post('/admin/login', [
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/admin/login');
        $response->assertSessionHasErrors(['password']);
        $response->assertSessionMissing('admin_authenticated');
    }

    public function test_admin_logout_clears_session(): void
    {
        $response = $this->withSession(['admin_authenticated' => true])
            ->post('/admin/logout');

        $response->assertRedirect(route('admin.login'));
        $response->assertSessionMissing('admin_authenticated');
    }
}
