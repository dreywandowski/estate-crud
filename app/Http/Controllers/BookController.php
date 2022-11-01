<?php

namespace App\Http\Controllers;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\BookStoreRequest;
 


class BookController extends Controller
{
    // database call and data extraction
    private function dbCall($from, $to){
        $submissions =  DB::table('kgmpopcorn_posts')->
        join('kgmpopcorn_postmeta' , 'kgmpopcorn_posts.ID' , '=','kgmpopcorn_postmeta.post_id')
        ->orderBy('post_date', 'desc')->
        select ('kgmpopcorn_posts.post_date','kgmpopcorn_posts.ID', 
        'kgmpopcorn_posts.post_type','kgmpopcorn_postmeta.meta_id',
         'kgmpopcorn_postmeta.meta_value', 'kgmpopcorn_postmeta.meta_key') ->
           where('kgmpopcorn_postmeta.meta_key' , '=', 'sb_elem_cfd')
             ->whereBetween('post_date', [$from, $to])
             ->simplePaginate(30)
             ->toArray();


     $final_results = array();
     foreach ($submissions['data'] as $sub) {
                 $meta = $sub->meta_value;
                 $date = $sub->post_date;
                 $metad = $sub->meta_id;
                 $id = $sub->ID;
 
         // make the results to an array
         $fin = explode('}', $meta);
         $form_results = array();
 
 // extract form submission values that we need
 $form_result[0] = substr($fin[0], strpos($fin[0], "value") + 12);   
 $form_result[1] = substr($fin[1], strpos($fin[1], "value") +12); 
 $form_result[2] = substr($fin[2], strpos($fin[2], "value") +13); 
 $form_result[3] = substr($fin[3], strpos($fin[3], "value") +12); 
 $form_result[4] = substr($fin[4], strpos($fin[4], "value") +13);  
 
 // remove semi-colons
 $form_results['Name'] = str_replace(';', '', $form_result[0]);
 $form_results['Email'] = str_replace(';', '', $form_result[1]);
 $form_results['Contact'] = str_replace(';', '', $form_result[2]);
 $form_results['Message'] = str_replace(';', '', $form_result[3]);
 $form_results['Extra'] = str_replace(';', '', $form_result[4]);
 $form_results['Date of submission'] = $date;
 
 // send sanitized results to a final array 
 //$final_results[] = array_slice($form_results, 0,6,true);
 $final_results[] = $form_results;
 
 }
 
 $prev_indi = $submissions['prev_page_url'];
 $next_indi = $submissions['next_page_url'];
 
 if(isset($submissions['prev_page_url']) && ($submissions['prev_page_url'] !='')){
 $prev = "$prev_indi&from=$from&to=$to"; 
 }
 else $prev = '';
 
 if(isset($submissions['next_page_url']) && ($submissions['next_page_url'] !='')){
     $next = "$next_indi&from=$from&to=$to"; 
     }
     else $next = '';

     $data = ['data' => $final_results, 'prev' => $prev, 'next' => $next];

     return $data;
    
    }



// create books
public function createBook(BookStoreRequest $request){
    $validated = $request->validated();

    try{
    $book = new Book;
    $book->name = $validated['name'];
    $book->isbn = $validated['isbn'];
    $book->authors = $validated['authors'];
    $book->country = $validated['country'];
    $book->number_of_pages = $validated['number_of_pages'];
    $book->publisher = $validated['publisher'];
    $book->release_date = $validated['release_date'];
    $book->save();

    $authors[] = $book->authors;
    $pages = intval($book->number_of_pages);
   

    $book_info = array('name' => $book->name, 'isbn' => $book->isbn, 
    'authors' => $authors , 'number_of_pages' => $pages 
    ,'publisher' => $book->publisher,'country' => $book->country ,
    'release_date' => $book->release_date);

    $data['book'] = $book_info;

    }
    catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e], 400);
    }

    return response()->json(['status' => 'success', 'data' => $data], 201);
}


// get books
public function getBooks(){
    $books = Book::all()->toArray();
    $resp_data = array();

    foreach($books as $book){
        $resp_data[] = array('id' => $book['id'],'name' => $book['name'], 'isbn' => $book['isbn'], 
        'authors' => $book['authors'], 'number_of_pages' => $book['number_of_pages']
        ,'publisher' => $book['publisher'],'country' => $book['country'],
        'release_date' => $book['release_date']);  
        
    }

    return response()->json(['status' => 'success',
             'data' => $resp_data], 200)->header('Content-Type', 'application/json');

    
}

// update book
public function updateBook(){
    $books = Book::all();
    print_r($books);
}

// delete book
public function deleteBook($id){
try{
    $book = Book::where('id', $id)->get()->delete();
   // $user->posts->pluck('id')
}
    catch (\Illuminate\Database\QueryException $e) {
        return response()->json(['message' => $e], 400);
    }
    echo "hii==$book";
}


// external books API
public function getExternalBooks($name)
{
    $response = Http::withHeaders([ 
        'Accept'=> 'application/json'
    ]) 
    ->get('https://www.anapioficeandfire.com/api/books?name='.$name); 

$resp = json_decode($response->body(), true);
$resp_fin = array();


// build response object
if(!empty($resp)){
foreach($resp as $res){
    $resp_fin[] = array('name' => $res['name'], 'isbn' => $res['isbn'], 
    'authors' => $res['authors'], 'number_of_pages' => $res['numberOfPages']
    ,'publisher' => $res['publisher'],'country' => $res['country'],
    'release_date' => $res['released']);   
}

return response()->json(['status' => 'success', 'data' => $resp_fin], 200)->header('Content-Type', 'application/json');

}

else{
    $resp_fin['status'] = "not found";
    $resp_fin['data']  = array();

  
    return response()->json([ $resp_fin], 404)->header('Content-Type', 'application/json');

}

}


}