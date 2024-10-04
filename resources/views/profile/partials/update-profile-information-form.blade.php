<section>
    <header>
        <h2 class="text-lg font-medium text-wite-900 dark:text-gray-100">
            {{ __('Información del Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-wite-900 dark:text-gray-400">
            {{ __("Actualice la información del perfil y la dirección de correo electrónico de su cuenta.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nombre')" style="color:black" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Correo')" style="color:black" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Su dirección de correo electrónico no está verificada.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            {{ __('Haga clic aquí para reenviar el correo de verificación.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            {{ __('.') }}Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="address" :value="__('Dirección')" style="color:black" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700" :value="old('address', $user->address)" required autofocus autocomplete="address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>
        <div>
            <x-input-label for="phone" :value="__('Teléfono')" style="color:black" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700" :value="old('phone', $user->phone)" required autofocus autocomplete="phone" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="photo" :value="__('Foto de Perfil')" style="color:black" />
            <imput type="file" class="form-imput-file" id="photo" name="photo" accept="image/*">
            @if ($user->photo)
                 <x-input-label for="photo" :value="__('Foto Actual')" />
                 <div>
                     <img src="{{ asset('atorage/' . $user->photo)}}">
                </div>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Datos Guardados.') }}</p>
            @endif
        </div>
    </form>
</section>
