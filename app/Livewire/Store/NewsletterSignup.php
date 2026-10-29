<?php

namespace App\Livewire\Store;

use App\Models\NewsletterSubscriber;
use Livewire\Component;

class NewsletterSignup extends Component
{
    public string $email = '';

    public bool $subscribed = false;

    public function subscribe(): void
    {
        $this->validate(['email' => 'required|email']);

        NewsletterSubscriber::firstOrCreate(['email' => $this->email]);

        $this->subscribed = true;
        $this->email = '';
    }

    public function render()
    {
        return view('livewire.store.newsletter-signup');
    }
}
