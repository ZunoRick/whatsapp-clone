<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Validation\Rule;

use App\Rules\InvalidEmail;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = auth()->user()->contacts()->paginate();

        return view('contacts.index', [
            'contacts' => $contacts
        ]);
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'exists:users',
                Rule::notIn([auth()->user()->email]),
                new InvalidEmail
            ]
        ]);

        $user = User::where('email', $request->email)->first();
        
        $contact = Contact::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
            'contact_id' => $user->id,
        ]);

        session()->flash(
            'flash.banner', 'El contacto se ha creado correctamente'
        );
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('contacts.edit', $contact);
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', [
            'contact' => $contact
        ]);
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'exists:users',
                Rule::notIn([auth()->user()->email]),
                new InvalidEmail($contact->user->email)
            ]
        ]);

        $user = User::where('email', $request->email)->first();
        
        $contact->update([
            'name' => $request->name,
            'contact_id' => $user->id
        ]);

        session()->flash(
            'flash.banner',
            'El contacto se actualizó correctamente'
        );
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('contacts.edit', $contact);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        session()->flash(
            'flash.banner',
            'El contacto se eliminó correctamente'
        );
        session()->flash('flash.bannerStyle', 'success');

        return redirect()->route('contacts.index');
    }
}
