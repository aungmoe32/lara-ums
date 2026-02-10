<x-product::layouts.master>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Product Details</h1>
            <a href="{{ route('product.index') }}" class="text-gray-600 hover:text-gray-900 font-medium">
                &larr; Back to Products
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-4xl mx-auto flex flex-col md:flex-row">
            <div class="md:w-1/2">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="flex items-center justify-center h-64 bg-gray-200 text-gray-500">No Image Available</div>
                @endif
            </div>
            <div class="md:w-1/2 p-8">
                <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold mb-2">
                    {{ ucfirst($product->status) }}
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h2>
                <h3 class="text-2xl font-bold text-gray-700 mb-6">${{ number_format($product->price, 2) }}</h3>

                <p class="text-gray-600 mb-6 leading-relaxed">
                    {{ $product->description }}
                </p>

                <div class="mb-6">
                    <span class="text-gray-700 font-bold">Stock:</span>
                    <span class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $product->stock }} units
                    </span>
                </div>

                <div class="flex items-center mt-8 space-x-4">
                    <a href="{{ route('product.edit', $product->id) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-150 transform hover:-translate-y-1">
                        Edit
                    </a>
                    <form action="{{ route('product.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline transition duration-150 transform hover:-translate-y-1">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-product::layouts.master>