<?php
/**
 * Pagination Helper Function
 * 
 * @param int $total_records Total number of records
 * @param int $current_page Current page number
 * @param int $per_page Records per page
 * @param string $base_url Base URL for pagination links
 * @return string HTML for pagination
 */
function renderPagination($total_records, $current_page = 1, $per_page = 10, $base_url = '') {
    $total_pages = ceil($total_records / $per_page);
    
    // Always show pagination, even if only 1 page
    
    // Get current page from URL if not provided
    if (empty($current_page)) {
        $current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    }
    
    // Build base URL
    if (empty($base_url)) {
        $base_url = basename($_SERVER['PHP_SELF']);
    }
    
    // Preserve search parameter
    $search = isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
    
    // Calculate page numbers
    $start_page = max(1, $current_page - 2);
    $end_page = min($total_pages, $current_page + 2);
    
    $html = '<div class="pagination-wrapper">';
    $html .= '<div class="pagination-info">';
    $html .= '<span>Total ' . number_format($total_records) . ' results</span>';
    $html .= '</div>';
    
    $html .= '<div class="pagination-controls">';
    
    // Rows per page selector
    $html .= '<div class="rows-per-page">';
    $html .= '<span class="rows-label">Rows per page</span>';
    $html .= '<select onchange="changeRowsPerPage(this.value, \'' . htmlspecialchars($base_url) . '\')" class="rows-select">';
    $options = [10, 25, 50, 100];
    foreach ($options as $option) {
        $selected = ($per_page == $option) ? 'selected' : '';
        $html .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
    }
    $html .= '</select>';
    $html .= '</div>';
    
    // Page info
    $html .= '<div class="page-info">';
    $html .= '<span>Page ' . $current_page . ' of ' . $total_pages . '</span>';
    $html .= '</div>';
    
    // Navigation buttons
    $html .= '<div class="pagination-buttons">';
    
    // First page
    if ($current_page > 1) {
        $html .= '<a href="' . $base_url . '?page=1' . $search . '" class="pagination-nav" title="First page">&laquo;</a>';
    } else {
        $html .= '<span class="pagination-nav disabled" title="First page">&laquo;</span>';
    }
    
    // Previous page
    if ($current_page > 1) {
        $html .= '<a href="' . $base_url . '?page=' . ($current_page - 1) . $search . '" class="pagination-nav" title="Previous page">&lt;</a>';
    } else {
        $html .= '<span class="pagination-nav disabled" title="Previous page">&lt;</span>';
    }
    
    // Next page
    if ($current_page < $total_pages) {
        $html .= '<a href="' . $base_url . '?page=' . ($current_page + 1) . $search . '" class="pagination-nav" title="Next page">&gt;</a>';
    } else {
        $html .= '<span class="pagination-nav disabled" title="Next page">&gt;</span>';
    }
    
    // Last page
    if ($current_page < $total_pages) {
        $html .= '<a href="' . $base_url . '?page=' . $total_pages . $search . '" class="pagination-nav" title="Last page">&raquo;</a>';
    } else {
        $html .= '<span class="pagination-nav disabled" title="Last page">&raquo;</span>';
    }
    
    $html .= '</div>'; // pagination-buttons
    $html .= '</div>'; // pagination-controls
    $html .= '</div>'; // pagination-wrapper
    
    return $html;
}
?>

