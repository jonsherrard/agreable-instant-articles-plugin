<?php namespace AgreableInstantArticlesPlugin;

/** @var \Herbert\Framework\Panel $panel */

\add_action('plugins_loaded', function() use ($panel) {
  $user = wp_get_current_user();
  $user_roles = $user->roles;

  if (in_array('instant_articles_editor', $user_roles) || in_array('administrator',$user_roles)) {
    $panel->add([
        'type'   => 'panel',
        'as'     => 'instantArticlesPanel',
        'title'  => 'Instant Articles',
        'slug'   => 'instant-articles-index',
        'icon'   => 'dashicons-facebook',
        'uses'   => __NAMESPACE__ . '\Controllers\PanelController@index'
    ]);
  }
});
