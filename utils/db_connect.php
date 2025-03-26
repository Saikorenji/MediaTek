<?php
// Chargement sécurisé des constantes depuis le fichier env.php
require_once __DIR__ . '/env.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    // Journalisation de l'erreur dans un fichier log (à créer dans utils par ex.)
    error_log("[" . date('Y-m-d H:i:s') . "] Connexion échouée : " . $e->getMessage() . "\n", 3, __DIR__ . '/db_error.log');
    // Message générique affiché à l'utilisateur
    die('Erreur de connexion à la base de données.');
}
