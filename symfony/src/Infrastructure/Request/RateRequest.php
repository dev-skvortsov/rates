<?php

namespace App\Infrastructure\Request;

use App\Domain\ValueObject\Code;
use App\Domain\Entity\Rate;
use App\Infrastructure\Request\Resolve\RequestInterface;
use App\Infrastructure\Request\Validate\ValidatedRequestInterface;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class RateRequest implements RequestInterface, ValidatedRequestInterface
{
    public const DATE_FORMAT = 'Y-m-d';

    private string $code = '';
    private string $date = '';
    private string $baseCode = Code::RUR_CODE;

    public function __construct(Request $request)
    {
        $code = $request->query->get('code');
        if (is_string($code)) {
            $this->code = $code;
        }

        $date = $request->query->get('date');
        if (is_string($date)) {
            $this->date = $date;
        }

        $baseCode = $request->query->get('baseCode');
        if (is_string($baseCode)) {
            $this->baseCode = $baseCode;
        }
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraints('code', [
            new Assert\NotBlank(),
            new Assert\Length(exactly: Code::ISO4217_CODE_LENGTH),
        ]);

        $metadata->addPropertyConstraints('baseCode', [
            new Assert\Length(exactly: Code::ISO4217_CODE_LENGTH),
        ]);

        $metadata->addConstraint(new Assert\Callback([
            self::class,
            'codeMustBeNotEqualBaseCode'
        ]));

        $metadata->addPropertyConstraints('date', [
            new Assert\NotBlank(),
            new Assert\DateTime(format: self::DATE_FORMAT),
            new Assert\GreaterThanOrEqual(Rate::getStartTradingDate()->format(self::DATE_FORMAT)),
            new Assert\LessThanOrEqual((new DateTime())->format(self::DATE_FORMAT))
        ]);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDate(): DateTime
    {
        return DateTime::createFromFormat(self::DATE_FORMAT, $this->date);
    }

    public function getBaseCode(): string
    {
        return $this->baseCode;
    }

    public static function codeMustBeNotEqualBaseCode(RateRequest $request, ExecutionContextInterface $context, mixed $payload): void
    {
        if ($request->getCode() !== '' && $request->getCode() === $request->getBaseCode()) {
            $context->buildViolation('Code must not be equal to baseCode')
                ->atPath('code')
                ->addViolation();
        }
    }
}