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

    // update utility
    private function updateUtil(Request $request, $id){
        $name = $request->name ?? '';
        $isbn = $request->isbn ?? '';
        $authors = $request->authors ?? '';
        $country = $request->country ?? '';
        $number_of_pages = $request->number_of_pages ?? '';
        $publisher = $request->publisher ?? '';
        $release_date = $request->release_date ?? '';
        
        // condense request body to an array
        $data = ["name" => $name,
        "isbn" => $isbn,
        "authors" => $authors,
        "country" => $country,
        "number_of_pages" => $number_of_pages,
        "publisher" => $publisher,
        "release_date" => $release_date];

        $fin_data = array();
    
        // loop tru the array and remove update items which are not set
        foreach($data as $key => $val){
            if($val === '')continue;
            $fin_data[$key] = $val;
        }

        return $fin_data;
    }

    
// get data util
   private function getData($id){
    $book = Book::where('id', $id)->first();
    $resp_data = array();

   if(!empty($book)){
    $resp_data['id'] = $book['id'];
    $resp_data['name'] = $book['name'];
    $resp_data['isbn'] = $book['isbn'];
    $resp_data['authors'] = array($book['authors']);
    $resp_data['number_of_pages'] = $book['number_of_pages'];
    $resp_data['publisher'] = $book['publisher'];
    $resp_data['country'] = $book['country'];
    $resp_data['release_date'] = $book['release_date'];
   }
   
    return $resp_data;
}

// external books API util
private function apiCall($name){
    $response = Http::withHeaders([ 
        'Accept'=> 'application/json'
    ]) 
    ->get('https://www.anapioficeandfire.com/api/books?name='.$name); 

$result = json_decode($response->body(), true);

return $result;
}


public function getExternalBooks($name)
{
$resp = self::apiCall($name);
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
        'authors' => array($book['authors']), 'number_of_pages' => $book['number_of_pages']
        ,'publisher' => $book['publisher'],'country' => $book['country'],
        'release_date' => $book['release_date']);  
        
    }

    return response()->json(['status' => 'success',
             'data' => $resp_data], 200)->header('Content-Type', 'application/json');

    
}

// get book
public function getBook($id){
    $book = self::getData($id);

    if(!empty($book)){
    return response()->json(['status' => 'success',
             'data' => $book], 200);
}

    else{
        return response()->json(['status' => 'failed', 
   'message' => 'The book with id '.$id.' was not found',
    'data' => array()], 404);
    }
    
}

// update book
public function updateBook(Request $request, $id){
    $fin_data = self::updateUtil($request, $id);

    // perform the update
       try{
            $bookUpdated = Book::where("id", $id)->update(
                $fin_data
            );
            if($bookUpdated == 0){
                return response()->json(['status' => 'fail', 
                'message' => 'Unable to update book, because no book with id '.$id.' was found',
                 'data' => array()], 404);
                 }

            $data = self::getData($id);
            $name = $data['name'];
        }
        catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e], 400);
        }

        return response()->json(['status' => 'success', 
   'message' => 'The book '.$name.' was updated successfully',
    'data' => $data], 200);
    }



    // delete book
public function deleteBook($id){
    $book = Book::where('id', $id)->first();
    
    if(!empty($book)){
        $name =  $book->name;
        try{
        $book->delete();
        }
        catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => $e], 400);
        }

        return response()->json(['status' => 'success', 
   'message' => 'The book '.$name.' was deleted successfully',
    'data' => array()], 202);
    }


    else{
        return response()->json(['status' => 'failed', 
   'message' => 'The book with id '.$id.' was not found',
    'data' => array()], 404);
    }
   
 
}




}