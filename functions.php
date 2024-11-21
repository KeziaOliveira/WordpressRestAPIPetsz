<?php
// Remove as rotas definidas pelo WordPress
// remove_action('rest_api_init', 'create_initial_rest_routes', 99);

// Remove endpoins específicos no wordpress por questão de segurança
add_filter('rest_endpoints', function ($endpoints) {
  unset($endpoints['/wp/v2/users']);
  unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
  return $endpoints;
});

// Linca a página do user no endpoints
$dirbase = get_template_directory();
require_once $dirbase . '/endpoints/user_post.php';
require_once $dirbase . '/endpoints/user_get.php';

require_once $dirbase . '/endpoints/photo_delete.php';
require_once $dirbase . '/endpoints/photo_post.php';
require_once $dirbase . '/endpoints/photo_get.php';

require_once $dirbase . '/endpoints/comment_post.php';
require_once $dirbase . '/endpoints/comment_get.php';

require_once $dirbase . '/endpoints/password.php';

require_once $dirbase . '/endpoints/stats_get.php';

// Torna imagem em tamanho padrão
update_option('large_size_w', 1000);
update_option('large_size_h', 1000);
update_option('large_crop', 1);

// Modifica o prefixo da API de wp-json para json apenas
// Necessário salvar os permalinks para dar um refresh nos URL's
function change_api() {
  return 'json';
}
add_filter('rest_url_prefix', 'change_api',);

// Tempo em que expira o token do JWT (No caso em 24h) - Assim o usuário precisa fazer o login no tempo determinado.
// function expire_token() {
//   return time() + (60 * 60 * 24)
// };
// add_action('jwt_auth_expire', 'expire_token');

?>
