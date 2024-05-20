<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class CreateAnnouncement extends Component
{
    public $title;
    public $body;
    public $price;
    public $category;

    
    protected $rules = [
        'title' =>'required|min:4',
        'body' =>'required|min:4',
        'category' =>'required',
        'price' =>'required|numeric',
    ];

    protected $messages = [
        'required'=>'Il campo :attribute è richiesto',
        'min'=> 'Il campo :attribute deve contenere almeno 4 caratteri',
        'numeric'=> 'Il campo :attribute dev\'essere un numero',
    ];

    protected $validationAttributes = [
        'title' => 'titolo',
        'body' => 'descrizione',
        'category' => 'categoria',
        'price' => 'prezzo',
    ];
    

    public function store()
    {
        $this->validate();
        $category = Category::find($this->category);
        
        $announcement = $category->announcements()->create([
            'title'=>$this->title,
            'body'=>$this->body,
            'price'=>$this->price,
        ]);
        Auth::user()->announcements()->save($announcement);
        
        session()->flash('message', 'Annuncio inserito con successo');
        $this->cleanForm();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function cleanForm()
    {
        $this->title = '';
        $this->body = '';
        $this->price = '';
        $this->category = '';
    }

    public function render()
    {
        return view('livewire.create-announcement');
    }
}
