<?php

namespace Meetee\Libs\Security\Validators\Factories;

use Meetee\Libs\Security\Validators\IsSetValidator;
use Meetee\Libs\Security\Validators\NotEmptyValidator;
use Meetee\Libs\Security\Validators\TypeValidator;
use Meetee\Libs\Security\Validators\NotTypeValidator;
use Meetee\Libs\Security\Validators\MinValidator;
use Meetee\Libs\Security\Validators\MaxValidator;
use Meetee\Libs\Security\Validators\MinLengthValidator;
use Meetee\Libs\Security\Validators\MaxLengthValidator;
use Meetee\Libs\Security\Validators\ExactLengthValidator;
use Meetee\Libs\Security\Validators\DateBeforeValidator;
use Meetee\Libs\Security\Validators\DateAfterValidator;
use Meetee\Libs\Security\Validators\DateNotBeforeValidator;
use Meetee\Libs\Security\Validators\DateNotAfterValidator;
use Meetee\Libs\Security\Validators\PatternValidator;
use Meetee\Libs\Security\Validators\ExistsValidator;
use Meetee\Libs\Security\Validators\NotExistValidator;
use Meetee\Libs\Security\Validators\EmailValidator;
use Meetee\Libs\Security\Validators\BothMatchValidator;

class ValidatorFactory
{
	public static function createIsSetValidator(
		?string $errorMsg = ''
	): IsSetValidator
	{
		$validator = new IsSetValidator();
		$validator->errorMsg = $errorMsg;

		return $validator;
	}

	public static function createNotEmptyValidator(
		?string $errorMsg = ''
	): NotEmptyValidator
	{
		$validator = new NotEmptyValidator();
		$validator->errorMsg = $errorMsg;

		return $validator;
	}

	public static function createStringValidator(
		?string $errorMsg = ''
	): TypeValidator
	{
		return static::createTypeValidator('string', $errorMsg);
	}

	public static function createIntValidator(
		?string $errorMsg = ''
	): TypeValidator
	{
		return static::createTypeValidator('integer', $errorMsg);
	}

	public static function createBoolValidator(
		?string $errorMsg = ''
	): TypeValidator
	{
		return static::createTypeValidator('boolean', $errorMsg);
	}

	public static function createTypeValidator(
		string $type,
		?string $errorMsg = ''
	): TypeValidator
	{
		$validator = new TypeValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setType($type);

		return $validator;
	}

	public static function createNotTypeValidator(
		string $type,
		?string $errorMsg = ''
	): NotTypeValidator
	{
		$validator = new NotTypeValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setType($type);

		return $validator;
	}

	public static function createMinValidator(
		int $min, 
		?string $errorMsg = ''
	): MinValidator
	{
		$validator = new MinValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setMinimum($min);

		return $validator;
	}

	public static function createMaxValidator(
		int $max,
		?string $errorMsg = ''
	): MaxValidator
	{
		$validator = new MaxValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setMaximum($max);

		return $validator;
	}

	public static function createMinLengthValidator(
		int $min, 
		?string $errorMsg = ''
	): MinLengthValidator
	{
		$validator = new MinLengthValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setMinimum($min);

		return $validator;
	}

	public static function createMaxLengthValidator(
		int $max,
		?string $errorMsg = ''
	): MaxLengthValidator
	{
		$validator = new MaxLengthValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setMaximum($max);

		return $validator;
	}

	public static function createExactLengthValidator(
		int $expected,
		?string $errorMsg = ''
	): ExactLengthValidator
	{
		$validator = new ExactLengthValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setExpected($expected);

		return $validator;
	}

	public static function createDateBeforeValidator(
		string $upperBound,
		?string $errorMsg = ''
	): DateBeforeValidator
	{
		$validator = new DateBeforeValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setUpperBound(new \DateTime($upperBound));

		return $validator;
	}

	public static function createDateAfterValidator(
		string $lowerBound,
		?string $errorMsg = ''
	): DateAfterValidator
	{
		$validator = new DateAfterValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setLowerBound(new \DateTime($lowerBound));

		return $validator;
	}

	public static function createDateNotBeforeValidator(
		string $lowerBound,
		?string $errorMsg = ''
	): DateNotBeforeValidator
	{
		$validator = new DateNotBeforeValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setLowerBound(new \DateTime($lowerBound));

		return $validator;
	}

	public static function createDateNotAfterValidator(
		string $upperBound,
		?string $errorMsg = ''
	): DateNotAfterValidator
	{
		$validator = new DateNotAfterValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setUpperBound(new \DateTime($upperBound));

		return $validator;
	}

	public static function createPatternValidator(
		string $pattern,
		?string $errorMsg = ''
	): PatternValidator
	{
		$validator = new PatternValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setPattern($pattern);

		return $validator;
	}

	public static function createExistsValidator(
		string $table,
		string $column,
		?string $errorMsg = ''
	): ExistsValidator
	{
		$validator = new ExistsValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setTable($table);
		$validator->setColumn($column);

		return $validator;
	}

	public static function createNotExistValidator(
		string $table,
		string $column,
		?string $errorMsg = ''
	): NotExistValidator
	{
		$validator = new NotExistValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setTable($table);
		$validator->setColumn($column);

		return $validator;
	}

	public static function createEmailValidator(
		?string $errorMsg = ''
	): EmailValidator
	{
		$validator = new EmailValidator();
		$validator->errorMsg = $errorMsg;

		return $validator;
	}

	public static function createBothMatchValidator(
		$expected,
		?string $errorMsg = ''
	): EmailValidator
	{
		$validator = new BothMatchValidator();
		$validator->errorMsg = $errorMsg;
		$validator->setExpected($expected);

		return $validator;
	}
}