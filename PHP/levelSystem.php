<?php

/**
 * levelSystem.php
 * Include this file wherever level data is needed.
 *
 * XP formula: total XP to reach level N = N*(N+1)/2 * 100
 *   Level 1  =   100 XP
 *   Level 2  =   300 XP
 *   Level 3  =   600 XP
 *   Level 5  = 1,500 XP
 *   Level 10 = 5,500 XP
 */


/**
 * Returns the total XP required to reach a given level.
 */
function xpForLevel(int $level): int {
    return (int)($level * ($level + 1) / 2 * 100);
}


/**
 * Given a total XP amount, returns:
 *   - level        : current level
 *   - xpThisLevel  : XP accumulated since the start of this level
 *   - xpNeeded     : XP required to complete this level (reach the next one)
 *   - percent      : fill percentage for the XP bar (0.0 – 100.0)
 */
function getLevelData(int $totalXP): array {
    // Derived by solving N*(N+1)/2*100 <= totalXP
    $level = (int)floor((-1 + sqrt(1 + 8 * $totalXP / 100)) / 2);

    $xpThisLevel = $totalXP - xpForLevel($level);
    $xpNeeded    = ($level + 1) * 100; // equals xpForLevel(level+1) - xpForLevel(level)
    $percent     = round($xpThisLevel / $xpNeeded * 100, 1);

    return [
        'level'       => $level,
        'xpThisLevel' => $xpThisLevel,
        'xpNeeded'    => $xpNeeded,
        'percent'     => $percent,
    ];
}
