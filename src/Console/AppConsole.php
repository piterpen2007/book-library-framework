<?php

namespace EfTech\BookLibrary\Infrastructure\Console;

use EfTech\BookLibrary\Infrastructure\Exception\RuntimeException;
use EfTech\BookLibrary\Infrastructure\Console\Output\EchoOutput;
use EfTech\BookLibrary\Infrastructure\Console\Output\OutputInterface;
use EfTech\BookLibrary\Infrastructure\Exception;
use EfTech\BookLibrary\Infrastructure\DI\ContainerInterface;
use Throwable;

class AppConsole
{
    /**
     * Ключем является имя команды, а значением класс консольной команды или данные типа callble
     *
     * @var array
     */
    private array $commands;

    /**
     * Компонент отвечающий за вывод данных в консоль
     *
     * @var OutputInterface|null
     */
    private ?OutputInterface $output = null;

    /**
     * di контейнер
     *
     * @var ContainerInterface|null
     */
    private ?ContainerInterface $diContainer = null;


    /**
     * Фабрика реализующая логику осздания рендера
     *
     * @var callable
     */
    private $outputFactory;

    /**
     * Фабрика для создания diContainer
     *
     * @var callable
     */
    private $diContainerFactory;

    /**
     * @param array    $commands           - Ключем является имя команды, а значением класс консольной команды или
     *                                     данные типа callble
     * @param callable $outputFactory      - Фабрика реализующая логику осздания рендера
     * @param callable $diContainerFactory - Фабрика для создания diContainer
     */
    public function __construct(array $commands, callable $outputFactory, callable $diContainerFactory)
    {
        $this->commands = $commands;
        $this->outputFactory = $outputFactory;
        $this->diContainerFactory = $diContainerFactory;
        $this->initiateErrorHandling();
    }

    /**
     * Возвращает компонент отвечающий за вывод даннных в консоль
     *
     * @return OutputInterface
     */
    private function getOutput(): OutputInterface
    {
        if (null === $this->output) {
            $this->output = ($this->outputFactory)($this->getDiContainer());
        }

        return $this->output;
    }

    /**
     * Возвращает di container
     *
     * @return ContainerInterface
     */
    private function getDiContainer(): ContainerInterface
    {
        if (null === $this->diContainer) {
            $this->diContainer = ($this->diContainerFactory)();
        }
        return $this->diContainer;
    }


    /**
     *
     * Иницирую обработку ошибок
     *
     * @return void
     */
    private function initiateErrorHandling(): void
    {
        set_error_handler(static function (int $errNom, string $errStr/**,string $errFile, int errLine */) {
            throw new Exception\RuntimeException($errStr);
        });
    }

    public function dispatch(string $commandName = null, array $params = null): void
    {
        $output = null;
        try {
            $output = $this->getOutput();

            $commandName = $commandName ?? $this->getCommandName();
            if (null === $commandName) {
                throw new RuntimeException("Command name must be specified");
            }
            if (false === array_key_exists($commandName, $this->commands)) {
                throw new RuntimeException("Unknown command: '$commandName'");
            }

            if (
                false === is_string($this->commands[$commandName]) || false === is_subclass_of(
                    $this->commands[$commandName],
                    CommandInterface::class,
                    true
                )
            ) {
                throw new RuntimeException("There is no valid handler for the command '$commandName'");
            }
            $command = $this->getDiContainer()->get($this->commands[$commandName]);
            $params = $params ?? $this ->getCommandParams($this->commands[$commandName]);

            $command($params);
        } catch (Throwable $e) {
            $output = $output ?? new EchoOutput();
            $output->print("Error: {$e->getMessage()}\n");
        }
    }

    /**
     * Возвращает имя команды
     * @return string|null
     */
    private function getCommandName(): ?string
    {
        $options = getopt('', ['command:']);
        $command = null;
        if (is_array($options) && array_key_exists('command', $options) && is_string($options['command'])) {
            $command = $options['command'];
        }
        return $command;
    }

    /**
     * возвращает параметры для команды
     * @param string $commandName
     *
     * @return array
     */
    private function getCommandParams(string $commandName): array
    {
        $longOptions = call_user_func("$commandName::getLongOption");
        $shortOptions = call_user_func("$commandName::getShortOption");
        $options = getopt($shortOptions, $longOptions);

        return is_array($options) ? $options : [];
    }
}
