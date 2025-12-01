<?php
/**
 * Quick Test: Class Membership Fix Verification
 * 
 * Usage:
 * 1. Place this in: app/tests/test_class_membership.php
 * 2. Run via CLI: php app/tests/test_class_membership.php
 * 3. Or access via browser: http://localhost/stuarz/public/index.php?page=test_class_membership
 * 
 * This script tests:
 * - ClassService methods
 * - getAll Classes with User Status
 * - is_joined flag accuracy
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__DIR__) . '/services/ClassService.php';
require_once dirname(__DIR__) . '/model/ClassModel.php';
require_once dirname(__DIR__) . '/config/config.php';

class ClassMembershipTest {
    private PDO $pdo;
    private ClassService $service;
    private $mysqli;
    private ClassModel $model;
    
    // Test IDs (adjust based on your test data)
    private int $testUserId = 999; // Use a test user ID that exists
    private int $testClassId1 = 1;
    private int $testClassId2 = 2;
    
    public function __construct() {
        // PDO Connection
        try {
            $this->pdo = new PDO('mysql:host=localhost;dbname=stuarz;charset=utf8mb4', 'root', '');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            $this->error("PDO Connection failed: " . $e->getMessage());
            exit(1);
        }
        
        // MySQLi for ClassModel
        global $config;
        $this->mysqli = $config;
        
        // Initialize services
        $this->service = new ClassService($this->pdo);
        $this->model = new ClassModel($this->mysqli);
    }
    
    public function runAllTests(): array {
        $results = [];
        
        $results['test_unique_constraint'] = $this->testUniqueConstraint();
        $results['test_join_class'] = $this->testJoinClass();
        $results['test_join_idempotent'] = $this->testJoinIdempotent();
        $results['test_get_all_classes_with_user_status'] = $this->testGetAllClassesWithUserStatus();
        $results['test_is_user_member'] = $this->testIsUserMember();
        $results['test_leave_class'] = $this->testLeaveClass();
        $results['test_query_accuracy'] = $this->testQueryAccuracy();
        
        return $results;
    }
    
    private function testUniqueConstraint(): array {
        $test = ['name' => 'Unique Constraint Check', 'pass' => false, 'message' => ''];
        
        try {
            $query = "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                     WHERE TABLE_NAME = 'class_members' 
                     AND CONSTRAINT_NAME = 'ux_class_user' 
                     AND TABLE_SCHEMA = 'stuarz'";
            $stmt = $this->pdo->query($query);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && $result['CONSTRAINT_NAME'] === 'ux_class_user') {
                $test['pass'] = true;
                $test['message'] = 'Unique constraint ux_class_user exists on (class_id, user_id)';
            } else {
                $test['message'] = 'Unique constraint NOT found. Run migration first!';
            }
        } catch (Exception $e) {
            $test['message'] = 'Error: ' . $e->getMessage();
        }
        
        return $test;
    }
    
    private function testJoinClass(): array {
        $test = ['name' => 'Join Class', 'pass' => false, 'message' => ''];
        
        try {
            // Clean up first
            $this->service->leaveClass($this->testUserId, $this->testClassId1);
            
            // Join class
            $result = $this->service->joinClass($this->testUserId, $this->testClassId1, 'student');
            
            if ($result && $this->service->isUserMember($this->testUserId, $this->testClassId1)) {
                $test['pass'] = true;
                $test['message'] = "User {$this->testUserId} successfully joined class {$this->testClassId1}";
            } else {
                $test['message'] = 'Join failed or user not found as member after join';
            }
        } catch (Exception $e) {
            $test['message'] = 'Error: ' . $e->getMessage();
        }
        
        return $test;
    }
    
    private function testJoinIdempotent(): array {
        $test = ['name' => 'Join Idempotency (ON DUPLICATE KEY UPDATE)', 'pass' => false, 'message' => ''];
        
        try {
            // Join once with role 'student'
            $this->service->joinClass($this->testUserId, $this->testClassId1, 'student');
            
            // Join again with role 'teacher'
            $result = $this->service->joinClass($this->testUserId, $this->testClassId1, 'teacher');
            
            // Check: should have 1 row, role should be 'teacher'
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM class_members WHERE user_id = ? AND class_id = ?');
            $stmt->execute([$this->testUserId, $this->testClassId1]);
            $count = $stmt->fetchColumn();
            
            $role = $this->service->getUserRoleInClass($this->testUserId, $this->testClassId1);
            
            if ($result && $count == 1 && $role === 'teacher') {
                $test['pass'] = true;
                $test['message'] = "Idempotent join works: 1 row, role updated to 'teacher'";
            } else {
                $test['message'] = "Idempotent join FAILED: count={$count}, role={$role}";
            }
        } catch (Exception $e) {
            $test['message'] = 'Error: ' . $e->getMessage();
        }
        
        return $test;
    }
    
    private function testGetAllClassesWithUserStatus(): array {
        $test = ['name' => 'getAllClassesWithUserStatus (CRITICAL)', 'pass' => false, 'message' => ''];
        
        try {
            // Setup: user joined to testClassId1 only
            $this->service->leaveClass($this->testUserId, $this->testClassId1);
            $this->service->leaveClass($this->testUserId, $this->testClassId2);
            $this->service->joinClass($this->testUserId, $this->testClassId1, 'student');
            
            // Get all classes with status
            $classes = $this->service->getAllClassesWithUserStatus($this->testUserId);
            
            // Verify
            $class1 = array_filter($classes, fn($c) => $c['id'] == $this->testClassId1);
            $class2 = array_filter($classes, fn($c) => $c['id'] == $this->testClassId2);
            
            $class1 = array_shift($class1);
            $class2 = array_shift($class2);
            
            $success = $class1 && $class2 && 
                      $class1['is_joined'] == 1 && $class1['member_role'] === 'student' &&
                      $class2['is_joined'] == 0 && $class2['member_role'] === null;
            
            if ($success) {
                $test['pass'] = true;
                $test['message'] = "Query accurate: class1 is_joined=1, class2 is_joined=0";
            } else {
                $test['message'] = "Query INCORRECT: class1 is_joined={$class1['is_joined']}, class2 is_joined={$class2['is_joined']}";
            }
        } catch (Exception $e) {
            $test['message'] = 'Error: ' . $e->getMessage();
        }
        
        return $test;
    }
    
    private function testIsUserMember(): array {
        $test = ['name' => 'isUserMember Quick Check', 'pass' => false, 'message' => ''];
        
        try {
            $isMember1 = $this->service->isUserMember($this->testUserId, $this->testClassId1);
            $isMember2 = $this->service->isUserMember($this->testUserId, $this->testClassId2);
            
            if ($isMember1 && !$isMember2) {
                $test['pass'] = true;
                $test['message'] = "isUserMember accurate: user is member of class 1, not class 2";
            } else {
                $test['message'] = "isUserMember INCORRECT: class1={$isMember1}, class2={$isMember2}";
            }
        } catch (Exception $e) {
            $test['message'] = 'Error: ' . $e->getMessage();
        }
        
        return $test;
    }
    
    private function testLeaveClass(): array {
        $test = ['name' => 'Leave Class', 'pass' => false, 'message' => ''];
        
        try {
            // User already in testClassId1, leave it
            $result = $this->service->leaveClass($this->testUserId, $this->testClassId1);
            $isMember = $this->service->isUserMember($this->testUserId, $this->testClassId1);
            
            if ($result && !$isMember) {
                $test['pass'] = true;
                $test['message'] = "User successfully left class";
            } else {
                $test['message'] = "Leave failed or user still member";
            }
        } catch (Exception $e) {
            $test['message'] = 'Error: ' . $e->getMessage();
        }
        
        return $test;
    }
    
    private function testQueryAccuracy(): array {
        $test = ['name' => 'ClassModel::getAllClassesWithUserStatus', 'pass' => false, 'message' => ''];
        
        try {
            // Setup: user joined testClassId1 only
            $this->service->leaveClass($this->testUserId, $this->testClassId1);
            $this->service->leaveClass($this->testUserId, $this->testClassId2);
            $this->service->joinClass($this->testUserId, $this->testClassId1, 'student');
            
            // Get via ClassModel
            $classes = $this->model->getAllClassesWithUserStatus($this->testUserId);
            
            $class1 = array_filter($classes, fn($c) => $c['id'] == $this->testClassId1);
            $class2 = array_filter($classes, fn($c) => $c['id'] == $this->testClassId2);
            
            $class1 = array_shift($class1);
            $class2 = array_shift($class2);
            
            if ($class1 && $class2 && 
                $class1['is_joined'] == 1 && 
                $class2['is_joined'] == 0) {
                $test['pass'] = true;
                $test['message'] = "ClassModel query also accurate";
            } else {
                $test['message'] = "ClassModel query INCORRECT";
            }
        } catch (Exception $e) {
            $test['message'] = 'Error: ' . $e->getMessage();
        }
        
        return $test;
    }
    
    private function error($msg) {
        echo "\n[ERROR] $msg\n";
    }
    
    public function printResults($results) {
        echo "\n";
        echo "========================================\n";
        echo "CLASS MEMBERSHIP FIX - TEST RESULTS\n";
        echo "========================================\n";
        
        $passed = 0;
        $failed = 0;
        
        foreach ($results as $key => $result) {
            $status = $result['pass'] ? '✓ PASS' : '✗ FAIL';
            echo "\n{$status} | {$result['name']}\n";
            echo "   {$result['message']}\n";
            
            if ($result['pass']) {
                $passed++;
            } else {
                $failed++;
            }
        }
        
        echo "\n========================================\n";
        echo "SUMMARY: {$passed} passed, {$failed} failed\n";
        echo "========================================\n";
        
        if ($failed === 0) {
            echo "\n✓ All tests PASSED! Fix is working correctly.\n";
        } else {
            echo "\n✗ Some tests FAILED. Check implementation.\n";
        }
    }
}

// Run tests
try {
    $tester = new ClassMembershipTest();
    $results = $tester->runAllTests();
    $tester->printResults($results);
} catch (Exception $e) {
    echo "Fatal error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
