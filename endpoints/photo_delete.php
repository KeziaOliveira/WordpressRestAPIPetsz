  <?php

  function api_photo_delete($request) {
    // Solicitando ID do autor e do usuário
    $post_id = $request['id'];
    $user = wp_get_current_user();
    $post = get_post($post_id);
    $author_id = (int) $post->post_author;
    $user_id = (int) $user->ID;
    
    // Verifica se o user ID é diferente do author ID e se o post existe
    if ($user_id !== $author_id || !isset($post)) {
      $response = new WP_Error('error', 'Usuário não possui permissão', ['status' => 401]);
      return rest_ensure_response($response);
    }

    // Deleta o attachment da imagem
    $attachment_id = get_post_meta($post_id,'img', true);
    wp_delete_attachment($attachment_id, true);
    // Deleta o post que não cumprir a condição
    wp_delete_post($post_id, true);

    return rest_ensure_response('Post deletado!');
  }

  function register_api_photo_delete() {
    register_rest_route('api', '/photo/(?P<id>[0-9]+)', [
      // Aqui deve ser DELETABLE, para deletar
      'methods' => WP_REST_Server::DELETABLE,
      'callback' => 'api_photo_delete',
    ]);
  }
  add_action('rest_api_init', 'register_api_photo_delete');

  ?>