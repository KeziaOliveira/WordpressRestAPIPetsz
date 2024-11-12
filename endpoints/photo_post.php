<?php

function api_photo_post($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if($user_id === 0) {
    $response = new WP_Error('error', 'Usuário não possui permissão', ['status' => 401]);
    return rest_ensure_response($response);
  }

  // Variável para cada item que vai vir do request
  $nome = sanitize_text_field($request['nome']);
  $peso = sanitize_text_field($request['peso']);
  $idade = sanitize_text_field($request['idade']);
  $files = $request-> get_file_params();

  // Verifica para não receber dados vazio
  if(empty($nome) || empty($peso) || empty($idade) || empty($files)) {
    $response = new WP_Error('error', 'Dados incompletos', ['status' => 422]);
    return rest_ensure_response($response);
    error_log(print_r($files, true));

  }
  
  // Cada post vai ser uma nova foto com os seguintes dados:
  $response = [
    'post_author' => $user_id,
    'post_type' => 'post',
    'post_status' => 'publish',
    'post_title' => $nome,
    'post_content' => $nome,
    'files' => $files,
    // Metafields = outros valores
    'meta_input' => [
      'peso' => $peso,
      'idade' => $idade,
      'acessos' => 0,
    ],
  ];

  // Retorna o ID  interno do post
  $post_id = wp_insert_post($response);
  
  // O media_handle_upload é uma fação pesada e por isso é preciso requerer uma parte específica do WP para lhe dar com o upload de imagem
  require_once ABSPATH . 'wp-admin/includes/image.php';
  require_once ABSPATH . 'wp-admin/includes/file.php';
  require_once ABSPATH . 'wp-admin/includes/media.php';

  // No WP não é inserida a imagem no post, mas sim na parte de gerenciamento de arquivos externos, por isso o ID interno associado ao post
  $photo_id = media_handle_upload('img', $post_id);
  // Atualizar com novo metaimput para ele ter uma relação da ID da imagem com o post
  update_post_meta($post_id, 'img', $photo_id);
  
  return rest_ensure_response($response);
}

function register_api_photo_post() {
  register_rest_route('api', '/photo', [
    // Requisições post são CREATABLE
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'api_photo_post',
  ]);
}
add_action('rest_api_init', 'register_api_photo_post');

?>