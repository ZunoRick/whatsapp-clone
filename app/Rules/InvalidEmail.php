<?php

namespace App\Rules;

use App\Models\Contact;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class InvalidEmail implements ValidationRule
{
    public $email;
    public function __construct($email = null)
    {
        $this->email = $email;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $contacts = Contact::where('user_id', auth()->id())
                ->whereHas('user', function($q) use ($value){
                    $q->where('email', $value)
                    ->when($this->email, function($q){
                        $q->where('email', '!=', $this->email);
                    });
                })
                ->get();
        if ($contacts->count() > 0) {
            $fail('El email ya está en la lista de contactos.');
        }
    }
}
