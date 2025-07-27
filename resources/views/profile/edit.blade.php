@php
    use Illuminate\Support\Facades\Storage;
@endphp

<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
                        <p class="mt-2 text-gray-600">Update your profile information and preferences</p>
                    </div>
                    <a href="{{ route('profile.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Profile
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Profile Images Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Profile Images</h3>
                        
                        <!-- Cover Image -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cover Image</label>
                            <div class="relative">
                                <div class="w-full h-32 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg overflow-hidden">
                                    @if($user->cover_image)
                                        <img src="{{ Storage::url($user->cover_image) }}" alt="Cover" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <button type="button" class="absolute bottom-2 right-2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Profile Image -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                            <div class="flex items-center space-x-4">
                                <img src="{{ $user->profile_image ? Storage::url($user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name ?? $user->name) . '&size=80&background=6366f1&color=ffffff' }}" 
                                     alt="{{ $user->full_name ?? $user->name }}" 
                                     class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">
                                <div>
                                    <button type="button" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                                        Change Photo
                                    </button>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG up to 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="lg:col-span-2">
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <!-- Basic Information -->
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Basic Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                                    <input type="text" name="full_name" id="full_name" 
                                           value="{{ old('full_name', $user->full_name) }}"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                    <input type="text" name="name" id="name" 
                                           value="{{ old('name', $user->name) }}"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" id="email" 
                                           value="{{ old('email', $user->email) }}"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="md:col-span-2">
                                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                                    <textarea name="bio" id="bio" rows="3" 
                                              class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                              placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                    <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Additional Information</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                    <input type="text" name="location" id="location" 
                                           value="{{ old('location', $user->location) }}"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="City, Country">
                                    <x-input-error :messages="$errors->get('location')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                                    <input type="url" name="website" id="website" 
                                           value="{{ old('website', $user->website) }}"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                           placeholder="https://yourwebsite.com">
                                    <x-input-error :messages="$errors->get('website')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Birth Date</label>
                                    <input type="date" name="birth_date" id="birth_date" 
                                           value="{{ old('birth_date', $user->birth_date) }}"
                                           class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                                    <select name="gender" id="gender" 
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select Gender</option>
                                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Settings -->
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-6">Privacy Settings</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">Private Account</h4>
                                        <p class="text-sm text-gray-500">Only followers can see your posts</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="is_private" value="1" 
                                               {{ old('is_private', $user->is_private) ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ route('profile.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                Cancel
                            </a>
                            <button type="submit" class="px-8 py-3 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>