[![Php Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg)](https://secure.php.net/)
[![Swoole Version](https://img.shields.io/badge/swoole-%3E=4.3.0-brightgreen.svg)](https://github.com/swoole/swoole-src)
[![Rangine Framework Version](https://img.shields.io/badge/rangine-%3E=0.0.1-brightgreen.svg)](https://gitee.com/we7coreteam/w7swoole)
[![Illuminate Database Version](https://img.shields.io/badge/illuminate/database-%3E=5.6.0-brightgreen.svg)](https://github.com/illuminate/database)
[![Rangine Doc](https://img.shields.io/badge/docs-passing-green.svg?maxAge=2592000)](https://s.we7.cc/index.php?c=wiki&do=view&id=317)
# w7Swoole

一款基于Swoole高性能应用框架。常驻内存，不依赖传统的 PHP-FPM，全异步非阻塞、协程实现。可以用于构建高性能的Web系统、API、中间件、基础服务等等。

# 代码

Github : https://github.com/we7coreteam/w7swoole_empty.git

Gitee : https://gitee.com/we7coreteam/w7swoole_empty.git

# 安装

composer install 前更改 composer 源，防止报错。

```
composer config -g repo.packagist composer https://packagist.laravel-china.org

git clone https://gitee.com/we7coreteam/w7swoole_empty ./rangine-test

cd rangine-test

sudo composer install
```


# 文档

https://s.we7.cc/index.php?c=wiki&do=view&id=317

# 功能

- 基于 Swoole 扩展
- HTTP 服务器 (PSR-7消息)
- RPC 服务器 *
- WebSocket 服务器 *
- MVC 分层设计
- 中间件 (PSR-15)
- URL路由 ([FastRoute](https://github.com/nikic/FastRoute))
- 协程数据库连接(Pdo, Mysql)
- 数据库连接池
- ORM 模型 ([Laravel Database](https://laravel-china.org/docs/laravel/5.5/eloquent/1332))
- DB查询门面  ([Laravel Database](https://laravel-china.org/docs/laravel/5.5/queries/1327))
- 日志系统 ([MonoLog](https://github.com/Seldaek/monolog))
- 协程、异步任务投递
- 类 Crontab 计划任务
- 异步任务
- 自定义事件侦听






