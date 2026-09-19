<?php

declare(strict_types=1);

function minutesToSeconds(int $minutes): int
{
    if ($minutes < 1) {
        return 0;
    }

    return $minutes * 60;
}

try {
    if (minutesToSeconds(0) !== 0) {
        throw new RuntimeException("Для 0 минут ожидалось 0 секунд");
    }

    if (minutesToSeconds(2) !== 120) {
        throw new RuntimeException("Для 2 минут ожидалось 120 секунд");
    }

} catch (RuntimeException $e) {
    echo $e->getMessage() . PHP_EOL;
    exit(1);
}

echo "OK\n";
exit(0);

