<?php

declare(strict_types=1);

namespace Dev\PHPCsFixer\Comment;

use InvalidArgumentException;
use Override;
use PhpCsFixer\ConfigurationException\InvalidForEnvFixerConfigurationException;
use PhpCsFixer\ConfigurationException\RequiredFixerConfigurationException;
use PhpCsFixer\Console\Application;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerConfiguration\DeprecatedFixerOption;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerConfiguration\InvalidOptionsForEnvException;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Analyzer\WhitespacesAnalyzer;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Utils;
use PhpCsFixer\WhitespacesFixerConfig;
use SplFileInfo;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Options;

/**
 * Фиксер обязательных комментариев для всех классов перед class
 *
 * @template TFixerInputConfig of array<string, mixed>
 * @template TFixerComputedConfig of array<string, mixed>
 *
 * @implements ConfigurableFixerInterface<TFixerInputConfig, TFixerComputedConfig>
 *
 * @psalm-suppress MissingTemplateParam
 */
final class ClassDocCommentFixer implements FixerInterface, WhitespacesAwareFixerInterface, ConfigurableFixerInterface
{
    private const string EXCLUDE_KEY = 'exclude';

    private WhitespacesFixerConfig $whitespacesConfig;

    private ?FixerConfigurationResolverInterface $configurationDefinition = null;

    /**
     * @var array<array-key, mixed>|null
     */
    private ?array $configuration = null;

    public function __construct()
    {
        $this->whitespacesConfig = $this->getDefaultWhitespacesFixerConfig();
    }

    #[Override]
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAnyTokenKindsFound(Token::getClassyTokenKinds());
    }

    #[Override]
    public function isRisky(): bool
    {
        return false;
    }

    #[Override]
    public function getName(): string
    {
        return \sprintf('ClassDocComment/%s', 'class_doc_comment');
    }

    #[Override]
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'There must be a class comment before class.',
            [
                new CodeSample(
                    '<?php
final class Sample
{}
',
                ),
            ],
        );
    }

    #[Override]
    public function getPriority(): int
    {
        return -30;
    }

    #[Override]
    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        if ($tokens->count() <= 0) {
            return;
        }
        if (!$this->isCandidate($tokens)) {
            return;
        }
        if (!$this->supports($file)) {
            return;
        }
        $this->applyFix($tokens);
    }

    #[Override]
    public function supports(SplFileInfo $file): bool
    {
        return $this->isAllowedByConfiguration($file);
    }

    #[Override]
    public function setWhitespacesConfig(WhitespacesFixerConfig $config): void
    {
        $this->whitespacesConfig = $config;
    }

    /**
     * @param TFixerInputConfig $configuration
     */
    #[Override]
    public function configure(array $configuration): void
    {
        foreach ($this->getConfigurationDefinition()->getOptions() as $option) {
            if (!$option instanceof DeprecatedFixerOption) {
                continue;
            }

            $name = $option->getName();
            if (\array_key_exists($name, $configuration)) {
                /**
                 * @psalm-suppress DeprecatedClass
                 * @psalm-suppress InternalClass
                 * @psalm-suppress InternalMethod
                 */
                Utils::triggerDeprecation(new InvalidArgumentException(\sprintf(
                    'Option "%s" for rule "%s" is deprecated and will be removed in version %d.0. %s',
                    $name,
                    $this->getName(),
                    /**
                     * @psalm-suppress InternalClass
                     * @psalm-suppress InternalMethod
                     */
                    Application::getMajorVersion() + 1,
                    str_replace('`', '"', $option->getDeprecationMessage()),
                )));
            }
        }

        try {
            $this->configuration = $this->getConfigurationDefinition()->resolve($configuration);
        } catch (MissingOptionsException $exception) {
            /**
             * @psalm-suppress InternalClass
             * @psalm-suppress InternalMethod
             */
            throw new RequiredFixerConfigurationException(
                $this->getName(),
                \sprintf('Missing required configuration: %s', $exception->getMessage()),
                $exception,
            );
        } catch (InvalidOptionsForEnvException $exception) {
            /**
             * @psalm-suppress InternalClass
             * @psalm-suppress InternalMethod
             */
            throw new InvalidForEnvFixerConfigurationException(
                $this->getName(),
                \sprintf('Invalid configuration for env: %s', $exception->getMessage()),
                $exception,
            );
        }
    }

    #[Override]
    public function getConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        if ($this->configurationDefinition === null) {
            $this->configurationDefinition = $this->createConfigurationDefinition();
        }

        return $this->configurationDefinition;
    }

    /**
     * @return iterable<int>
     */
    private function findClasses(Tokens $tokens): iterable
    {
        for ($index = $tokens->count() - 1; $index > 0; --$index) {
            if (!$tokens[$index]->isClassy()) {
                continue;
            }

            $startIndex = $tokens->getNextTokenOfKind($index, [';']);

            if ($startIndex === null) {
                return;
            }

            yield $startIndex;
        }
    }

    private function applyFix(Tokens $tokens): void
    {
        /**
         * @var int $index
         * @var Token $token
         */
        foreach ($tokens as $index => $token) {
            if (!$token->isClassy()) {
                continue;
            }

            /** @var int $startBraceIndex */
            $startBraceIndex = $tokens->getNextTokenOfKind($index, ['{']);

            if (!$tokens[$startBraceIndex + 1]->isWhitespace()) {
                continue;
            }

            foreach ($this->findClasses($tokens) as $startIndex) {
                $this->addComment($tokens, $startIndex);
            }
        }
    }

    private function addComment(Tokens $tokens, int $startIndex): void
    {
        /** @var int $classIndex */
        $classIndex = $tokens->getPrevTokenOfKind($startIndex, [[T_DECLARE], [T_NAMESPACE]]);

        $docBlockIndex = $this->getDocBlockIndex($tokens, $classIndex);

        if ($this->isPHPDoc($tokens, $docBlockIndex)) {
            return;
        }

        $this->createDocBlock($tokens, $docBlockIndex);
    }

    private function createDocBlock(Tokens $tokens, int $docBlockIndex): void
    {
        $lineEnd = $this->whitespacesConfig->getLineEnding();

        /** @var int $nextBlockIndex */
        $nextBlockIndex = $tokens->getNextNonWhitespace($docBlockIndex);

        /**
         * @psalm-suppress InternalClass
         * @psalm-suppress InternalMethod
         */
        $originalIndent = WhitespacesAnalyzer::detectIndent($tokens, $nextBlockIndex);
        $toInsert = [
            new Token([
                T_DOC_COMMENT, '/**'.$lineEnd."{$originalIndent} * TODO: Опиши за что отвечает данный класс, ".
                'какие проблемы решает'.$lineEnd."{$originalIndent} */",
            ]),
            new Token([T_WHITESPACE, $lineEnd.$originalIndent]),
        ];

        /** @var int $index */
        $index = $tokens->getNextMeaningfulToken($docBlockIndex);

        $tokens->insertAt($index, $toInsert);
    }

    private function getDocBlockIndex(Tokens $tokens, int $index): int
    {
        // Иду до объявления final|abstract|interface|enum|trait|class, если по пути встречается атрибут, останавливаюсь на нём
        // Где бы не остановился, отдаю предыдущий индекс, который не является пробелом
        $isAttribute = false;
        do {
            /** @var int $index */
            $index = $tokens->getNextNonWhitespace($index);

            if ($tokens[$index]->getContent() === '#[') {
                $isAttribute = true;

                /** @var int $index */
                $index = $tokens->getPrevNonWhitespace($index);

                break;
            }
        } while (!$tokens[$index]->isGivenKind([T_FINAL, T_ABSTRACT, T_INTERFACE, T_ENUM, T_TRAIT, T_CLASS]));

        if ($isAttribute) {
            return $index;
        }

        /** @var int $prevNonWhitespaceIndex */
        $prevNonWhitespaceIndex = $tokens->getPrevNonWhitespace($index);

        return $prevNonWhitespaceIndex;
    }

    private function isPHPDoc(Tokens $tokens, int $index): bool
    {
        return $tokens[$index]->isGivenKind(T_DOC_COMMENT);
    }

    private function getDefaultWhitespacesFixerConfig(): WhitespacesFixerConfig
    {
        static $defaultWhitespacesFixerConfig = null;

        if ($defaultWhitespacesFixerConfig === null) {
            $defaultWhitespacesFixerConfig = new WhitespacesFixerConfig('    ', "\n");
        }

        /** @var WhitespacesFixerConfig $whiteSpaceConfig */
        $whiteSpaceConfig = $defaultWhitespacesFixerConfig;

        return $whiteSpaceConfig;
    }

    private function createConfigurationDefinition(): FixerConfigurationResolver
    {
        return new FixerConfigurationResolver([
            (new FixerOptionBuilder(self::EXCLUDE_KEY, 'Excluded namespace.'))
                ->setAllowedTypes(['string'])
                ->setNormalizer(static function (Options $options, string $value): string {
                    if (trim($value) === '') {
                        return '';
                    }

                    return $value;
                })
                ->getOption(),
        ]);
    }

    private function isAllowedByConfiguration(SplFileInfo $file): bool
    {
        if ($this->configuration !== null) {
            /** @var string $excludeKey */
            $excludeKey = $this->configuration[self::EXCLUDE_KEY];
            if (str_contains($file->getPathname(), $excludeKey)) {
                return false;
            }
        }

        return true;
    }
}
