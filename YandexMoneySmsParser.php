<?php

class YandexMoneySmsParser {

    private static $_codePattern = '/\D(\d{4})$|\D(\d{4})\D/';
    private static $_summPattern = '/(\d*[.,]\d{2})\D/';
    private static $_walletPattern = '/\d{14}/';

    /**
     * Возвращает массив значений, вида:
     *  [
     *      'code' => SMS-код,
     *      'summ' => Сумма платежа,
     *      'wallet' => ЯД кошелек
     *  ]
     *  либо false, если не получен хотя бы один параметр
     *
     * @param string $string
     * @return array|false
     */
    public static function getData(string $string) {
        if ($code = self::_getCode($string)) {
            if ($summ = self::_getSumm($string)) {
                if ($wallet = self::_getWallet($string)) {
                    return [
                        'code' => mb_substr($code[0], 1, 4),
                        'summ' => $summ[1],
                        'wallet' => $wallet[0],
                    ];
                }
            }
        }
        return false;
    }

    private static function _getDataByPattern(string $pattern, string $string) : array {
        preg_match($pattern, $string, $matches);
        return $matches;
    }

    private static function _getCode(string $string) : array {
        return self::_getDataByPattern(self::$_codePattern, $string);
    }

    private static function _getSumm(string $string) : array {
        return self::_getDataByPattern(self::$_summPattern, $string);
    }

    private static function _getWallet(string $string) : array {
        return self::_getDataByPattern(self::$_walletPattern, $string);
    }

}
