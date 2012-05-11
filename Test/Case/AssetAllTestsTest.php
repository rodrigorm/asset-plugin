<?php
class AssetAllTestsTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('Asset All Tests');
        $suite->addTestDirectoryRecursive(App::pluginPath('Asset') . 'Test' . DS . 'Case' . DS);
        return $suite;
    }
}