<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.max' => 'Поле "Имя" не должно превышать 255 символов.',
            'email.required' => 'Поле "Email" обязательно для заполнения.',
            'email.email' => 'Поле "Email" должно содержать корректный email адрес.',
            'email.max' => 'Поле "Email" не должно превышать 255 символов.',
            'message.required' => 'Поле "Сообщение" обязательно для заполнения.',
            'message.max' => 'Поле "Сообщение" не должно превышать 2000 символов.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create new contact record
        Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        return redirect()->back()
            ->with('success', 'Спасибо за ваше сообщение! Мы свяжемся с вами в ближайшее время.');
    }
}
