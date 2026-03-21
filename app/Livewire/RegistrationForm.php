<?php

namespace App\Livewire;

use App\Enums\Degree;
use App\Enums\Prefix;
use App\Mail\RegistrationConfirmation;
use App\Models\Participant;
use App\Models\Registration;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class RegistrationForm extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $participants = [];

    public string $locale = 'ro';

    public function mount(): void
    {
        $this->locale = session('locale', 'ro');
        app()->setLocale($this->locale);
        $this->addParticipant();
    }

    public function switchLocale(string $locale): void
    {
        $this->locale = in_array($locale, ['ro', 'en']) ? $locale : 'ro';
        session(['locale' => $this->locale]);
        app()->setLocale($this->locale);
    }

    public function addParticipant(): void
    {
        $this->participants[] = [
            'prefix' => Prefix::FR->value,
            'full_name' => '',
            'degree' => Degree::Ucenic->value,
            'dignity' => '',
            'lodge_name' => '',
            'lodge_number' => '',
            'orient' => '',
            'email' => '',
            'phone' => '',
            'friday_dinner_count' => 0,
            'symposium_lunch_count' => 0,
            'ritual_participation' => false,
            'ball_count' => 0,
            'observations' => '',
        ];
    }

    public function removeParticipant(int $index): void
    {
        if (count($this->participants) <= 1) {
            return;
        }

        unset($this->participants[$index]);
        $this->participants = array_values($this->participants);
    }

    public function incrementCount(int $index, string $field): void
    {
        $max = config('simpozion.max_event_count');
        $current = (int) ($this->participants[$index][$field] ?? 0);

        if ($current < $max) {
            $this->participants[$index][$field] = $current + 1;
        }
    }

    public function decrementCount(int $index, string $field): void
    {
        $current = (int) ($this->participants[$index][$field] ?? 0);

        if ($current > 0) {
            $this->participants[$index][$field] = $current - 1;
        }
    }

    #[Computed]
    public function isRegistrationOpen(): bool
    {
        return Setting::registrationOpen();
    }

    #[Computed]
    public function totalAmount(): int
    {
        $total = 0;

        foreach ($this->participants as $participant) {
            $total += Participant::computeCost($participant);
        }

        return $total;
    }

    public function submit(): void
    {
        if (! $this->isRegistrationOpen) {
            return;
        }

        $this->validate();

        $registration = DB::transaction(function () {
            $registration = Registration::create([
                'total_amount' => $this->totalAmount,
            ]);

            foreach ($this->participants as $participantData) {
                $registration->participants()->create([
                    'prefix' => $participantData['prefix'],
                    'full_name' => $participantData['full_name'],
                    'degree' => $participantData['degree'],
                    'dignity' => $participantData['dignity'],
                    'lodge_name' => $participantData['lodge_name'],
                    'lodge_number' => $participantData['lodge_number'],
                    'orient' => $participantData['orient'],
                    'email' => $participantData['email'],
                    'phone' => $participantData['phone'],
                    'friday_dinner_count' => $participantData['friday_dinner_count'] ?: 0,
                    'symposium_lunch_count' => $participantData['symposium_lunch_count'] ?: 0,
                    'ritual_participation' => $participantData['ritual_participation'] ?? false,
                    'ball_count' => $participantData['ball_count'] ?: 0,
                    'observations' => $participantData['observations'] ?: null,
                ]);
            }

            return $registration;
        });

        $registration->load('participants');

        try {
            $firstParticipant = $registration->participants->first();
            Mail::to($firstParticipant->email)->send(new RegistrationConfirmation($registration));
        } catch (\Throwable $e) {
            Log::error('Failed to send registration confirmation email', [
                'registration_id' => $registration->id,
                'error' => $e->getMessage(),
            ]);
        }

        $this->redirect("/payment/{$registration->uuid}");
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        $maxCount = config('simpozion.max_event_count');

        $rules = [
            'participants' => ['required', 'array', 'min:1'],
        ];

        foreach ($this->participants as $index => $participant) {
            $rules["participants.{$index}.prefix"] = ['required', new Enum(Prefix::class)];
            $rules["participants.{$index}.full_name"] = ['required', 'string', 'max:255'];
            $rules["participants.{$index}.degree"] = ['required', new Enum(Degree::class)];
            $rules["participants.{$index}.dignity"] = ['nullable', 'string', 'max:255'];
            $rules["participants.{$index}.lodge_name"] = ['required', 'string', 'max:255'];
            $rules["participants.{$index}.lodge_number"] = ['required', 'integer', 'min:1'];
            $rules["participants.{$index}.orient"] = ['required', 'string', 'max:255'];
            $rules["participants.{$index}.email"] = ['required', 'email', 'max:255'];
            $rules["participants.{$index}.phone"] = ['required', 'string', 'max:20'];
            $rules["participants.{$index}.friday_dinner_count"] = ['required', 'integer', 'min:0', "max:{$maxCount}"];
            $rules["participants.{$index}.symposium_lunch_count"] = ['required', 'integer', 'min:0', "max:{$maxCount}"];
            $rules["participants.{$index}.ritual_participation"] = ['boolean'];
            $rules["participants.{$index}.ball_count"] = ['required', 'integer', 'min:0', "max:{$maxCount}"];
            $rules["participants.{$index}.observations"] = ['nullable', 'string'];
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    protected function validationAttributes(): array
    {
        $attributes = [];

        foreach ($this->participants as $index => $participant) {
            $num = $index + 1;
            $attributes["participants.{$index}.prefix"] = "Participant {$num} - Prefix";
            $attributes["participants.{$index}.full_name"] = "Participant {$num} - Nume și Prenume";
            $attributes["participants.{$index}.degree"] = "Participant {$num} - Grad";
            $attributes["participants.{$index}.dignity"] = "Participant {$num} - Demnitate";
            $attributes["participants.{$index}.lodge_name"] = "Participant {$num} - Numele Lojei";
            $attributes["participants.{$index}.lodge_number"] = "Participant {$num} - Numărul Lojei";
            $attributes["participants.{$index}.orient"] = "Participant {$num} - Orient";
            $attributes["participants.{$index}.email"] = "Participant {$num} - Email";
            $attributes["participants.{$index}.phone"] = "Participant {$num} - Telefon";
            $attributes["participants.{$index}.friday_dinner_count"] = "Participant {$num} - Cină vineri";
            $attributes["participants.{$index}.symposium_lunch_count"] = "Participant {$num} - Simpozion + Prânz";
            $attributes["participants.{$index}.ball_count"] = "Participant {$num} - Bal";
        }

        return $attributes;
    }

    public function render(): mixed
    {
        return view('livewire.pages.registration-form');
    }
}
