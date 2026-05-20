<?php

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Ce script doit etre lance en ligne de commande.\n");
    exit(1);
}

$sqlPath = $argv[1] ?? __DIR__ . '/../SQL/bdd_ecf_vite_et_gourmand.sql';
$sqlPath = realpath($sqlPath);

if ($sqlPath === false || !is_file($sqlPath)) {
    fwrite(STDERR, "Fichier SQL introuvable.\n");
    exit(1);
}

$jawsDbUrl = getenv('JAWSDB_URL') ?: ($_ENV['JAWSDB_URL'] ?? null);

if (!$jawsDbUrl) {
    fwrite(STDERR, "Variable JAWSDB_URL absente. Recupere-la avec Heroku ou definis-la dans l'environnement.\n");
    exit(1);
}

$db = parse_url($jawsDbUrl);

if (!$db || empty($db['host']) || empty($db['user']) || !isset($db['pass']) || empty($db['path'])) {
    fwrite(STDERR, "JAWSDB_URL invalide.\n");
    exit(1);
}

$host = $db['host'];
$port = $db['port'] ?? 3306;
$name = ltrim($db['path'], '/');
$user = rawurldecode($db['user']);
$password = rawurldecode($db['pass']);
$dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";

function splitSqlStatements(string $sql): array
{
    $statements = [];
    $buffer = '';
    $quote = null;
    $length = strlen($sql);

    for ($i = 0; $i < $length; $i++) {
        $char = $sql[$i];
        $next = $sql[$i + 1] ?? '';

        if ($quote === null && $char === '-' && $next === '-') {
            while ($i < $length && $sql[$i] !== "\n") {
                $i++;
            }
            $buffer .= "\n";
            continue;
        }

        if ($quote === null && $char === '#') {
            while ($i < $length && $sql[$i] !== "\n") {
                $i++;
            }
            $buffer .= "\n";
            continue;
        }

        if ($quote === null && $char === '/' && $next === '*') {
            $end = strpos($sql, '*/', $i + 2);
            if ($end === false) {
                break;
            }

            $comment = substr($sql, $i, $end - $i + 2);
            if (preg_match('/^\/\*![0-9]{5}\s+(.*)\*\/$/s', $comment, $match)) {
                $buffer .= $match[1];
            }

            $i = $end + 1;
            continue;
        }

        if (($char === "'" || $char === '"' || $char === '`') && ($i === 0 || $sql[$i - 1] !== '\\')) {
            if ($quote === null) {
                $quote = $char;
            } elseif ($quote === $char) {
                $quote = null;
            }
        }

        if ($char === ';' && $quote === null) {
            $statement = trim($buffer);
            if ($statement !== '') {
                $statements[] = $statement;
            }
            $buffer = '';
            continue;
        }

        $buffer .= $char;
    }

    $statement = trim($buffer);
    if ($statement !== '') {
        $statements[] = $statement;
    }

    return $statements;
}

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $sql = file_get_contents($sqlPath);
    if ($sql === false) {
        throw new RuntimeException('Lecture du fichier SQL impossible.');
    }

    $statements = array_values(array_filter(
        splitSqlStatements($sql),
        static function (string $statement): bool {
            return !preg_match('/^\s*CREATE\s+DATABASE\b/i', $statement)
                && !preg_match('/^\s*USE\s+/i', $statement);
        }
    ));

    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

    try {
        $existingTables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        foreach ($existingTables as $table) {
            $quotedTable = str_replace('`', '``', $table);
            $pdo->exec("DROP TABLE IF EXISTS `$quotedTable`");
        }

        foreach ($statements as $index => $statement) {
            try {
                $pdo->exec($statement);
            } catch (Throwable $e) {
                throw new RuntimeException('Erreur SQL instruction ' . ($index + 1) . ' : ' . $e->getMessage(), 0, $e);
            }
        }
    } finally {
        $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    }

    $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

    echo "Import termine dans la base distante.\n";
    echo "Tables trouvees: " . count($tables) . "\n";

    foreach ($tables as $table) {
        $quotedTable = str_replace('`', '``', $table);
        $count = $pdo->query("SELECT COUNT(*) FROM `$quotedTable`")->fetchColumn();
        echo "- $table: $count ligne(s)\n";
    }
} catch (Throwable $e) {
    fwrite(STDERR, $e->getMessage() . "\n");
    exit(1);
}
