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

     // test get all local books endpoint -- 200 
    public function test_get_books()
    {
        $response = $this->get('/api/v1/books');

        $response->assertOk();
    }

     // test get a local book endpoint -- 200 
     public function test_get_book_success()
     {
         $response = $this->get('/api/v1/books/17');
 
         $response->assertOk();
     }
 
     // test get a local book endpoint -- 404 
     public function test_get_book_fail()
     {
         $response = $this->get('/api/v1/books/1');
 
         $response->assertStatus(404);
     }


    // test external book endpoint -- 200
    public function test_get_external_books_success()
    {
        $response = $this->get('/api/external-books/'.'The Rogue Prince');

        $response->assertOk();
    }


    // test external books endpoint -- 404
    public function test_get_external_books_fail()
    {
        $response = $this->get('/api/external-books/'.'Harry Potter');

        $response->assertStatus(404);
    }


      // test create book endpoint -- 201 
      public function test_create_book_success_endpoint()
      {
          $response = $this->post('/api/v1/books', ['name' => 'Chike and the River',
                                                        'isbn' => '3809398303-28393',
                                                        'authors' => 'Taylor Oatwell',
                                                        'number_of_pages' => 44,
                                                        'country' => 'Kenya',
                                                         'publisher' => 'Macmilian',
                                                         'release_date' => '2021-09-11'
                                                             ]);
  
  
        $response->assertCreated();
      }

       // test create book endpoint -- failed due to invalid/incomplete params -- 302 
       public function test_create_book_failed_endpoint_failed()
       {
           $response = $this->post('/api/v1/books', ['name' => 'Chike and the River',
                                                         'isbn' => '3809398303-28393',
                                                         'authors' => 'Taylor Oatwell',
                                                         'number_of_pages' => '44',
                                                         'country' => 'Kenya',
                                                          'publisher' => 'Macmilian'
                                                          
                                                              ]);
   
   
         $response->assertStatus(302);
       }
 

        // test update book -- 200
        public function test_update_book_sucess()
        {
            $response = $this->patch('/api/v1/books/17', ['name' => 'Without a Silver Spoon',
                                                         'authors' => 'Ure Chokwe',
                                                          'number_of_pages' => 424,
                                                          'country' => 'Morocco',
                                                           'publisher' => 'Ore Olunyo'
                                                               ]);
    
    
          $response->assertOk();
        }
    
        // test update book -- 404
        public function test_update_book_fail()
        {
            $response = $this->patch('/api/v1/books/1', ['name' => 'Without a Silver Spoon',
                                                         'authors' => 'Ure Chokwe',
                                                          'number_of_pages' => 424,
                                                          'country' => 'Morocco',
                                                           'publisher' => 'Ore Olunyo'
                                                               ]);
    
    
          $response->assertStatus(404);
        }
    
         // test delete book -- 202
         public function test_delete_book_success()
         {
             $response = $this->delete('/api/v1/books/19');
     
     
           $response->assertStatus(202);
         }

         // test delete book -- 404
         public function test_delete_book_fail()
         {
             $response = $this->delete('/api/v1/books/15');
     
     
           $response->assertStatus(404);
         }

}
