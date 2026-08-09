<?php

namespace spec\Mareg\RoughDate\Helper;

use Mareg\RoughDate\Exception\UnrecognizedDateFormat;
use PhpSpec\ObjectBehavior;

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

    function it_converts_MjY_string_into_a_correct_format()
    {
        $this->normalize('Mar 8, 1993')->shouldReturn('1993-03-08');
    }

    function it_converts_MY_string_into_a_correct_format()
    {
        $this->normalize('Mar 1993')->shouldReturn('1993-03-00');
    }

    function it_converts_FY_string_into_a_correct_format()
    {
        $this->normalize('March 1993')->shouldReturn('1993-03-00');
    }

    function it_converts_jFY_string_into_a_correct_format()
    {
        $this->normalize('1 March 1993')->shouldReturn('1993-03-01');
        $this->normalize('1. March 1993')->shouldReturn('1993-03-01');
    }

    function it_converts_FjY_string_into_a_correct_format()
    {
        $this->normalize('March 1, 1993')->shouldReturn('1993-03-01');
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

    function it_converts_Ym_string_with_dash_into_a_correct_format()
    {
        $this->normalize('1993-10')->shouldReturn('1993-10-00');
    }

    function it_converts_Ym_string_with_slash_into_a_correct_format()
    {
        $this->normalize('1993/10')->shouldReturn('1993-10-00');
    }

    function it_converts_Ym_string_with_dot_into_a_correct_format()
    {
        $this->normalize('1993.10')->shouldReturn('1993-10-00');
    }

    function it_accepts_Y00_string_as_a_year_precision_date()
    {
        $this->normalize('1993-00')->shouldReturn('1993-00-00');
    }

    function it_accepts_month_names_in_any_case_and_common_abbreviations()
    {
        $this->normalize('23 Sept 1925')->shouldReturn('1925-09-23');
        $this->normalize('23 nov 1925')->shouldReturn('1925-11-23');
        $this->normalize('23 NOVEMBER 1925')->shouldReturn('1925-11-23');
        $this->normalize('Sept 1925')->shouldReturn('1925-09-00');
    }

    function it_accepts_a_leap_day_in_a_leap_year()
    {
        $this->normalize('2016-02-29')->shouldReturn('2016-02-29');
    }

    function it_rejects_impossible_calendar_dates()
    {
        $this->shouldThrow(new UnrecognizedDateFormat('2015-02-30'))->during('normalize', ['2015-02-30']);
        $this->shouldThrow(new UnrecognizedDateFormat('1993-02-29'))->during('normalize', ['1993-02-29']);
        $this->shouldThrow(new UnrecognizedDateFormat('1993-01-32'))->during('normalize', ['1993-01-32']);
        $this->shouldThrow(new UnrecognizedDateFormat('31 February 1993'))->during('normalize', ['31 February 1993']);
    }

    function it_rejects_a_month_outside_the_calendar_range()
    {
        $this->shouldThrow(new UnrecognizedDateFormat('1993-13'))->during('normalize', ['1993-13']);
        $this->shouldThrow(new UnrecognizedDateFormat('1993-13-45'))->during('normalize', ['1993-13-45']);
        $this->shouldThrow(new UnrecognizedDateFormat('1993-15-01'))->during('normalize', ['1993-15-01']);
    }

    function it_rejects_a_known_day_inside_an_unknown_month()
    {
        $this->shouldThrow(new UnrecognizedDateFormat('1993-00-15'))->during('normalize', ['1993-00-15']);
    }

    function it_rejects_a_pipe_as_a_separator()
    {
        $this->shouldThrow(new UnrecognizedDateFormat('1993|09|05'))->during('normalize', ['1993|09|05']);
        $this->shouldThrow(new UnrecognizedDateFormat('1993|10'))->during('normalize', ['1993|10']);
    }

    function it_rejects_mixed_separators()
    {
        $this->shouldThrow(new UnrecognizedDateFormat('1993/09-05'))->during('normalize', ['1993/09-05']);
        $this->shouldThrow(new UnrecognizedDateFormat('1993.09-05'))->during('normalize', ['1993.09-05']);
        $this->shouldThrow(new UnrecognizedDateFormat('1993-09.05'))->during('normalize', ['1993-09.05']);
    }

    function it_throws_unrecognized_date_format_when_the_month_name_is_not_a_month()
    {
        $this->shouldThrow(new UnrecognizedDateFormat('99 Foo 1993'))->during('normalize', ['99 Foo 1993']);
        $this->shouldThrow(new UnrecognizedDateFormat('Foo 1993'))->during('normalize', ['Foo 1993']);
    }
}
