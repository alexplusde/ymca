<?php $route = new \rex_yform_rest_route(array(
  'path' => '/v1/rex_event_category/',
  'auth' => '\\rex_yform_rest_auth_token::checkToken',
  'type' => 'rex_event_category::class',
  'query' => 'rex_event_category::query()',
  'get' =>
  array(
    'fields' =>
    array(
      'rex_event_category' =>
      array(
        0 => 'name',
        1 => 'icon',
        2 => 'teaser',
        3 => 'description',
        4 => 'images',
        5 => 'status',
        6 => 'createuser',
        7 => 'updateuser',
      ),
    ),
  ),
  'post' =>
  array(
    'fields' =>
    array(
      'rex_event_category' =>
      array(
        0 => 'name',
        1 => 'icon',
        2 => 'teaser',
        3 => 'description',
        4 => 'images',
        5 => 'status',
        6 => 'createuser',
        7 => 'updateuser',
      ),
    ),
  ),
  'delete' =>
  array(
    'fields' =>
    array(
      'rex_event_category' =>
      array(
        0 => 'id',
      ),
    ),
  ),
));
