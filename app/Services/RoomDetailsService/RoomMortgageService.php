<?php

namespace App\Services\RoomDetailsService;

use App\Models\Room;

class RoomMortgageService
{
    protected function calculateMonthly($loan, $rate, $years)
    {
        $monthlyRate = $rate / 100 / 12;
        $months = $years * 12;

        $monthlyPayment = $loan * (
            $monthlyRate * pow(1 + $monthlyRate, $months)
        ) / (
            pow(1 + $monthlyRate, $months) - 1
        );

        return round($monthlyPayment, 2);
    }


    protected function breakdown($loan, $rate, $years)
    {
        $monthlyPayment = $this->calculateMonthly($loan, $rate, $years);
        $totalPayment = $monthlyPayment * $years * 12;
        $totalInterest = $totalPayment - $loan;

        return [
            'monthly_payment' => $monthlyPayment,
            'principal_amount' => round($loan, 2),
            'interest_amount' => round($totalInterest, 2),
            'total_payment' => round($totalPayment, 2),
        ];
    }

    public function getRoomMortgage(int $roomId)
    {
        $room = Room::with('loans')->findOrFail($roomId);

        $breakdowns = $room->loans->map(function ($loan) {
            return $this->breakdown(
                $loan->loan_amount,
                $loan->interest_rate,
                $loan->loan_years
            );
        });

        return [
            'room' => $room,
            'loans' => $room->loans,
            'breakdowns' => $breakdowns,
        ];
    }
}
