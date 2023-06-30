# CodeHawk Portal 開發專案

## 建構開發環境

1. 前往專案根目錄執行 `docker-compose up` 啟動開發伺服器。
2. 前往專案根目錄執行 `docker-compose down` 關閉開發伺服器。

## 後端與資料庫

### 資料庫設定
1. 開發伺服器預設資料庫連線資訊如下：
    * host : `localhost`
    * port : `8306`
    * username : `root`
    * password : `root`
    * database : `codehawk`
2. 初始化開發用資料庫：
    * 於 docker 伺服器建構完成後，在專案根目錄下執行 `docker-compose exec backend php spark migrate -all`

### 後端
1. 後端伺服器連線資訊如下：
    * host: `localhost`
    * port: `8080`
1. 請將根目錄 `env` 複製一份 `.env` 至後端專案目錄下，不用修改 database 連線資訊
2. 修改 PHP 程式碼後不須重開 docker
3. 若是須於容器執行 spark 指令，至專案根目錄下執行 `docker-compose exec backend php spark [你的指令]` 即可
4. 執行單元測試 `docker-compose exec backend vendor/bin/phpunit`
5. 每次 `docker-compose up` 時會自動執行 `docker-compose exec backend composer install`
6. 安裝套件時執行 `docker-compose exec backend composer require`