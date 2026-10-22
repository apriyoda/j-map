<?php

namespace Tests\Feature;

use App\Models\Place;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlaceSubmissionAndProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_submitting_place_gets_pending_status_and_redirects_to_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $response = $this->actingAs($member)->post('/places', [
            'name' => 'Akihabara Maid Cafe',
            'category' => 'Kafe Bertema',
            'description' => 'Kafe unik bernuansa maid cosplay di Jakarta.',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success', 'Lokasi Anda berhasil diajukan dan sedang ditinjau oleh Admin. Terima kasih atas partisipasi Anda!');

        $this->assertDatabaseHas('places', [
            'name' => 'Akihabara Maid Cafe',
            'status' => 'pending',
            'user_id' => $member->id,
        ]);

        // Visit dashboard and verify banner text appears
        $dashboardResponse = $this->actingAs($member)->get(route('dashboard'));
        $dashboardResponse->assertStatus(200);
    }

    public function test_admin_submitting_place_gets_approved_status_and_redirects_to_places_index(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post('/places', [
            'name' => 'Tokyo Station Ramen',
            'category' => 'Restoran Ramen',
            'description' => 'Ramen halal autentik khas Tokyo.',
            'latitude' => -6.1751,
            'longitude' => 106.8650,
        ]);

        $response->assertRedirect(route('places.index'));
        $response->assertSessionHas('success', 'Tempat berhasil ditambahkan.');

        $this->assertDatabaseHas('places', [
            'name' => 'Tokyo Station Ramen',
            'status' => 'approved',
            'user_id' => $admin->id,
        ]);
    }

    public function test_member_cannot_access_places_index_and_is_redirected_to_dashboard(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $response = $this->actingAs($member)->get('/places');
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');
    }

    public function test_member_cannot_access_edit_update_or_delete_place(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $place = Place::create([
            'name' => 'Sample Place',
            'category' => 'Spot Foto',
            'description' => 'Deskripsi',
            'latitude' => -6.2000,
            'longitude' => 106.8166,
            'status' => 'approved',
        ]);

        // GET edit
        $editResponse = $this->actingAs($member)->get("/places/{$place->id}/edit");
        $editResponse->assertRedirect(route('dashboard'));
        $editResponse->assertSessionHas('error');

        // PUT update
        $updateResponse = $this->actingAs($member)->put("/places/{$place->id}", [
            'name' => 'Modified Name',
            'category' => 'Spot Foto',
            'latitude' => -6.2000,
            'longitude' => 106.8166,
        ]);
        $updateResponse->assertRedirect(route('dashboard'));
        $updateResponse->assertSessionHas('error');

        // DELETE destroy
        $deleteResponse = $this->actingAs($member)->delete("/places/{$place->id}");
        $deleteResponse->assertRedirect(route('dashboard'));
        $deleteResponse->assertSessionHas('error');

        // Verify place still exists unchanged
        $this->assertDatabaseHas('places', [
            'id' => $place->id,
            'name' => 'Sample Place',
        ]);
    }

    public function test_admin_can_access_places_index_and_edit_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $place = Place::create([
            'name' => 'Admin Managed Place',
            'category' => 'Toko Anime',
            'description' => 'Toko figur anime terlengkap.',
            'latitude' => -6.1900,
            'longitude' => 106.8200,
            'status' => 'approved',
        ]);

        $indexResponse = $this->actingAs($admin)->get('/places');
        $indexResponse->assertStatus(200);

        $editResponse = $this->actingAs($admin)->get("/places/{$place->id}/edit");
        $editResponse->assertStatus(200);
    }
}
