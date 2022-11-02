<?php

namespace Tests\Feature;

use App\Http\Controllers\BookController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

     // test all books endpoint
    public function test_get_books()
    {
        $response = $this->get('/api/v1/books');

        $response->assertStatus(200);
    }

    
    // test external books endpoint
    public function test_get_external_books()
    {
        $response = $this->get('/api/external-books/{name}');

        $response->assertStatus(200);
    }

    
    

}
