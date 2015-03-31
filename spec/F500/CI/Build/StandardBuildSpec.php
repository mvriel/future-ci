<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Build;

use F500\CI\Suite\Suite;
use F500\CI\Task\Task;
use F500\CI\Vcs\Commit;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class StandardBuildSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Build
 */
class StandardBuildSpec extends ObjectBehavior
{
    const PROJECT_FOLDER = '/our/project/folder';

    protected $suiteJson = <<< EOT
{
    "suite": {
        "name": "Some Suite",
        "cn": "some_suite",
        "class": "F500\\\\CI\\\\Suite\\\\StandardSuite"
    },
    "build": {
        "class": "F500\\\\CI\\\\Build\\\\StandardBuild"
    }
}
EOT;

    function let(Suite $suite, Task $task)
    {
        $suite->getCn()->willReturn('some_suite');
        $suite->getConfig()->willReturn(array('root_dir' => self::PROJECT_FOLDER));
        $suite->getName()->willReturn('Some Suite');
        $suite->getTasks()->willReturn(array('some_task' => $task));
        $suite->toJson()->willReturn($this->suiteJson);

        /** @noinspection PhpParamsInspection */
        $this->beConstructedWith($suite, '/path/to/builds');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Build\StandardBuild');
        $this->shouldImplement('F500\CI\Build\Build');
    }

    function it_has_a_cn()
    {
        $this->getCn()->shouldMatch('/^[a-z0-9]+$/');
    }

    function it_has_a_date()
    {
        $this->getDate()->shouldHaveType('DateTimeImmutable');
    }

    function it_has_a_suite_cn()
    {
        $this->getSuiteCn()->shouldReturn('some_suite');
    }

    function it_has_a_suite_name()
    {
        $this->getSuiteName()->shouldReturn('Some Suite');
    }

    function it_has_a_build_dir()
    {
        $this->getBuildDir()->shouldMatch('|^/path/to/builds/some_suite/[a-z0-9]+$|');
    }

    function it_has_a_project_dir()
    {
        $this->getProjectDir()->shouldReturn(self::PROJECT_FOLDER);
    }

    function it_has_a_build_dir_for_a_task(Task $task)
    {
        $task->getCn()->willReturn('some_task');

        $this->getBuildDir($task)->shouldMatch('|^/path/to/builds/some_suite/[a-z0-9]+/some_task$|');
    }

    function it_has_tasks(Task $task)
    {
        $this->getTasks()->shouldReturn(array('some_task' => $task));
    }

    function it_can_optionally_have_a_commit_with_which_it_was_started(Commit $commit)
    {
        $this->getCommit()->shouldReturn(null);

        $this->initiatedBy($commit)->shouldReturn($this);

        $this->getCommit()->shouldReturn($commit);
    }

    function it_turns_itself_into_json()
    {
        $this->toJson()->shouldReturn($this->suiteJson);
    }
}
