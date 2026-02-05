<?php

namespace App\Filament\Employee\Pages;

use App\Models\Attendance;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class CheckInOut extends Page
{
    use HasPageShield;

    protected string $view = 'filament.employee.pages.check-in-out';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowPath;

    protected static string|UnitEnum|null $navigationGroup = 'Attendances';

    public $todayAttendance;
    public $canCheckin = false;
    public $canCheckout = false;
    public $currentTime;

    public function mount(): void
    {
        $this->loadAttendance();
        $this->currentTime = now()->format('H:i:s');
    }

    public function loadAttendance(): void
    {
        $this->todayAttendance = Attendance::where('user_id', auth()->user()->id)
            ->where('date', today())
            ->first();

        $this->canCheckin = ! $this->todayAttendance || $this->todayAttendance->check_in === null;
        $this->canCheckout = $this->todayAttendance && $this->todayAttendance->check_in !== null && $this->todayAttendance->check_out === null;
    }

    public function checkIn(): void
    {
        try {
            if ( ! $this->canCheckin) {
                return;
            }

            $this->todayAttendance = Attendance::create([
                'user_id' => auth()->user()->id,
                'date' => today(),
                'check_in' => now(),
                'status' => now()->format('H:i') > '09:00' ? 'late' : 'present',
            ]);

            Notification::make()
                ->success()
                ->title('Checked in successfully')
                ->body('Your check-in time: '.now()->format('H:i A'))
                ->send();

            $this->loadAttendance();
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Checked in failed')
                ->body('You have already checked in today')
                ->send();
        }
    }

    public function checkOut(): void
    {
        if ($this->todayAttendance) {
            $this->todayAttendance->update([
                'check_out' => now(),
            ]);

            Notification::make()
                ->success()
                ->title('Checked Out successfully')
                ->body('Your check-out time: '.now()->format('H:i A'))
                ->send();

            $this->loadAttendance();
        }
    }

    public function getHeaderActions(): array
    {
        return [
            Action::make('checkIn')
                ->label('Check In')
                ->icon('heroicon-o-arrow-right-on-rectangle')
                ->color('success')
                ->visible(fn() => $this->canCheckin)
                ->requiresConfirmation()
                ->modalHeading('check in')
                ->modalDescription('Are you sure you want to check in now?')
                ->modalSubmitActionLabel('Yes, Check In')
                ->action(fn() => $this->checkIn()),
            Action::make('checkOut')
                ->label('Check Out')
                ->icon('heroicon-o-arrow-left-on-rectangle')
                ->color('danger')
                ->visible(fn() => $this->canCheckout)
                ->requiresConfirmation()
                ->modalHeading('check out')
                ->modalDescription('Are you sure you want to check out now?')
                ->modalSubmitActionLabel('Yes, Check Out')
                ->action(fn() => $this->checkOut()),
        ];
    }
}
