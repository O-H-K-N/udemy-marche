<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'information' => ['required', 'string', 'max:1000'],
            'is_selling' => ['required'],
            // 拡張子、byteを指定
            'image' => 'image|mimes:jpg, jpeg, png|max:2048',
        ];
    }

    // エラーメッセージの指定
    public function messages()
    {
        return [
            'image' => '指定されたファイルが画像ではありません',
            'mimes' => '指定された拡張子（jpg/jpeg/png）ではりません',
            'max' => 'ファイルサイズは2MB以内にしてください',
        ];
    }
}
