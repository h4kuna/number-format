<?php declare(strict_types = 1);

namespace h4kuna\Format\Tests\Number\NativePhp;

use Closure;
use h4kuna\Format\Number\NativePhp\NumberFormatterFactory;
use h4kuna\Format\Tests\TestCase;
use h4kuna\Format\Utils\Space;
use NumberFormatter;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

final class NumberFormatterFactoryTest extends TestCase
{

	/**
	 * @return array<string|int, array{0: Closure(static):void}>
	 */
	public function dataNumber(): array
	{
		return [
			[
				static function (self $self): void {
					$self->assertNbsp(
						self::factory()->create(),
						'1 000,345',
					);
				},
			],
			[
				static function (self $self): void {
					$self->assertNbsp(
						self::factory()->create(0),
						'1 000',
					);
				},
			],
			[
				static function (self $self): void {
					$self->assertNbsp(
						self::factory()->create(1),
						'1 000,3',
					);
				},
			],
			[
				static function (self $self): void {
					$self->assertNbsp(
						self::factory()->create(4),
						'1 000,3450',
					);
				},
			],
			[
				static function (self $self): void {
					$self->assertNbsp(
						self::factory()->create(2, 'en_GB'),
						'1,000.34',
					);
				},
			],
			[
				static function (self $self): void {
					$self->assertNbsp(
						self::factory()->currency('en_GB'),
						'£1,000.34',
					);
				},
			],
			[
				static function (self $self): void {
					$self->assertNbsp(
						self::factory()->currency(),
						'1 000,34 Kč',
					);
				},
			],
			[
				static function (self $self): void {
					$self->assertNbsp(
						self::factory()->ordinal(),
						'1 000.',
					);
				},
			],
			[
				static function (self $self): void {
					$self->assertNumber(
						self::factory()->spell(),
						'jedna tisíc čárka tři čtyři pět',
					);
				},
			],
			[
				static function (self $self): void {
					$self->assertNbsp(
						self::factory()->percent(),
						'1 000 %',
					);
				},
			],
			[
				static function (self $self): void {
					$self->assertNbsp(
						self::factory()->percent(1),
						'1 000,3 %',
					);
				},
			],
		];
	}

	/**
	 * @param Closure(static):void $assert
	 *
	 * @dataProvider dataNumber
	 */
	public function testNumber(Closure $assert): void
	{
		$assert($this);
	}

	public function assertNbsp(
		NumberFormatter $formatter,
		string $expected,
	): void
	{
		Assert::same(Space::nbsp($expected), $formatter->format(1000.345));
	}

	public function assertNumber(
		NumberFormatter $formatter,
		string $expected,
	): void
	{
		Assert::same($expected, $formatter->format(1000.345));
	}

	private static function factory(): NumberFormatterFactory
	{
		return new NumberFormatterFactory('cs_CZ');
	}

}

(new NumberFormatterFactoryTest())->run();
