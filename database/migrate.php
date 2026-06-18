<?php

require_once __DIR__ . '/../src/config/database.php';

try {
    $db = Database::connect();
    $driver = $db->getAttribute(PDO::ATTR_DRIVER_NAME);

    echo "Running migrations for driver: $driver...\n";

    // Add image column to fields table
    try {
        if ($driver === 'sqlite') {
            $db->exec("ALTER TABLE fields ADD COLUMN image VARCHAR(255) DEFAULT NULL");
        } else {
            $db->exec("ALTER TABLE fields ADD COLUMN image VARCHAR(255) DEFAULT NULL AFTER description");
        }
        echo "Added image column to fields table.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') === false && strpos($e->getMessage(), 'duplicate column name') === false) {
            echo "Warning on image column: " . $e->getMessage() . "\n";
        } else {
            echo "Image column already exists.\n";
        }
    }

    // Add unique constraint to bookings table
    try {
        if ($driver === 'sqlite') {
            $db->exec("CREATE UNIQUE INDEX IF NOT EXISTS unique_booking_slot ON bookings(field_id, booking_date, start_time)");
        } else {
            $db->exec("ALTER TABLE bookings ADD UNIQUE KEY unique_booking_slot (field_id, booking_date, start_time)");
        }
        echo "Added unique constraint to bookings table.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') === false) {
            echo "Warning on unique constraint: " . $e->getMessage() . "\n";
        } else {
            echo "Unique constraint already exists.\n";
        }
    }

    echo "Migrations completed successfully.\n";

} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
