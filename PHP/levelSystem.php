<?php
function xpForLevel(int $level): int {
    return (int)($level * ($level + 1) / 2 * 100);
}

function getLevelData(int $totalXP): array {
    
    $level = (int)floor((-1 + sqrt(1 + 8 * $totalXP / 100)) / 2);

    $xpThisLevel = $totalXP - xpForLevel($level);
    $xpNeeded    = ($level + 1) * 100;
    $percent     = round($xpThisLevel / $xpNeeded * 100, 1);

    return [
        'level'       => $level,
        'xpThisLevel' => $xpThisLevel,
        'xpNeeded'    => $xpNeeded,
        'percent'     => $percent,
    ];
}
?>