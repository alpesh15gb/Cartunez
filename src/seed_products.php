<?php
// seed_products.php
// Place this in the root of your WordPress install (c:\CarTunez\src) and run via:
// docker compose exec -T wordpress php seed_products.php

require_once('wp-load.php');

if (!class_exists('WooCommerce')) {
    die("WooCommerce is not installed or active.\n");
}

echo "Generating Car Tunez Products...\n";

$categories = [
    'Interior Accessories' => ['Floor Mats', 'Seat Covers', 'Steering Wheel Cover', 'Sun Shade'],
    'Exterior Parts' => ['Bumper Guard', 'Spoiler', 'Roof Rack', 'Mud Flaps'],
    'Electronics' => ['Android Player', 'Dash Cam', 'Parking Sensors', 'LED Headlights'],
    'Performance' => ['Air Filter', 'Spark Plugs', 'Exhaust Tip', 'Brake Pads'],
    'Car Care' => ['Wax Polish', 'Microfiber Cloth', 'Vacuum Cleaner', 'Glass Cleaner']
];

$images = [
    // Placeholder images from Placehold.co (Dark theme)
    'https://placehold.co/600x600/1a1a1a/ff0000?text=Product+Image',
    'https://placehold.co/600x600/1a1a1a/ffffff?text=Car+Part'
];

foreach ($categories as $cat_name => $products) {
    // Create/Get Category
    $term = term_exists($cat_name, 'product_cat');
    if (!$term) {
        $term = wp_insert_term($cat_name, 'product_cat');
    }
    $cat_id = is_array($term) ? $term['term_id'] : $term;
    echo "Category: $cat_name (ID: $cat_id)\n";

    foreach ($products as $prod_name) {
        $post_id = wp_insert_post([
            'post_title' => $prod_name,
            'post_content' => "High quality $prod_name for your vehicle. Durable, stylish, and easy to install.",
            'post_status' => 'publish',
            'post_type' => 'product',
        ]);

        if ($post_id) {
            wp_set_object_terms($post_id, $cat_id, 'product_cat');
            update_post_meta($post_id, '_visibility', 'visible');
            update_post_meta($post_id, '_stock_status', 'instock');
            update_post_meta($post_id, 'total_sales', '0');
            update_post_meta($post_id, '_price', rand(20, 500));
            update_post_meta($post_id, '_regular_price', rand(20, 500));
            update_post_meta($post_id, '_sale_price', '');
            update_post_meta($post_id, '_sku', strtoupper(substr($prod_name, 0, 3)) . '-' . rand(100, 999));

            // Set dummy image if possible (requires sideloading, skipping for speed, just setting text)
            // Ideally we would sideload images here.

            echo " - Created: $prod_name (ID: $post_id)\n";
        }
    }
}

echo "Done! 20 Products Generated.\n";
