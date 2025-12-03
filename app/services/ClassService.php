<?php
/**
 * ClassService
 * 
 * Handles all class membership operations with proper transaction support,
 * unique constraint handling, and prepared statements.
 * 
 * Uses PDO for database operations.
 */
class ClassService {
    private PDO $db;
    
    public function __construct(PDO $db) {
        $this->db = $db;
        // Ensure exceptions on errors
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Join a user to a class (idempotent operation)
     * 
     * @param int $userId User ID
     * @param int $classId Class ID
     * @param string $role Member role (default: 'student')
     * @return bool True on success, false otherwise
     * @throws Exception
     */
    public function joinClass(int $userId, int $classId, string $role = 'user'): bool {
        try {
            $this->db->beginTransaction();
            
            // Verify class exists
            $stmt = $this->db->prepare('SELECT id FROM classes WHERE id = ? LIMIT 1');
            $stmt->execute([$classId]);
            if (!$stmt->fetch()) {
                throw new Exception('Class not found');
            }

            // Verify user exists
            $stmt = $this->db->prepare('SELECT id FROM users WHERE id = ? LIMIT 1');
            $stmt->execute([$userId]);
            if (!$stmt->fetch()) {
                throw new Exception('User not found');
            }

            // Insert or update member (handles duplicate via unique constraint)
            // Using ON DUPLICATE KEY UPDATE to make operation idempotent
            $sql = "INSERT INTO class_members (class_id, user_id, role, joined_at)
                    VALUES (:classId, :userId, :role, NOW())
                    ON DUPLICATE KEY UPDATE role = VALUES(role), joined_at = NOW()";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':classId' => $classId,
                ':userId' => $userId,
                ':role' => $role
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            // Log or handle error as needed
            // error_log('ClassService::joinClass error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Leave a class (remove membership)
     * 
     * @param int $userId User ID
     * @param int $classId Class ID
     * @return bool True if record was deleted, false otherwise
     */
    public function leaveClass(int $userId, int $classId): bool {
        try {
            $stmt = $this->db->prepare('DELETE FROM class_members WHERE class_id = ? AND user_id = ?');
            $stmt->execute([$classId, $userId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            // Log error
            // error_log('ClassService::leaveClass error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all classes that a user has joined
     * 
     * @param int $userId User ID
     * @return array List of classes with member_role and joined_at
     */
    public function getMyClasses(int $userId): array {
        try {
            $stmt = $this->db->prepare("
                SELECT c.id, c.name, c.code, c.description, c.created_by,
                       cm.role AS member_role, cm.joined_at
                FROM class_members cm
                JOIN classes c ON c.id = cm.class_id
                WHERE cm.user_id = ?
                ORDER BY cm.joined_at DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Log error
            // error_log('ClassService::getMyClasses error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all classes with user's membership status
     * 
     * Critical: LEFT JOIN includes AND cm.user_id = ? to prevent false positives
     * 
     * @param int $userId User ID
     * @return array List of all classes with is_joined (0/1) and member_role (or NULL)
     */
    public function getAllClassesWithUserStatus(int $userId): array {
        try {
            $stmt = $this->db->prepare("
                SELECT
                    c.id, c.name, c.code, c.description, c.created_by,
                    (SELECT COUNT(*) FROM class_members cm2 WHERE cm2.class_id = c.id) AS members_count,
                    CASE WHEN cm.user_id IS NOT NULL THEN 1 ELSE 0 END AS is_joined,
                    cm.role AS member_role,
                    cm.joined_at
                FROM classes c
                LEFT JOIN class_members cm 
                    ON cm.class_id = c.id 
                    AND cm.user_id = ?
                ORDER BY c.id DESC
            ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Log error
            // error_log('ClassService::getAllClassesWithUserStatus error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Quick check if user is member of a class
     * 
     * @param int $userId User ID
     * @param int $classId Class ID
     * @return bool True if user is a member
     */
    public function isUserMember(int $userId, int $classId): bool {
        try {
            $stmt = $this->db->prepare('SELECT 1 FROM class_members WHERE user_id = ? AND class_id = ? LIMIT 1');
            $stmt->execute([$userId, $classId]);
            return (bool)$stmt->fetchColumn();
        } catch (Exception $e) {
            // Log error
            // error_log('ClassService::isUserMember error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get a user's role in a specific class
     * 
     * @param int $userId User ID
     * @param int $classId Class ID
     * @return string|null Role name or null if not a member
     */
    public function getUserRoleInClass(int $userId, int $classId): ?string {
        try {
            $stmt = $this->db->prepare('SELECT role FROM class_members WHERE user_id = ? AND class_id = ? LIMIT 1');
            $stmt->execute([$userId, $classId]);
            $result = $stmt->fetchColumn();
            return $result ?: null;
        } catch (Exception $e) {
            // Log error
            // error_log('ClassService::getUserRoleInClass error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get all members of a class
     * 
     * @param int $classId Class ID
     * @return array List of members with user details
     */
    public function getClassMembers(int $classId): array {
        try {
            $stmt = $this->db->prepare("
                SELECT cm.id, cm.user_id, cm.role, cm.joined_at,
                       u.username, u.email, u.name
                FROM class_members cm
                LEFT JOIN users u ON cm.user_id = u.id
                WHERE cm.class_id = ?
                ORDER BY cm.joined_at DESC
            ");
            $stmt->execute([$classId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Log error
            // error_log('ClassService::getClassMembers error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get class member count
     * 
     * @param int $classId Class ID
     * @return int Number of members
     */
    public function getMemberCount(int $classId): int {
        try {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM class_members WHERE class_id = ?');
            $stmt->execute([$classId]);
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            // Log error
            // error_log('ClassService::getMemberCount error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update a member's role in a class
     * 
     * @param int $userId User ID
     * @param int $classId Class ID
     * @param string $newRole New role
     * @return bool True on success
     */
    public function updateMemberRole(int $userId, int $classId, string $newRole): bool {
        try {
            $stmt = $this->db->prepare('UPDATE class_members SET role = ? WHERE class_id = ? AND user_id = ?');
            $stmt->execute([$newRole, $classId, $userId]);
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            // Log error
            // error_log('ClassService::updateMemberRole error: ' . $e->getMessage());
            throw $e;
        }
    }
}
?>
