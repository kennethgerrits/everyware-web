<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TemplatesApiTest extends TestCase
{
    use  DatabaseMigrations;
    public function setUp(): void
    {
        parent::setUp();

        // seed the database
        $this->artisan('db:seed');
        // generate passport keys
        $this->artisan('passport2:install');
    }
    public function testGetTemplatesForStudentId()
    {

        $response = $this->postJson('/api/login', ['email' => 'student@example.com', 'password' => 'password']);

        $authToken = $response['data']['token'];
        $studentId = $response['data']['id'];
        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',])
       ->get('/api/templates/student/' . $studentId);
      $response->assertJsonCount(7,'data');


    }
    public function testGetTemplatesForStudentIdWithNonExistingId()
    {

        $response = $this->postJson('/api/login', ['email' => 'student@example.com', 'password' => 'password']);

        $authToken = $response['data']['token'];
        $studentId = $response['data']['id'];
        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',])
            ->get('/api/templates/student/' . $studentId."u");
        $response->assertJsonPath('data.error','Het opgegeven ID bestaat niet.');


    }
    public function testAuthorizationFailed()
    {

        $response = $this->postJson('/api/login', ['email' => 'student@example.com', 'password' => 'password']);

        $authToken = $response['data']['token'];
        $studentId = $response['data']['id'];
        $response = $this->withHeaders([
            'Authorization' => $authToken. "1",
            'Accept' => 'application/json',])
            ->get('/api/templates/student/' . $studentId);
        $response->assertJsonPath('message','Unauthenticated.');


    }
}
