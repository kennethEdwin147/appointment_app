<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Créer votre profil créateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('creator.store') }}">
                        @csrf

                        <!-- Fuseau horaire -->
                        <div class="mb-4">
                            <x-input-label for="timezone" :value="__('Votre fuseau horaire')" />
                            <select id="timezone" 
                                    name="timezone" 
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                <option value="">Sélectionnez votre fuseau horaire</option>
                                @foreach(\App\Enums\Timezone::getTimezonesByRegion() as $region => $timezones)
                                    <optgroup label="{{ $region }}">
                                        @foreach($timezones as $value => $label)
                                            <option value="{{ $value }}" {{ old('timezone') == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('timezone')" class="mt-2" />
                            <p class="mt-2 text-sm text-gray-500">
                                Ce fuseau horaire sera utilisé pour gérer vos disponibilités et rendez-vous.
                            </p>
                        </div>

                        <!-- Bio -->
                        <div class="mb-4">
                            <x-input-label for="bio" :value="__('Bio')" />
                            <textarea id="bio" 
                                    name="bio" 
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    rows="4">{{ old('bio') }}</textarea>
                            <x-input-error :messages="$errors->get('bio')" class="mt-2" />
                        </div>

                        <!-- Autres champs du formulaire... -->

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Créer mon profil') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
