<?php


require_once __DIR__ . '/config/db.php';

$newPassword = 'admin12345';
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    $pdo = getDB();

    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE role = 'admin'");
    $result = $stmt->execute([$hashedPassword]);
    
    $rowsAffected = $stmt->rowCount();
    
    if ($rowsAffected > 0) {
        echo "SUCCESS: Admin password updated successfully!\n";
        echo "Rows affected: $rowsAffected\n";
        echo "New password: $newPassword\n";
    } else {
        echo "WARNING: No admin users found in the database.\n";
    }
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

