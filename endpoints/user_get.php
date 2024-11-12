<?php

function api_user_get($request) {
  // Acessando dados do usuário
  $user = wp_get_current_user();
  $user_id = $user->ID;

  // Passar erro para usuário não logado ou sem permissão
  if($user_id === 0) {
    $response = new WP_Error('error', 'Usuário não possui permissão', ['status' => 401]);
    return rest_ensure_response($response);
  }

  $response = [
    'id' => $user_id,
    'username' => $user->$user_login,
    'nome' => $user->dispay_name,
    'email' => $user->user_email,
  ];
  
  return rest_ensure_response($response);
}

function register_api_user_get() {
  register_rest_route('api', '/user', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'api_user_get',
  ]);
}
add_action('rest_api_init', 'register_api_user_get');

?>