<?php

namespace App\Helpers;

class CityFormat
{
    /**
     * function standadizeTheNames
     *
     * @param string $name
     * @return
     */
    public static function standadizeTheNames(string $name)
    {
        $upperExcept = ['da', 'de', 'di', 'do', 'a', 'e', 'o', 'dos'];

        return static::ucWordsExcept($name, $upperExcept);
    }

    /**
     * function ucWordsExcept
     *
     * @param string $string
     * @param array $except
     * @return
     */
    public static function ucWordsExcept(string $string, array $except = [])
    {
        $string = static::clearString($string);

        foreach (explode(' ', $string) as $word) {
            $word = \strtolower($word);

            if (\in_array($word, \array_values($except))) {
                $finalString[] = $word;
                continue;
            }

            $finalString[] = \ucfirst($word);
        }

        return implode(' ', $finalString ?? []) ?: $string;
    }

    /**
     * function clearString
     *
     * @param string $string
     * @param array $remove
     * @param array $replace
     * @return string
     */
    public static function clearString(string $string, array $remove = [], array $replace = []): string
    {
        $string = trim($string);

        $defaultToRemove = ['\\', '.', ','];
        $string = \str_replace(($defaultToRemove + $remove), '', $string);

        $defaultToReplace = ['  ' => ' '];
        $toReplace = !\array_is_list($replace) ? \array_merge($replace, $defaultToReplace) : $defaultToReplace;
        return \str_replace(\array_keys($toReplace), \array_values($toReplace), $string);
    }
}
