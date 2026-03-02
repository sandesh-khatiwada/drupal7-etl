<?php

/**
 * Preprocess deferred_item nodes.
 */
function myzen_preprocess_node(&$variables) {

  if ($variables['node']->type !== 'deferred_item') {
    return;
  }

  $node = $variables['node'];

  // Remove Reminder Sent field completely
  unset($variables['content']['field_reminder_sent']);

  // Safely get status
  $status = '';
  if (!empty($node->field_learning_status[LANGUAGE_NONE][0]['value'])) {
    $status = $node->field_learning_status[LANGUAGE_NONE][0]['value'];
  }

  $variables['learning_status'] = $status;

  // Reminder Date formatting
  if (!empty($node->field_reminder_date[LANGUAGE_NONE][0]['value'])) {
    $reminder_raw = $node->field_reminder_date[LANGUAGE_NONE][0]['value'];
    $timestamp = strtotime($reminder_raw);
    $variables['next_reminder_formatted'] = format_date($timestamp, 'medium');
  }
  else {
    $variables['next_reminder_formatted'] = '';
  }

  // Build action buttons
  $actions = array();

  if ($status === 'Pending') {
    $actions[] = l(
      'Mark as Learned',
      "deferred-item/{$node->nid}/mark-learned",
      array('attributes' => array('class' => array('btn', 'btn-success')))
    );
  }

  if ($status === 'Learned') {
    $actions[] = l(
      'Add Reflection Log',
      "deferred-item/{$node->nid}/reflection",
      array('attributes' => array('class' => array('btn', 'btn-primary')))
    );

    $actions[] = l(
      'Mark as Dropped',
      "deferred-item/{$node->nid}/drop",
      array('attributes' => array('class' => array('btn', 'btn-danger')))
    );
  }

  $variables['action_buttons'] = $actions;
}


/**
 * Implements hook_theme().
 */
function myzen_theme($existing, $type, $theme, $path) {
  return array(
    'user_login' => array(
      'render element' => 'form',
      'path' => drupal_get_path('theme', 'myzen') . '/templates',
      'template' => 'user-login',
    ),
    'user_register_form' => array(
      'render element' => 'form',
      'path' => drupal_get_path('theme', 'myzen') . '/templates',
      'template' => 'user-register-form',
    ),
    'user_pass' => array(
      'render element' => 'form',
      'path' => drupal_get_path('theme', 'myzen') . '/templates',
      'template' => 'user-pass',
    ),
  );
}


/**
 * Redirect after successful login.
 */
function myzen_form_user_login_alter(&$form, &$form_state, $form_id) {
  $form['#submit'][] = 'myzen_user_login_custom_submit';
}

function myzen_user_login_custom_submit($form, &$form_state) {
  global $user;

  if ($user->uid > 0) {
    $form_state['redirect'] = 'home';
    unset($_GET['destination']);
  }
}


/**
 * Redirect after password set / profile save.
 */
function myzen_form_user_profile_form_alter(&$form, &$form_state, $form_id) {
  $form['#submit'][] = 'myzen_verification_redirect_submit';
}

function myzen_verification_redirect_submit($form, &$form_state) {
  global $user;

  if ($user->uid > 0) {
    $form_state['redirect'] = 'home';
  }
}