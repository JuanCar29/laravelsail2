<form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
    @csrf

    <!-- Email Address -->
    <flux:input
        name="email"
        :label="__('Email address')"
        :value="old('email')"
        type="email"
        required
        autofocus
        autocomplete="email"
        placeholder="email@example.com"
    />

    <!-- Password -->
    <div class="relative">
        <flux:input
            name="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="current-password"
            :placeholder="__('Password')"
            viewable
        />
    </div>

    <div class="flex items-center justify-end">
        <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
            {{ __('Log in') }}
        </flux:button>
    </div>
</form>