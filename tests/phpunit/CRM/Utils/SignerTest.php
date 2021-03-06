<?php
/*
 +--------------------------------------------------------------------+
| CiviCRM version 4.7                                                |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2015                                |
+--------------------------------------------------------------------+
| This file is a part of CiviCRM.                                    |
|                                                                    |
| CiviCRM is free software; you can copy, modify, and distribute it  |
| under the terms of the GNU Affero General Public License           |
| Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
|                                                                    |
| CiviCRM is distributed in the hope that it will be useful, but     |
| WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
| See the GNU Affero General Public License for more details.        |
|                                                                    |
| You should have received a copy of the GNU Affero General Public   |
| License and the CiviCRM Licensing Exception along                  |
| with this program; if not, contact CiviCRM LLC                     |
| at info[AT]civicrm[DOT]org. If you have questions about the        |
| GNU Affero General Public License or the licensing of CiviCRM,     |
| see the CiviCRM license FAQ at http://civicrm.org/licensing        |
+--------------------------------------------------------------------+
 */

/**
 * Class CRM_Utils_SignerTest
 */
class CRM_Utils_SignerTest extends CiviUnitTestCase {

  public function setUp() {
    parent::setUp();
  }

  public function testSignValidate() {
    $cases = array();
    $cases[] = array(
      'signParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => NULL,
      ),
      'validateParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => NULL,
      ),
      'isValid' => TRUE,
    );
    $cases[] = array(
      'signParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => NULL,
      ),
      'validateParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => NULL,
        'irrelevant' => 'totally-irrelevant',
      ),
      'isValid' => TRUE,
    );
    $cases[] = array(
      'signParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => NULL,
      ),
      'validateParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => '',
      ),
      'isValid' => TRUE,
    );
    $cases[] = array(
      'signParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => NULL,
      ),
      'validateParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => 0,
      ),
      'isValid' => FALSE,
    );
    $cases[] = array(
      'signParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => 0,
      ),
      'validateParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => NULL,
      ),
      'isValid' => FALSE,
    );
    $cases[] = array(
      'signParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => NULL,
      ),
      'validateParams' => array(
        'a' => 'eh',
        'b' => 'bay',
        'c' => NULL,
      ),
      'isValid' => FALSE,
    );
    $cases[] = array(
      'signParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => NULL,
      ),
      'validateParams' => array(
        'a' => 'eh',
        'b' => 'bee',
        'c' => FALSE,
      ),
      'isValid' => FALSE,
    );
    $cases[] = array(
      'signParams' => array(
        'a' => 1, // int
        'b' => 'bee',
      ),
      'validateParams' => array(
        'a' => '1', // string
        'b' => 'bee',
      ),
      'isValid' => TRUE,
    );

    foreach ($cases as $caseId => $case) {
      require_once 'CRM/Utils/Signer.php';
      $signer = new CRM_Utils_Signer('secret', array('a', 'b', 'c'));
      $signature = $signer->sign($case['signParams']);
      $this->assertTrue(!empty($signature) && is_string($signature)); // arbitrary

      $validator = new CRM_Utils_Signer('secret', array('a', 'b', 'c')); // same as $signer but physically separate
      $isValid = $validator->validate($signature, $case['validateParams']);

      if ($isValid !== $case['isValid']) {
        $this->fail("Case ${caseId}: Mismatch: " . var_export($case, TRUE));
      }
      $this->assertTrue(TRUE, 'Validation yielded expected result');
    }
  }

}
