<?php

namespace spec\RoughDate\Helper;

use PhpSpec\ObjectBehavior;
use RoughDate\RoughDate;
use RoughDate\Exception\UnrecognizedDateFormat;

class StringDateFormatterSpec extends ObjectBehavior
{
    const DATE = '1993-03-08';

    function let()
    {
        $this->beConstructedThrough('fromString', [self::DATE]);
    }

    function it_can_be_created_from_string()
    {
        $this->beConstructedThrough('fromString', ['1993-07-12']);

        $this->format()->shouldReturn('1993-07-12');
    }

    function it_returns_Ymd_formatted_date_string()
    {
        $this->format('Y-m-d')->shouldReturn(self::DATE);
    }

    function it_does_not_accept_other_date_formats_than_iso()
    {
        $this->shouldThrow(new UnrecognizedDateFormat('3/9/1993'))->during('fromString', ['3/9/1993']);
    }

    function it_returns_jMY_formatted_date_string()
    {
        $this->format('j M Y')->shouldReturn('8 Mar 1993');
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
        $today = new \DateTime(self::DATE);

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
        $today = new \DateTime(self::DATE);

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
        $today = new \DateTime(self::DATE);

        $this->format('j F Y')->shouldReturn($today->format('j F Y'));
    }
}
