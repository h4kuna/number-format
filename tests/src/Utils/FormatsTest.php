<?php declare(strict_types=1);

namespace h4kuna\Format\Tests\Utils;

use h4kuna\Format\Exceptions\InvalidStateException;
use h4kuna\Format\Tests\TestCase;
use h4kuna\Format\Utils\Formats;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * @testCase
 */
final class FormatsTest extends TestCase
{
	public function testDefaultIsNotDefinedFailed(): void
	{
		Assert::throws(fn () => (new Formats())->get('any'), InvalidStateException::class);
		Assert::throws(fn () => (new Formats())->setDefault('any'), InvalidStateException::class);
	}


	public function testFormats(): void
	{
		$formats = new Formats([
			'CZK' => 'Kč',
			'EUR' => static fn (): string => '€',
		]);
		$formats->add('GBP', '£');
		$formats->setDefault(static fn (string|int $key): string => "-$key-");

		Assert::true($formats->has('EUR')); // live
		Assert::true($formats->has('CZK')); // factories
		Assert::false($formats->has('usd'));

		Assert::same('Kč', $formats->get('CZK'));
		Assert::same('€', $formats->get('EUR'));
		Assert::same('£', $formats->get('GBP'));
		Assert::same('-unknown-', $formats->get('unknown'));

		$formats->add('CZK', static fn (): string => 'Kčs');
		Assert::same('Kčs', $formats->get('CZK'));
	}
}

(new FormatsTest())->run();
