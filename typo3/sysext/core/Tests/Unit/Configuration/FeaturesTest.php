<?php
namespace TYPO3\CMS\Core\Tests\Unit\Configuration;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\Configuration\Features;

/**
 * Test case
 */
class FeaturesTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @test
     */
    public function nonExistingFeatureReturnsFalse()
    {
        $features = new Features();
        $result = $features->isFeatureEnabled('nonExistingFeature');
        $this->assertEquals(false, $result);
    }

    /**
     * @test
     */
    public function checkIfExistingDisabledFeatureIsDisabled()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['nativeFunctionality'] = false;
        $features = new Features();
        $result = $features->isFeatureEnabled('nativeFunctionality');
        $this->assertEquals(false, $result);
    }

    /**
     * @test
     */
    public function checkIfExistingEnabledFeatureIsEnabled()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['nativeFunctionality'] = true;
        $features = new Features();
        $result = $features->isFeatureEnabled('nativeFunctionality');
        $this->assertEquals(true, $result);
    }

    /**
     * @test
     */
    public function checkIfExistingEnabledFeatureIsDisabled()
    {
        $GLOBALS['TYPO3_CONF_VARS']['SYS']['features']['nativeFunctionality'] = false;
        $features = new Features();
        $result = $features->isFeatureEnabled('nativeFunctionality');
        $this->assertEquals(false, $result);
    }
}
