<?php
// api/products.php
require_once 'helpers.php';
$products = json_decode(file_get_contents(__DIR__ . '/../products.json'), true);

// Optional: support query params ?category=Women&sort=low-high
$category = $_GET['category'] ?? null;
$sort = $_GET['sort'] ?? null;

$items = array_values($products);

if ($category && $category !== 'all') {
  $items = array_filter($items, fn($p)=>strcasecmp($p['category'] ?? 'Uncategorized', $category)===0);
}

if ($sort === 'low-high') usort($items, fn($a,$b)=>$a['price'] <=> $b['price']);
if ($sort === 'high-low') usort($items, fn($a,$b)=>$b['price'] <=> $a['price']);

jsonResponse(['products'=>array_values($items)]);
