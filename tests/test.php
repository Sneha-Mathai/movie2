<?php
use PHPUnit\Framework\TestCase;

class test extends TestCase {
    public function testDatabaseConnectionReturnsObject() {
        require_once __DIR__ . '/../config.php';
        $db = new Database();
        $conn = $db->getConnection();
        $this->assertNotNull($conn, "Database connection should not be null");
    }
}