<?php

namespace Tests\Unit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ApiLoginTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function setUp(): void
{
    parent::setUp();

    // seed the database
    $this->artisan('db:seed');
    // generate passport keys
    $this->artisan('passport2:install');
}
    public function testLoginSucces()
    {

        $response = $this->postJson('/api/login', ['email' => 'student@example.com', 'password' => 'password']);

        $response
            ->assertStatus(200)
            ->assertJsonPath('data.first_name', 'Bofin')
            ->assertJsonPath('data.last_name', 'Bot');
    }
    public function testLoginFailed()
    {

        $response = $this->postJson('/api/login', ['email' => 'student@example.com', 'password' => 'Password']);

        $response
            ->assertStatus(404)
            ->assertJsonPath('data.error', 'De combinatie van email en wachtwoord is niet bij ons bekend.');
    }

    public function testCategoriesByStudentId(){
        $response = $this->postJson('/api/login', ['email' => 'student@example.com', 'password' => 'password']);

        $authToken = $response['data']['token'];
        $studentId = $response['data']['id'];
        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',
        ])
            ->get('/api/categories/student/' . $studentId);
        $response->assertStatus(200);
    }
    public function testMultipleTemplatesById(){
        $response = $this->postJson('/api/login', ['email' => 'student@example.com', 'password' => 'password']);

        $authToken = $response['data']['token'];
        $studentId = $response['data']['id'];
        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',
        ])->get('/api/templates/student/' . $studentId);
        $array = collect();
        foreach($response["data"] as $item){
            $array->add($item["id"]);
        }

        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',
        ])->get('/api/templates/multiple/' . $array->implode(","));
        $response->assertJsonCount(7,"data");
    }
    public function testGetSingleWordlistById(){
        $response = $this->postJson('/api/login', ['email' => 'student@example.com', 'password' => 'password']);

        $authToken = $response['data']['token'];
        $studentId = $response['data']['id'];
        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',
        ])->get('/api/templates/student/' . $studentId);

        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',
        ])->get('/api/wordlist/' . $response["data"][0]["wordlist_id"]);
        $response->assertJsonCount(2,"data");
    }

    public function testPostOfWorksheetSuccess(){
        $response = $this->postJson('/api/login', ['email' => 'student@example.com', 'password' => 'password']);


        $authToken = $response['data']['token'];
        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',
        ])->get('/api/templates');

        $json = json_decode('{"answerType":"MULTIPLE_CHOICE","endTime":"Jun 20, 2021 10:02:29 PM","questionAmount":5,"questionType":"ARITHMETIC_SUM_TEXT","questions":[{"operator":"PLUS","termOne":4,"termTwo":4,"answer":{"possibleAnswers":["7","8","9","10"],"completed":true,"correctAnswer":{"amount":8},"selectedAnswer":{"name":"8"},"success":true},"begin":1624226540808,"end":1624226542806},{"operator":"PLUS","termOne":3,"termTwo":1,"answer":{"possibleAnswers":["1","2","3","4"],"completed":true,"correctAnswer":{"amount":4},"selectedAnswer":{"name":"1"},"success":false},"begin":1624226544179,"end":1624226544903},{"operator":"PLUS","termOne":0,"termTwo":0,"answer":{"possibleAnswers":["-1","0","1","2"],"completed":true,"correctAnswer":{"amount":0},"selectedAnswer":{"name":"-1"},"success":false},"begin":1624226545877,"end":1624226546383},{"operator":"PLUS","termOne":5,"termTwo":0,"answer":{"possibleAnswers":["4","5","6","7"],"completed":true,"correctAnswer":{"amount":5},"selectedAnswer":{"name":"4"},"success":false},"begin":1624226547037,"end":1624226547416},{"operator":"PLUS","termOne":2,"termTwo":3,"answer":{"possibleAnswers":["5","6","7","8"],"completed":true,"correctAnswer":{"amount":5},"selectedAnswer":{"name":"5"},"success":true},"begin":1624226548036,"end":1624226548419}],"startTime":"Jun 20, 2021 10:02:19 PM","studentId":"60c9b493ef000000980077fc","successAmount":2,"templateId":"'.$response["data"][5]["id"] .'"}',true);
        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',
        ])->postJson('/api/worksheets', $json );
        $response->assertStatus(200);
    }
    public function testPostOfWorksheetWrongTemplateId(){
        $response = $this->postJson('/api/login', ['email' => 'student@example.com', 'password' => 'password']);


        $authToken = $response['data']['token'];
        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',
        ])->get('/api/templates');

        $json = json_decode('{"answerType":"MULTIPLE_CHOICE","endTime":"Jun 20, 2021 10:02:29 PM","questionAmount":5,"questionType":"ARITHMETIC_SUM_TEXT","questions":[{"operator":"PLUS","termOne":4,"termTwo":4,"answer":{"possibleAnswers":["7","8","9","10"],"completed":true,"correctAnswer":{"amount":8},"selectedAnswer":{"name":"8"},"success":true},"begin":1624226540808,"end":1624226542806},{"operator":"PLUS","termOne":3,"termTwo":1,"answer":{"possibleAnswers":["1","2","3","4"],"completed":true,"correctAnswer":{"amount":4},"selectedAnswer":{"name":"1"},"success":false},"begin":1624226544179,"end":1624226544903},{"operator":"PLUS","termOne":0,"termTwo":0,"answer":{"possibleAnswers":["-1","0","1","2"],"completed":true,"correctAnswer":{"amount":0},"selectedAnswer":{"name":"-1"},"success":false},"begin":1624226545877,"end":1624226546383},{"operator":"PLUS","termOne":5,"termTwo":0,"answer":{"possibleAnswers":["4","5","6","7"],"completed":true,"correctAnswer":{"amount":5},"selectedAnswer":{"name":"4"},"success":false},"begin":1624226547037,"end":1624226547416},{"operator":"PLUS","termOne":2,"termTwo":3,"answer":{"possibleAnswers":["5","6","7","8"],"completed":true,"correctAnswer":{"amount":5},"selectedAnswer":{"name":"5"},"success":true},"begin":1624226548036,"end":1624226548419}],"startTime":"Jun 20, 2021 10:02:19 PM","studentId":"60c9b493ef000000980077fc","successAmount":2,"templateId":"'.$response["data"][1]["id"] .'"}',true);
        $response = $this->withHeaders([
            'Authorization' => $authToken,
            'Accept' => 'application/json',
        ])->postJson('/api/worksheets', $json );
        $response->assertStatus(500);
    }

}
