<?php
// Remove as rotas definidas pelo WordPress
remove_action('rest_api_init', 'create_initial_rest_routes', 99);

// Linca a página do user no endpoints
$dirbase = get_template_directory();
require_once $dirbase . '/endpoints/user_post.php';

// Modifica o prefixo da API de wp-json para json apenas
// Necessário salvar os permalinks para dar um refresh nos URL's
function change_api($slug) {
  return 'json';
}
add_filter('rest_url_prefix', 'change_api');
add_action('rest_api_init', function () {
  $routes = rest_get_server()->get_routes();
  error_log(print_r($routes, true));
});

// Tempo em que expira o token do JWT (No caso em 24h) - Assim o usuário precisa fazer o login no tempo determinado.
function expire_token() {
  return time() + (60 * 60 * 24)
}
add_action('jwt_auth_expire', 'expire_token');

?>
