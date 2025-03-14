<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\AuthorData;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $authors = Author::with('authorData', 'getPosts')->get();
        return view('author.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $author_id = $request->get('author_id');
        return view('author.create', compact('author_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'author_club' => ['required', 'max:50'],
                'author_nickname' => ['required', 'max:50'],
                'author_age' => ['required', 'string', 'max:10'],
                'author_hobby' => ['required', 'min:3', 'max:50'],
                'author_email' => ['nullable', 'email', 'unique:author_data,email'],
                // 'author_id' => ['required', 'integer','exists:authors,id'],
                'author_id' => ['required', 'integer', 'unique:author_data,author_id'],
                'author_photo' => ['required','image', 'mimes:jpeg,png,jpg,gif', 'max:5048']
            ],
            [
                'author_club.required'    => 'The author club is required.',
                'author_club.max'         => 'The author club must not exceed 50 characters.',

                'author_nickname.required' => 'The author nickname is required.',
                'author_nickname.max'     => 'The author nickname must not exceed 50 characters.',

                'author_age.required'     => 'The author age is required.',
                'author_age.string'       => 'The author age must be a string.',
                'author_age.max'          => 'The author age must not exceed 10 characters.',

                'author_hobby.required'   => 'The author hobby is required.',
                'author_hobby.min'        => 'The author hobby must be at least 3 characters.',
                'author_hobby.max'        => 'The author hobby must not exceed 50 characters.',

                'author_email.email'      => 'Please enter a valid email address.',
                'author_email.unique'     => 'The email address has already been taken.',

                'author_photo.mimes'      => 'The author photo must be a file of type: jpeg, png, jpg, gif.',
                'author_photo.max'        => 'The author photo must not be larger than 5MB.',
            ]
        );

        if ($validator->fails()) {
            $request->flash();
            return redirect()->back()->withErrors($validator);
        }

        $authorData = new AuthorData();
        $file = $request->file('author_photo');
        $ext = $file->getClientOriginalExtension();
        $name = rand(1000000, 9999999) . '_' . rand(1000000, 9999999);
        $name = $name . '.' . $ext;
        $destinationPath = public_path() . '/authorPhoto/';
        $file->move($destinationPath, $name);
        $authorData->photo = asset('/authorPhoto/' . $name);



        // $authorData = new AuthorData();
        $authorData->club = $request->author_club;
        $authorData->nickname = $request->author_nickname;
        $authorData->age = $request->author_age;
        $authorData->hobby = $request->author_hobby;
        $authorData->email = $request->author_email;
        $authorData->author_id = $request->author_id;
        $authorData->save();

        return redirect()->route('author.index')->with('info_message', 'You have completed your profile.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $authorData = AuthorData::where('author_id', $author->id)->first();
        if (!$authorData) {
            $authorData = null;
        }
        return view('author.show', compact('author', 'authorData'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        $authorData = AuthorData::where('author_id', $author->id)->firstOrFail();
        return view('author.edit', compact('author', 'authorData'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AuthorData $authorData)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'author_club' => ['required', 'max:50'],
                'author_nickname' => ['required', 'max:50'],
                'author_age' => ['required', 'string', 'max:10'],
                'author_hobby' => ['required', 'min:3', 'max:50'],
                'author_email' => ['nullable', 'email', Rule::unique('author_data', 'email')->ignore($authorData->id)],
                //"unique:author_data,email,{$authorData->id}"],

            ],
            [
                'author_club.required'    => 'The author club is required.',
                'author_club.max'         => 'The author club must not exceed 50 characters.',

                'author_nickname.required' => 'The author nickname is required.',
                'author_nickname.max'     => 'The author nickname must not exceed 50 characters.',

                'author_age.required'     => 'The author age is required.',
                'author_age.string'       => 'The author age must be a string.',
                'author_age.max'          => 'The author age must not exceed 10 characters.',

                'author_hobby.required'   => 'The author hobby is required.',
                'author_hobby.min'        => 'The author hobby must be at least 3 characters.',
                'author_hobby.max'        => 'The author hobby must not exceed 50 characters.',

                'author_email.email'      => 'Please enter a valid email address.',
                'author_email.unique'     => 'The email address has already been taken.',

                'author_photo.mimes'      => 'The author photo must be a file of type: jpeg, png, jpg, gif.',
                'author_photo.max'        => 'The author photo must not be larger than 5MB.',
            ]
        );

        if ($validator->fails()) {
            $request->flash();
            return redirect()->back()->withErrors($validator)->withInput();;
        }

        // Обновление данных
        $authorData->update([
            'club' => $request->author_club,
            'nickname' => $request->author_nickname,
            'age' => $request->author_age,
            'hobby' => $request->author_hobby,
            'email' => $request->author_email,
        ]);

        return redirect()->route('author.show', $authorData->author_id)->with('success_message', 'You have successfully updated your profile !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuthorData $authorData)
    {
        $author_id = $authorData->author_id;
        $authorData->delete();

        return redirect()->route('author.show', $author_id)->with('danger_message', 'Your profile has been deleted !');
    }

    public function pdf(Author $author)
    {
        $authorData = AuthorData::where('author_id', $author->id)->first();
        $pdf = PDF::loadView('author.pdf', compact('author', 'authorData'));
        return $pdf->download($author->name . '-' . $author->id . '.pdf');
    }
}
