<?php

namespace App\Service;

use PDO;

class QuestRewardService
{
    public function calculateExerciseRewards(int $livello, int $livelloDiff, int $xpCustom, int $moneteCustom): array
    {
        $livello = max(1, $livello);
        $diff = is_numeric($livelloDiff) ? (int) $livelloDiff : 1;
        $diff = $this->clampValue(0, 3, $diff);

        $xpCustomValue = is_numeric($xpCustom) ? (int) $xpCustom : null;
        $moneteCustomValue = is_numeric($moneteCustom) ? (int) $moneteCustom : null;

        $useAutoXp = $xpCustomValue === null || $xpCustomValue <= 0;
        $useAutoMonete = $moneteCustomValue === null || $moneteCustomValue <= 0;

        $xpNext = 0;
        if ($useAutoXp || $useAutoMonete) {
            $xpNext = $this->getXpNextForLevel($livello);
        }

        $n = (int) round(2.5 + 5.5 * ($livello - 1) / 59);
        $n = $this->clampValue(3, 8, $n);

        $diffXpMap = [
            0 => 0.80,
            1 => 1.00,
            2 => 1.25,
            3 => 1.50,
        ];
        $diffCoinMap = [
            0 => 0.85,
            1 => 1.00,
            2 => 1.20,
            3 => 1.30,
        ];

        $mXp = $diffXpMap[$diff] ?? 1.00;
        $mCoin = $diffCoinMap[$diff] ?? 1.00;

        if ($useAutoXp) {
            $xpReward = (int) round($xpNext * $mXp / $n);
            $xpCap = (int) round(0.45 * $xpNext);
            $xpCustomValue = min($xpReward, $xpCap);
        }

        if ($useAutoMonete) {
            $moneteCustomValue = (int) round(23.3 * pow($xpNext, 0.4) * $mCoin / $n);
        }

        return [
            'xp' => $xpCustomValue ?? 0,
            'monete' => $moneteCustomValue ?? 0,
            'xp_auto' => $useAutoXp,
            'monete_auto' => $useAutoMonete,
        ];
    }

    private function getXpNextForLevel(int $livello): int
    {
        $nextLevel = $livello + 1;
        $riga = Database::getConnection()->prepare('SELECT xp FROM ct_xp_livello WHERE livello = :livello');
        $riga->execute(['livello' => $nextLevel]);
        $row = $riga->fetch(PDO::FETCH_ASSOC);
        if (!isset($row['xp'])) {
            $riga = Database::getConnection()->prepare('SELECT xp FROM ct_xp_livello ORDER BY livello DESC LIMIT 1');
            $riga->execute([]);
            $row = $riga->fetch(PDO::FETCH_ASSOC);
        }

        return isset($row['xp']) ? (int) $row['xp'] : 0;
    }

    private function clampValue(int $min, int $max, int $value): int
    {
        return max($min, min($max, $value));
    }
}
