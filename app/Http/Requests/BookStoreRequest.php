<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
        'isbn' => 'required|string',
        'authors' => 'required',
        'country' => 'required|string',
        'number_of_pages' => 'required|integer',
        'publisher' => 'required|string',
        'release_date' => 'required|date'
        ];
    }

    // custom error messages
    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'isbn.required' => 'ISBN is required!',
            'authors.required' => 'at least one author is required!',
            'country.required' => 'country is required!',
            'number_of_pages.required' => 'please put in the number of pages!',
            'publisher.required' => 'publisher is required!',
            'release_date.required' => 'release date is required!'
        ];
    }
}
