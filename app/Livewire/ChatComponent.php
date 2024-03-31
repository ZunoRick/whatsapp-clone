<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\Contact;
use App\Models\Message;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class ChatComponent extends Component
{
    public $search;
    public $contactChat, $chat;
    public $bodyMessage;

    //Listeners
    public function getListeners()
    {
        $user_id = auth()->user()->id;

        return [
            "echo-notification:App.Models.User.{$user_id},notification" => 'render'
        ];
    }

    //Propiedad Computada
    public function getContactsProperty(){
        return Contact::where('user_id', auth()->id())
            ->when($this->search, function($q){
                $q->where(function($q){
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', function($q){
                            $q->where('email', 'like', '%'.$this->search.'%');
                        });
                });
            })
            ->get() ?? [];
    }

    public function getMessagesProperty(){
        return $this->chat ? Message::where('chat_id', $this->chat->id)->get() : [];
    }

    public function getChatsProperty(){
        return auth()->user()->chats()->get()->sortByDesc('last_message_at');
    }

    public function getUsersNotificationsProperty(){
        return $this->chat ? $this->chat->users->where('id', '!=', auth()->id()) : [];
    }

    //Ciclo de vida
    public function updateBodyMessage($value){
        dd('Mensaje'.$value);
        if($value){
            Notification::send($this->users_notifications, new \App\Notifications\UserTyping($this->chat->id));
        }
    }

    public function open_chat_contact(Contact $contact){
        $chat = auth()->user()->chats()
            ->whereHas('users', function($q) use ($contact){
                $q->where('user_id', $contact->contact_id);
            })
            ->has('users', 2)
            ->first();

        if ($chat) {
            $this->chat = $chat;
            $this->reset('contactChat', 'bodyMessage', 'search');
        }else{
            $this->contactChat = $contact;
            $this->reset('chat', 'bodyMessage', 'search');
        }
    }

    public function open_chat(Chat $chat){
        $this->chat = $chat;
        $this->reset('contactChat', 'bodyMessage');
    }

    public function sendMessage(){
        $this->validate([
            'bodyMessage' => 'required'
        ]);

        if (!$this->chat) {
            $this->chat = Chat::create();
            $this->chat->users()->attach([auth()->user()->id, $this->contactChat->contact_id]);
        }

        $this->chat->messages()->create([
            'body' => $this->bodyMessage,
            'user_id' => auth()->user()->id
        ]);

        Notification::send($this->users_notifications, new \App\Notifications\NewMessage());

        $this->reset('bodyMessage', 'contactChat');
    }

    public function render()
    {
        if($this->chat){
            $this->chat->messages()->where('user_id', '!=', auth()->id())
                ->where('is_read', false)->update([
                    'is_read' => true
                ]);
            $this->dispatch('scrollIntoView');
        }

        
        return view('livewire.chat-component')->layout('layouts.chat');
    }
}
