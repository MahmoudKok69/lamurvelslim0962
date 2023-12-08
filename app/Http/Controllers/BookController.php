<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    use GeneralTrait;

    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'url' => 'required'
        ]);
        if($validator->fails()) {
            return  $this->returnValidationError($validator->errors()->first(),400);
        }
        
        $book = Book::create([
            'title' => $request->title,
            'url' => $request->url
        ]);

        return $this->returnSuccessData($book, 'book created successfully', 200);
    }
    public function update(Request $request,$id){
        $book = Book::find($id);
        if($book){
            $book->title = isset($request->title) ? $request->title : $book->title;
            $book->url = isset($request->url) ? $request->url : $book->url;
            $book->save();
            return $this->returnSuccessData($book, 'book updated successfully', 200);
        }
        return $this->returnError('book not found', 404);
    }
    public function delete(Request $request,$id){
        $book = Book::find($id);
        if($book){
            $book->delete();
            return $this->returnSuccessData($book, 'book deleted successfully', 200);
        }
        return $this->returnError('book not found', 404);
    }
    public function show($id){
        $book = Book::find($id);
        if($book){
            return $this->returnSuccessData($book, '', 200);
        }
        return $this->returnError('book not found', 404);
    }
    public function showAll(){
        $books = Book::all();
        return $this->returnSuccessData($books, '', 200);
    }
}
