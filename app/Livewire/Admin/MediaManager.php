<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class MediaManager extends Component
{
    use WithFileUploads;

    public Product $product;
    public $images = [];
    public $newImages = [];
    public $coverImageIndex = 0;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->images = $product->images ?? [];
        
        if (count($this->images) > 0) {
            $this->coverImageIndex = 0;
        }
    }

    public function updatedNewImages()
    {
        $this->validate([
            'newImages.*' => 'image|max:2048',
        ]);
    }

    public function uploadImages()
    {
        $this->validate([
            'newImages.*' => 'required|image|max:2048',
        ]);

        foreach ($this->newImages as $image) {
            $path = $image->store('products', 'public');
            $this->images[] = $path;
        }

        $this->product->update(['images' => $this->images]);
        $this->newImages = [];
        
        session()->flash('message', 'Images uploaded successfully!');
    }

    public function deleteImage($index)
    {
        if (isset($this->images[$index])) {
            Storage::disk('public')->delete($this->images[$index]);
            array_splice($this->images, $index, 1);
            $this->images = array_values($this->images);
            
            if ($this->coverImageIndex >= count($this->images)) {
                $this->coverImageIndex = max(0, count($this->images) - 1);
            }
            
            $this->product->update(['images' => $this->images]);
            session()->flash('message', 'Image deleted successfully!');
        }
    }

    public function setCoverImage($index)
    {
        if (isset($this->images[$index])) {
            $selectedImage = $this->images[$index];
            array_splice($this->images, $index, 1);
            array_unshift($this->images, $selectedImage);
            
            $this->coverImageIndex = 0;
            $this->product->update(['images' => $this->images]);
            
            session()->flash('message', 'Cover image updated successfully!');
        }
    }

    public function render()
    {
        return view('livewire.admin.media-manager')->layout('components.layouts.admin', [
            'header' => 'Media: ' . $this->product->name,
        ]);
    }
}
