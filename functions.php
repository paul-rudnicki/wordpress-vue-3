<?php
function add_cors_http_header(){
    header("Access-Control-Allow-Origin: *");
}
add_action('init','add_cors_http_header');
function vue_wordpress_scripts()
{
    // Styles
    wp_enqueue_style( 'style.css', get_template_directory_uri() . '/style.css' );
    // wp_enqueue_style('vue_wordpress.css', get_template_directory_uri() . '/vue/dist/static/css/app.css');


    // Scripts

    // Enable For Production - Disable for Development
    // wp_enqueue_script('vue_manifest_js', get_template_directory_uri() . '/vue/dist/static/js/manifest.js', array(), null, true);
    // wp_enqueue_script('vue_vendor_js', get_template_directory_uri() . '/vue/dist/static/js/vendor.js', array(), null, true);

    // Only this script on production
    // wp_enqueue_script('vue_wordpress_js', get_template_directory_uri() . '/vue/dist/static/js/app.js', array(), null, true);

    // Enable For Development - Remove for Production
    wp_enqueue_script( 'vue_wordpress_js', 'http://localhost:8080/app.js', array(), false, true );

    wp_localize_script( 'vue_wordpress_js', 'lama_app', array(
        'nonce' => wp_create_nonce('lama_nonce_request_front'),
        'ajax_url' => admin_url('admin-ajax.php')
    ) );
}

add_action( 'wp_enqueue_scripts', 'vue_wordpress_scripts' );

add_action( 'wp_ajax_lama_get_posts', 'lama_get_posts' );
add_action( 'wp_ajax_nopriv_lama_get_posts', 'lama_get_posts');

function lama_get_posts()
{
    if(empty($_GET['nonce'])) wp_die();
    
    if (wp_verify_nonce( $_GET['nonce'], 'lama_nonce_request_front')) {
        
        $users = get_users();
        $unique = true;
        foreach($users as $user){
            if ($user->user_email == $_GET['email']) {
                $unique = false;
            }
        }
        if ($unique) {
            $res = array('unique' => true);
        } else {
            $res = array('unique' => false);
        }
        $res_encode = json_encode($res);
        echo $res_encode;
        wp_die();

    }
}