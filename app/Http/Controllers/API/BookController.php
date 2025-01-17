<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
        try {
            $books = Book::all();
            if ($books->isEmpty()) {
                return response()->json(['message' => 'No books found'], 404);
            }
            return response()->json(['books' => $books], 200);
        }catch(\Exception $e){
            return response()->json(['message' => 'Failed to retrieve books'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $validar = $request->validate([
            'title' =>'required|string|max:50',
            'author' =>'required|string|max:50',
            'editorial' =>'required|string|max:50',
            'genre' =>'required|string|max:30',
            'description' => 'required|string|max:255',
            'img_book' => 'required|file|mimes:jpg,jpeg,png',
            'date_published' =>'required',
        ]);

        $imagePath = $request->file('img_book')->store('','public');

        $book = Book::create([...$validar, 'img_book' => $imagePath]);

        if (!$book) {
            return response()->json(['message' => 'Failed to create book'], 500);
        }

        return response()->json(['message' => 'Book created successfully', 'book' => $book], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id){
        try {
            $book = Book::findOrFail($id);
            return response()->json(['book' => $book], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to retrieve book details'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified `resource` in storage.
     */
    public function update(Request $request, string $id){
        try {
            $book = Book::findOrFail($id);
            if (!$book) {
                return response()->json(['message' => 'Book not found'], 404);
            }
            $validar = $request->validate([
                'title' =>'nullable|string|max:50',
                'author' =>'nullable|string|max:50',
                'editorial' =>'nullable|string|max:50',
                'genre' =>'nullable|string|max:30',
                'description' => 'nullable|string|max:255',
                'img_book' => 'nullable|file|mimes:jpg,jpeg,png',
                'date_published' =>'nullable',
            ]);

            if ($request->hasFile('img_book')) {
                Storage::disk('public')->delete($book->img_book);
                $imagePath = $request->file('img_book')->store('','public');
                $validar['img_book'] = $imagePath;
            }
            $book->update($validar);
            return response()->json(['message' => 'Book updated successfully', 'book' => $book], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update book', 'error' => $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id){
        try{
            $book = Book::findOrFail($id);
            if(!$book){
                return response()->json(['message' => 'Book not found'], 404);
            }
            if ($book->img_book && Storage::disk('public')->exists($book->img_book)) {
                Storage::disk('public')->delete($book->img_book);
            }
            $book->delete();
            return response()->json(['message' => 'Book deleted successfully'], 200);
        }catch (\Exception $e){
            return response()->json(['message' => 'Failed to delete book'], 500);
        }
    }
}
