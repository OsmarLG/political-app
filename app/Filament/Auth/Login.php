<?php
namespace App\Filament\Auth;

use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Pages\Auth\Login as BaseAuth;
use Illuminate\Validation\ValidationException;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;


class Login extends BaseAuth
{
    public bool $remember = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getUsernameFormComponent(), 
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ])
            ->statePath('data');
    }
    
    protected function getUsernameFormComponent(): Component 
    {
        return TextInput::make('username')
            ->label('Username')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    { 
        return [
            'username' => $data['username'],
            'password'  => $data['password'],
        ];
    }
    
    public function authenticate(): ?LoginResponseContract
    {
        $credentials = $this->getCredentialsFromFormData($this->form->getState());
    
        if (!Auth::attempt($credentials, $this->data['remember'] ?? false)) {
            throw ValidationException::withMessages([
                'username' => [trans('filament::login.messages.failed')],
            ]);
        }

        $redirect = redirect()->intended(config('filament.home_url'));
    
        return $redirect instanceof LoginResponseContract ? $redirect : null;
    }
    
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'username' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}
