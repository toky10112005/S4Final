<?php

use App\Filters\RoleFilter;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class RoleFilterTest extends CIUnitTestCase
{
    public function testOperatorSessionRoleIsAccepted(): void
    {
        $session = service('session');
        $session->set('Client', ['role' => 'operateur']);

        $filter = new RoleFilter();
        $request = service('request');

        $result = $filter->before($request, ['operateur']);

        $this->assertNull($result);
    }
}
