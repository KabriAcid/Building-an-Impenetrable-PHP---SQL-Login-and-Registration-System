<?php
// I created this model file to handle data for the plugin

class ModelExtensionAdvancedSecurity extends Model {
    public function editSetting($code, $data, $store_id = 0) {
        // I updated the setting in the database
        $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = '" . $this->db->escape($code) . "' AND `store_id` = '" . (int)$store_id . "'");

        foreach ($data as $key => $value) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET `store_id` = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
        }
    }
}
?>
