<?php

namespace EfTech\BookLibrary\Infrastructure\DataLoader;

/** Интерфейс загрузчика данных из файла
 *
 */
interface DataLoaderInterface
{
    /** Загружаю и десериализую данные
     * @param string $sourceName
     * @return array
     */
    public function loadData(string $sourceName): array;
}