<?php
/* ========================== FILE INFORMATION ================================= 
phxEventManager :: class-user.php

User management class. 
============================================================================= */

class PEM_User 
{
   var $data;
   var $id = 0;
   var $caps = array();
   var $cap_key;
   var $roles = array();
   var $allcaps = array();

   function PEM_User($id, $name = "") 
   {
     global $table_prefix;

     if (empty($id) AND empty($name)) return;

     if (!is_numeric($id)) 
     {
      $name = $id;
      $id = 0;
     }

     if (!empty($id))
      $this->data = get_userdata($id);
     else
      $this->data = get_userdatabylogin($name);

     if ( empty($this->data->ID) )
      return;

     foreach (get_object_vars($this->data) as $key => $value) {
      $this->{$key} = $value;
     }

     $this->id = $this->ID;
     $this->cap_key = $table_prefix . 'capabilities';
     $this->caps = &$this->{$this->cap_key};
     if ( ! is_array($this->caps) )
      $this->caps = array();
     $this->get_role_caps();
   }
   
   function get_role_caps() {
     global $pem_roles;
     
     if ( ! isset($pem_roles) )
      $pem_roles = new PEM_Roles();

     //Filter out caps that are not role names and assign to $this->roles
     if(is_array($this->caps))
      $this->roles = array_filter(array_keys($this->caps), array(&$pem_roles, 'is_role'));

     //Build $allcaps from role caps, overlay user's $caps
     $this->allcaps = array();
     foreach($this->roles as $role) {
      $role = $pem_roles->get_role($role);
      $this->allcaps = array_merge($this->allcaps, $role->capabilities);
     }
     $this->allcaps = array_merge($this->allcaps, $this->caps);
   }
   
   function add_role($role) {
     $this->caps[$role] = true;
     update_usermeta($this->id, $this->cap_key, $this->caps);
     $this->get_role_caps();
     $this->update_user_level_from_caps();
   }
   
   function remove_role($role) {
     if ( empty($this->roles[$role]) || (count($this->roles) <= 1) )
      return;
     unset($this->caps[$role]);
     update_usermeta($this->id, $this->cap_key, $this->caps);
     $this->get_role_caps();
   }
   
   function set_role($role) {
     foreach($this->roles as $oldrole) 
      unset($this->caps[$oldrole]);
     $this->caps[$role] = true;
     $this->roles = array($role => true);
     update_usermeta($this->id, $this->cap_key, $this->caps);
     $this->get_role_caps();
     $this->update_user_level_from_caps();
   }

   function level_reduction($max, $item) {
      if(preg_match('/^level_(10|[0-9])$/i', $item, $matches)) {
        $level = intval($matches[1]);
        return max($max, $level);
      } else {
        return $max;
      }
   }
   
   function update_user_level_from_caps() {
      global $table_prefix;
      $this->user_level = array_reduce(array_keys($this->allcaps),    array(&$this, 'level_reduction'), 0);
      update_usermeta($this->id, $table_prefix.'user_level', $this->user_level);
   }
   
   function add_cap($cap, $grant = true) {
     $this->caps[$cap] = $grant;
     update_usermeta($this->id, $this->cap_key, $this->caps);
   }

   function remove_cap($cap) {
     if ( empty($this->caps[$cap]) ) return;
     unset($this->caps[$cap]);
     update_usermeta($this->id, $this->cap_key, $this->caps);
   }
   
   //has_cap(capability_or_role_name) or
   //has_cap('edit_post', post_id)
   function has_cap($cap) {
     if ( is_numeric($cap) )
      $cap = $this->translate_level_to_cap($cap);
     
     $args = array_slice(func_get_args(), 1);
     $args = array_merge(array($cap, $this->id), $args);
     $caps = call_user_func_array('map_meta_cap', $args);
     // Must have ALL requested caps
     $capabilities = apply_filters('user_has_cap', $this->allcaps, $caps, $args);
     foreach ($caps as $cap) {
      //echo "Checking cap $cap<br/>";
      if(empty($capabilities[$cap]) || !$capabilities[$cap])
      return false;
     }

     return true;
   }

   function translate_level_to_cap($level) {
     return 'level_' . $level;
   }

}

?>
