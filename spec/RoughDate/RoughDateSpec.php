<?php

namespace spec\Mareg\RoughDate;

use Mareg\RoughDate\Exception\UnrecognizedDateFormat;
use PhpSpec\ObjectBehavior;

class RoughDateSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('fromString', [(new \DateTime('today'))->format('Y-m-d')]);
    }

    function it_casts_to_string_in_string_context()
    {
        $this->__toString()->shouldBeString();
    }

    function it_should_hold_todays_date_by_default()
    {
        $this->__toString()->shouldReturn((new \DateTime('today'))->format('Y-m-d'));
    }

    function it_can_be_constructed_with_date_string()
    {
        $this->beConstructedThrough('fromString', ['2015-01-15']);

        $this->__toString()->shouldReturn('2015-01-15');
    }

    function it_can_be_constructed_from_DateTime_object()
    {
        $dateTime = new \DateTime('2015-02-15');
        $this->beConstructedThrough('fromDateTime', [$dateTime]);

        $this->__toString()->shouldReturn('2015-02-15');
    }

    function it_can_be_constructed_from_DateTimeImmutable_object()
    {
        $dateTime = new \DateTimeImmutable('2015-02-15');
        $this->beConstructedThrough('fromDateTime', [$dateTime]);

        $this->__toString()->shouldReturn('2015-02-15');
    }

    function it_throws_exception_when_date_string_format_is_not_recognised()
    {
        $this->shouldThrow(new UnrecognizedDateFormat('test'))->during('fromString', ['test']);
    }

    function it_converts_jMY_string_into_a_correct_format()
    {
        $this->beConstructedThrough('fromString', ['1. Mar 1993']);

        $this->__toString()->shouldReturn('1993-03-01');
    }

    function it_converts_MY_string_into_a_correct_format()
    {
        $this->beConstructedThrough('fromString', ['Mar 1993']);

        $this->__toString()->shouldReturn('1993-03-00');
    }

    function it_converts_Y_string_into_a_correct_format()
    {
        $this->beConstructedThrough('fromString', ['1993']);

        $this->__toString()->shouldReturn('1993-00-00');
    }

    function it_converts_Ymd_format_with_slashes_as_separator_into_a_correct_format()
    {
        $this->beConstructedThrough('fromString', ['1993/09/05']);

        $this->__toString()->shouldReturn('1993-09-05');
    }

    function it_converts_Ymd_format_with_dots_as_separator_into_a_correct_format()
    {
        $this->beConstructedThrough('fromString', ['1993.09.05']);

        $this->__toString()->shouldReturn('1993-09-05');
    }

    function it_converts_Y0000_string_into_a_correct_format()
    {
        $this->beConstructedThrough('fromString', ['1993-00-00']);

        $this->__toString()->shouldReturn('1993-00-00');
    }

    function it_converts_Ym00_string_into_a_correct_format()
    {
        $this->beConstructedThrough('fromString', ['1993-10-00']);

        $this->__toString()->shouldReturn('1993-10-00');
    }

    function it_returns_Ymd_formatted_date_string()
    {
        $today = new \DateTime('today');

        $this->format('Y-m-d')->shouldReturn($today->format('Y-m-d'));
    }

    function it_returns_Ymd_formatted_date_string_by_default()
    {
        $today = new \DateTime('today');

        $this->format()->shouldReturn($today->format('Y-m-d'));
    }

    function it_returns_jMY_formatted_date_string()
    {
        $today = new \DateTime('today');

        $this->format('j M Y')->shouldReturn($today->format('j M Y'));
    }

    function it_returns_default_formatted_string_with_zeros()
    {
        $this->beConstructedThrough('fromString', ['1993-00-00']);

        $this->format()->shouldReturn('1993-00-00');
    }

    function it_returns_jMY_formatted_date_string_without_zeros_if_day_is_not_set()
    {
        $this->beConstructedThrough('fromString', ['1993-10-00']);

        $this->format('j M Y')->shouldReturn(' Oct 1993');
    }

    function it_returns_jMY_formatted_date_string_without_zeros_if_day_and_month_are_not_set()
    {
        $this->beConstructedThrough('fromString', ['1993-00-00']);

        $this->format('j M Y')->shouldReturn('  1993');
    }

    function it_returns_DjMY_formatted_date_string()
    {
        $today = new \DateTime('today');

        $this->format('D, j M Y')->shouldReturn($today->format('D, j M Y'));
    }

    function it_returns_DjMY_formatted_date_string_with_no_day_name_when_day_is_not_set()
    {
        $this->beConstructedThrough('fromString', ['1993-10-00']);

        $this->format('D, j M Y')->shouldReturn(',  Oct 1993');
    }

    function it_returns_DjMY_formatted_date_string_with_no_day_name_when_day_and_month_are_not_set()
    {
        $this->beConstructedThrough('fromString', ['1993-00-00']);

        $this->format('D, j M Y')->shouldReturn(',   1993');
    }

    function it_returns_ljMY_formatted_date_string()
    {
        $today = new \DateTime('today');

        $this->format('l, j M Y')->shouldReturn($today->format('l, j M Y'));
    }

    function it_returns_ljMY_formatted_date_string_with_no_day_name_when_day_is_not_set()
    {
        $this->beConstructedThrough('fromString', ['1993-10-00']);

        $this->format('l, j M Y')->shouldReturn(',  Oct 1993');
    }

    function it_returns_ljMY_formatted_date_string_with_no_day_name_when_day_and_month_are_not_set()
    {
        $this->beConstructedThrough('fromString', ['1993-00-00']);

        $this->format('l, j M Y')->shouldReturn(',   1993');
    }

    function it_returns_jFY_formatted_date_string()
    {
        $today = new \DateTime('today');

        $this->format('j F Y')->shouldReturn($today->format('j F Y'));
    }

    function it_is_JsonSeriazable()
    {
        $this->shouldBeAnInstanceOf(\JsonSerializable::class);
    }

    function it_returns_date_string_when_serialised()
    {
        $today = new \DateTime('today');

        $this->jsonSerialize()->shouldReturn($today->format('Y-m-d'));
    }
}
