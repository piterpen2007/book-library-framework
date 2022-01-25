<?php

namespace EfTech\BookLibrary\Infrastructure\DataLoader;
/** Загрузка данных из json файла
 *
 */
class JsonDataLoader implements DataLoaderInterface
{
    /** Загрузка данных из ресурса
     * @param string $sourceName
     * @return array
     * @throws \JsonException
     */
    public function loadData (string $sourceName):array
    {
        $content = file_get_contents($sourceName);
        return json_decode($content, true,512 , JSON_THROW_ON_ERROR);
    }

}