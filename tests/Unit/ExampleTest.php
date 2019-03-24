<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        /**
         * Test customer & division
         * @var \App\Division $d
         */
        $d = factory(\App\Division::class)->create();

        $c = factory(\App\Customer::class)->create([
            'division_id' => $d->id
        ]);

        $this->assertEquals($d->id, $c->division->id);

        /**
         * Test customer & project
         */
        $p = factory(\App\Project::class)->create([
            'customer_id' => $c->id
        ]);

        $this->assertEquals($c->id, $p->customer->id);

        /**
         * Test project & activities
         */
        $a = factory(\App\Activity::class)->create([
            'project_id' => $p->id
        ]);

        $this->assertEquals($p->id, $a->project->id);

        /**
         * Test entry & user
         */
        /**
         * @var \App\User $u
         */
        $u = factory(\App\User::class)->create();

        $e = factory(\App\Entry::class)->create([
            'user_id' => $u->id
        ]);

        $this->assertEquals($u->id, $e->user->id);

        /**
         * Test entry & activities
         */
        $e->fill([
            'activity_id' => $a->id
        ])->save();
        
        $this->assertEquals($a->id, $e->fresh()->activity->id);

        /**
         * Test activity has entries
         */
        $this->assertEquals($a->entries->first()->id, $e->id);

        /**
         * User & division
         */
        //dd($d->getAttribute('name'));
        $this->assertEquals($d->getAttribute('name'), $u->division()['name']);

        //dd($u->division());

        // YES XD eindelijk
        // ik heb alles gezien, bijna niets begrepen xd

        /**
         * Test dat een user project(en) heeft
         */
        // entries => user_id, activity_id
        // activity => project_id

        // user heeft projecten door middel van entries & activities



        /**
         * Test dat een user een division heeft
         * 
         * Een user heeft een division door middel van het project waar hij aan werkt ?
         * en dat project behoort tot een customer, en de customer behoort tot een division
         * aah zo afleiden dus xd nu ben ik mee
         */

    }
}
