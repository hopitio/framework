<?php

class Config
{

    /**
     * <ul>
     * <li>0 = tắt debug</li>
     * <li>1 = bật debug PHP & Database trên trang thường, không bật trên service</li>
     * <li>10 = bật debug trên tất cả</li>
     * </ul>
     */
    const DEBUG_MODE = 0;

    /** Ten phan mem */
    const APP_NAME = 'Framework';

    /** Tham so database */
    const DB_TYPE = 'mysqli';
    const DB_HOST = '127.0.0.1';
    const DB_NAME = 'framework';
    const DB_USER = 'root';
    const DB_PASS = 'root';

    /**
     * Các class trong thư mục sau tự động load
     */
    static function autoload()
    {
        return array(
            BASE_DIR
        );
    }

    const CRYPT_SECRECT = 'abM)(*2312';

}
