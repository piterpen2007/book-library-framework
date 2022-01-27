<?php

namespace EfTech\BookLibrary\Infrastructure\Validator;

/**
 * Коллекция методов реализющих разнообразные проверки в приложении
 */
final class Assert
{
    /** Проверяет что заданные элементы массива являются строками
     * @param array $listItemsToCheck - список элементов для проверки
     * @param array $dataForValidation - валидируемые данные
     * @return string|null - текст ошибки или нулл если ошибок нет
     */
    public static function arrayElementsIsString(array $listItemsToCheck, array $dataForValidation): ?string
    {
        $result = null;
        foreach ($listItemsToCheck as $paramName => $errMsg) {
            if (
                array_key_exists($paramName, $dataForValidation)
                && false === is_string($dataForValidation[$paramName])
            ) {
                $result = $errMsg;
                break;
            }
        }
        return $result;
    }
}
