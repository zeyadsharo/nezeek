<?php


namespace App\Filament\Pages\Tenancy;

use App\Models\Customer;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Database\Eloquent\Model;
 
class RegisterTeam extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register team';
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('arabic_title'),
                TextInput::make('slug'),
            ]);
    }
    
    protected function handleRegistration(array $data): Customer
    {
        $team = Customer::create($data);
        
        $team->members()->attach(auth()->user());
        
        return $team;
    }
}