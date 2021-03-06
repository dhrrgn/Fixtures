<?php declare(strict_types=1);

namespace DavidBadura\Fixtures\Fixture;

use DavidBadura\Fixtures\TestObjects\Group;
use DavidBadura\Fixtures\TestObjects\Role;
use DavidBadura\Fixtures\TestObjects\User;
use PHPUnit\Framework\TestCase;

/**
 * @author David Badura <d.badura@gmx.de>
 */
class FixtureCollectionTest extends TestCase
{
    public function testCreateFixtures()
    {
        $data = [
            'user' =>
            [
                'properties' =>
                [
                    'class' => User::class,
                    'constructor' => ['name', 'email'],
                ],
                'data' =>
                [
                    'david' =>
                    [
                        'name' => 'David Badura',
                        'email' => 'd.badura@gmx.de',
                        'group' => ['@group:owner', '@group:developer'],
                        'role' => ['@role:admin'],
                    ],
                    'other' =>
                    [
                        'name' => 'Somebody',
                        'email' => 'test@example.de',
                        'group' => ['@group:developer'],
                        'role' => ['@role:user'],
                    ],
                ],
            ],
            'group' =>
            [
                'properties' =>
                [
                    'class' => Group::class,
                    'tags' => ['install', 'test'],
                ],
                'data' =>
                [
                    'developer' =>
                    [
                        'name' => 'Developer',
                        'leader' => '@@user:david',
                    ],
                ],
            ],
            'role' =>
            [
                'properties' =>
                [
                    'class' => Role::class,
                    'tags' => ['test'],
                ],
                'data' =>
                [
                    'admin' =>
                    [
                        'name' => 'Admin',
                    ],
                    'user' =>
                    [
                        'name' => 'User',
                    ],
                ],
            ],
        ];

        $fixtures = FixtureCollection::create($data);

        $this->assertEquals(3, count($fixtures));

        $this->assertEquals('user', $fixtures->get('user')->getName());
        $this->assertEquals('group', $fixtures->get('group')->getName());
        $this->assertEquals('role', $fixtures->get('role')->getName());

        $this->assertEquals('default', $fixtures->get('user')->getConverter());
        $this->assertEquals('default', $fixtures->get('group')->getConverter());
        $this->assertEquals('default', $fixtures->get('role')->getConverter());

        $this->assertEquals(new ParameterBag([
            'class' => User::class,
            'constructor' => ['name', 'email'],
            ]), $fixtures->get('user')->getProperties());

        $this->assertEquals(
            new ParameterBag([
            'class' => Group::class,
            'tags' => ['install', 'test'],
            ]),
            $fixtures->get('group')->getProperties()
        );

        $this->assertEquals(
            new ParameterBag([
            'class' => Role::class,
            'tags' => ['test'],
            ]),
            $fixtures->get('role')->getProperties()
        );
    }
}
