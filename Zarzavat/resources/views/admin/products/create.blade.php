@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Нов продукт</h1>

<form action="{{ route('admin.products.store') }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow" enctype="multipart/form-data">
    @csrf

    <div>
        <label class="block mb-1 font-semibold">Име:</label>
        <input type="text" name="name" class="w-full border rounded p-2" required>
    </div>

    <div>
        <label class="block mb-1 font-semibold">Описание:</label>
        <textarea name="description" class="w-full border rounded p-2"></textarea>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block mb-1 font-semibold">Цена (лв):</label>
            <input type="number" step="0.01" name="price" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block mb-1 font-semibold">Количество:</label>
            <input type="number" name="stock" class="w-full border rounded p-2" required>
        </div>
    </div>

    <div>
        <label class="block mb-1 font-semibold">Категория:</label>
        <select name="category_id" class="w-full border rounded p-2" required>
            <option value="">Изберете категория</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

     <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image mr-1"></i>Снимка на лаптопа
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Качи снимка</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)">
                                </label>
                                <p class="pl-1">или drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF до 2MB</p>
                        </div>
                    </div>
                    <div id="image-preview" class="mt-4 hidden">
                        <img id="preview-img" class="h-32 w-32 object-cover rounded-lg border border-gray-300" src="" alt="Преглед">
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

    {{-- <div>
        <label class="block mb-1 font-semibold">URL на снимка:</label>
        <input type="text" name="image" class="w-full border rounded p-2">
    </div> --}}

    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Запази</button>
</form>
<script src="{{ asset('js/validation/orderValidation.js') }}"></script>
<script>

function previewImage(input) {
    const preview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
