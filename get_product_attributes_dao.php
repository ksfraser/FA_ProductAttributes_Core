<?php

//Grok 5 created the following which is way overkill!

    /**
     * Get a ProductAttributesDao instance
     * @return \Ksfraser\FA_ProductAttributes\Dao\ProductAttributesDao
     */
    private function get_product_attributes_dao() {
        static $dao = null;
        if ($dao === null) {
            // Ensure autoloader is loaded
            self::ensure_autoloader_loaded();

            // Try to load required classes directly if autoloader fails
            if (!class_exists('\Ksfraser\ModulesDAO\Db\DbAdapterInterface')) {
                $interface_path = __DIR__ . '/vendor/ksfraser/ksf-modules-dao/src/Db/DbAdapterInterface.php';
                if (file_exists($interface_path)) {
                    require_once $interface_path;
                }
            }

            // Debug: Check if class exists
            if (!class_exists('\Ksfraser\ModulesDAO\Db\FrontAccountingDbAdapter')) {
                $autoloader_path = __DIR__ . '/vendor/autoload.php';
                error_log("FA_ProductAttributes: FrontAccountingDbAdapter class not found after autoloader");
                
                // Try to load the class directly
                $direct_path = __DIR__ . '/vendor/ksfraser/ksf-modules-dao/src/Db/FrontAccountingDbAdapter.php';
                if (file_exists($direct_path)) {
                    error_log("FA_ProductAttributes: Trying to load FrontAccountingDbAdapter directly from: " . $direct_path);
                    require_once $direct_path;
                }
                
                if (!class_exists('\Ksfraser\ModulesDAO\Db\FrontAccountingDbAdapter')) {
                    throw new \Exception("FrontAccountingDbAdapter class not found. Check autoloader path: " . $autoloader_path);
                }
            }

            // Create database adapter directly (avoid factory issues)
            $db_adapter = new \Ksfraser\ModulesDAO\Db\FrontAccountingDbAdapter();

            // Check if ProductAttributesDao exists
            if (!class_exists('\Ksfraser\FA_ProductAttributes\Dao\ProductAttributesDao')) {
                $dao_path = __DIR__ . '/vendor/ksfraser/fa-product-attributes/src/Ksfraser/FA_ProductAttributes/Dao/ProductAttributesDao.php';
                if (file_exists($dao_path)) {
                    require_once $dao_path;
                } else {
                    throw new \Exception("ProductAttributesDao class not found at: " . $dao_path);
                }
            }

            // Create DAO
            $dao = new \Ksfraser\FA_ProductAttributes\Dao\ProductAttributesDao($db_adapter, null);
        }
        return $dao;
    }
