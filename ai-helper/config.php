<?php
// ai-helper config: database connection (PDO)
try {
    error_log("Connecting to database...");
    $pdo = new PDO("mysql:host=localhost;dbname=stuarz;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    error_log("Database connection successful");
} catch (PDOException $e) {
    // In production you may want to log instead of die
    die("Koneksi database gagal: " . $e->getMessage());
}

// Helper function to search documentation
// Helper function to get page URL based on section and slug
function get_page_url($section, $slug) {
    $section = strtolower($section);
    $base_url = '/stuarz';
    
    // Map sections to their corresponding URLs
    $section_map = [
        'getting started' => '/docs/getting-started',
        'stuarz' => '/about',
        'dashboard' => '/dashboard',
        'news' => '/news',
        'docs' => '/docs',
        'announcements' => '/announcements',
        'classes' => '/classes',
        'grades' => '/grades',
        'tasks' => '/tasks',
        'attendance' => '/attendance',
        'schedule' => '/schedule',
        'certificates' => '/certificates'
    ];

    $url = $base_url;
    if (isset($section_map[$section])) {
        $url .= $section_map[$section];
        if ($slug) {
            $url .= '/' . $slug;
        }
    }

    return $url;
}

function search_documentation($query) {
    global $pdo;
    
    // Split query into words and prepare search terms
    $words = explode(' ', trim(strtolower($query)));
    $searchTerms = [];
    $params = [];
    
    foreach ($words as $i => $word) {
        if (strlen($word) < 2) continue; // Skip very short words
        
        // Add wildcards between characters for more flexible matching
        $searchTerm = '%' . implode('%', str_split($word)) . '%';
        $searchTerms[] = "(LOWER(title) LIKE :title$i OR LOWER(description) LIKE :desc$i OR LOWER(content) LIKE :content$i)";
        $params[":title$i"] = $searchTerm;
        $params[":desc$i"] = $searchTerm;
        $params[":content$i"] = $searchTerm;
    }
    
    if (empty($searchTerms)) {
        return []; // Return empty if no valid search terms
    }
    
    // Combine all search terms with AND
    $whereClause = implode(' AND ', $searchTerms);
    
    $sql = "SELECT *, 
        CASE 
            WHEN LOWER(title) LIKE :fullquery THEN 1
            WHEN LOWER(description) LIKE :fullquery THEN 2
            WHEN section = 'Getting Started' THEN 3
            ELSE 4
        END as relevance
        FROM documentation 
        WHERE $whereClause 
        ORDER BY relevance, title
        LIMIT 5";
    
    // Add the full query parameter
    $params[':fullquery'] = '%' . strtolower($query) . '%';
    
    error_log("Search query: " . $query);
    error_log("SQL: " . $sql);
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}