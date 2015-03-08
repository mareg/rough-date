<?php

namespace spec\RoughDate\Helper;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RoughDate\Exception\UnrecognizedDateFormat;

class StringDateNormalizerSpec extends ObjectBehavior
{
    function it_returns_same_string_for_correct_ISO_formatted_date()
    {
        $this->normalize('2015-03-08')->shouldReturn('2015-03-08');
    }

    function it_should_throw_exception_when_date_format_is_not_recognised()
    {
        $this->shouldThrow(new UnrecognizedDateFormat('test'))->during('normalize', ['test']);
    }

    function it_converts_jMY_string_into_a_correct_format()
    {
        $this->normalize('8. Mar 1993')->shouldReturn('1993-03-08');
        $this->normalize('8 Mar 1993')->shouldReturn('1993-03-08');
    }

    function it_converts_MY_string_into_a_correct_format()
    {
        $this->normalize('Mar 1993')->shouldReturn('1993-03-00');
    }

    function it_converts_Y_string_into_a_correct_format()
    {
        $this->normalize('1993')->shouldReturn('1993-00-00');
    }

    function it_converts_Ymd_format_with_slashes_as_separator_into_a_correct_format()
    {
        $this->normalize('1993/09/05')->shouldReturn('1993-09-05');
    }

    function it_converts_Ymd_format_with_dots_as_separator_into_a_correct_format()
    {
        $this->normalize('1993.09.05')->shouldReturn('1993-09-05');
    }

    function it_accepts_Y0000_string_as_a_correct_format()
    {
        $this->normalize('1993-00-00')->shouldReturn('1993-00-00');
    }

    function it_accepts_Ym00_string_as_a_correct_format()
    {
        $this->normalize('1993-10-00')->shouldReturn('1993-10-00');
    }
}
