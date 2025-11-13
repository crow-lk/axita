<x-shop::layouts>
    <x-slot:title>
        Contact Us
    </x-slot>

    <div class="container mt-8 px-4">
        <h1 class="text-3xl font-bold mb-4">Contact Us</h1>
        <p class="mb-8">Get in touch with us.</p>
        
        <div class="max-w-lg">
            <x-shop::form :action="route('shop.home.contact_us.send_mail')">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Email</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Phone</label>
                    <input type="text" name="contact" class="w-full px-3 py-2 border border-gray-300 rounded">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Message</label>
                    <textarea name="message" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded" required></textarea>
                </div>
                
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Send Message
                </button>
            </x-shop::form>
        </div>
    </div>
</x-shop::layouts>