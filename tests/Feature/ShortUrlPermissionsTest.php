<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\ShortUrl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlPermissionsTest extends TestCase
{
    use RefreshDatabase;

    protected function createUser(string $role, ?Company $company = null): User
    {
        return User::factory()->create([
            'role'       => $role,
            'company_id' => $company?->id,
        ]);
    }

    /** @test */
    public function admin_and_member_cannot_create_short_urls()
    {
        $company = Company::factory()->create();
        $admin   = $this->createUser(User::ROLE_ADMIN, $company);
        $member  = $this->createUser(User::ROLE_MEMBER, $company);

        $this->actingAs($admin)
            ->post('/short-urls', ['original_url' => 'https://example.com'])
            ->assertStatus(403);

        $this->actingAs($member)
            ->post('/short-urls', ['original_url' => 'https://example.com'])
            ->assertStatus(403);
    }

    /** @test */
    public function superadmin_cannot_create_short_urls()
    {
        $super = $this->createUser(User::ROLE_SUPER_ADMIN);

        $this->actingAs($super)
            ->post('/short-urls', ['original_url' => 'https://example.com'])
            ->assertStatus(403);
    }

    /** @test */
    public function admin_sees_only_short_urls_not_created_in_own_company()
    {
        $companyA = Company::factory()->create();
        $companyB = Company::factory()->create();

        $adminA = $this->createUser(User::ROLE_ADMIN, $companyA);
        $creatorA = $this->createUser(User::ROLE_SALES, $companyA);
        $creatorB = $this->createUser(User::ROLE_SALES, $companyB);

        $own = ShortUrl::factory()->create([
            'company_id' => $companyA->id,
            'user_id'    => $creatorA->id,
        ]);

        $other = ShortUrl::factory()->create([
            'company_id' => $companyB->id,
            'user_id'    => $creatorB->id,
        ]);

        $this->actingAs($adminA)
            ->get('/short-urls')
            ->assertStatus(200)
            ->assertSee($other->code)
            ->assertDontSee($own->code);
    }

    /** @test */
    public function member_sees_only_short_urls_not_created_by_themselves()
    {
        $company = Company::factory()->create();
        $member  = $this->createUser(User::ROLE_MEMBER, $company);
        $sales   = $this->createUser(User::ROLE_SALES, $company);

        $own = ShortUrl::factory()->create([
            'company_id' => $company->id,
            'user_id'    => $member->id,
        ]);

        $other = ShortUrl::factory()->create([
            'company_id' => $company->id,
            'user_id'    => $sales->id,
        ]);

        $this->actingAs($member)
            ->get('/short-urls')
            ->assertStatus(200)
            ->assertSee($other->code)
            ->assertDontSee($own->code);
    }

    /** @test */
    public function short_urls_are_not_publicly_resolvable_and_redirect_after_login()
    {
        $company = Company::factory()->create();
        $sales   = $this->createUser(User::ROLE_SALES, $company);

        $short = ShortUrl::factory()->create([
            'company_id'   => $company->id,
            'user_id'      => $sales->id,
            'original_url' => 'https://laravel.com',
            'code'         => 'abc12345',
        ]);

        // guest -> redirected to login (not public)
        $this->get('/r/'.$short->code)
            ->assertRedirect('/login');

        // authenticated -> redirected to original
        $this->actingAs($sales)
            ->get('/r/'.$short->code)
            ->assertRedirect('https://laravel.com');
    }
}
