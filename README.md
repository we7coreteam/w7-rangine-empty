[![Php Version](https://img.shields.io/badge/php-%3E=7.1-brightgreen.svg)](https://secure.php.net/)
[![Swoole Version](https://img.shields.io/badge/swoole-%3E=4.3.0-brightgreen.svg)](https://github.com/swoole/swoole-src)
[![Rangine Framework Version](https://img.shields.io/badge/rangine-%3E=0.0.1-brightgreen.svg)](https://github.com/we7coreteam/w7-rangine)
[![Illuminate Database Version](https://img.shields.io/badge/illuminate/database-%3E=5.6.0-brightgreen.svg)](https://github.com/illuminate/database)
[![Rangine Doc](https://img.shields.io/badge/docs-passing-green.svg?maxAge=2592000)](https://wiki.w7.cc/chapter/1?id=1175#)
# w7Rangine

软擎是基于 Php 7.2+ 和 Swoole 4.3+ 的高性能、简单易用的开发框架。支持同时在 Swoole Server 和 php-fpm 两种模式下运行。内置了 Http (Swoole, Fpm)，Tcp，WebSocket，Process，Crontab服务。集成了大量成熟的组件，可以用于构建高性能的Web系统、API、中间件、基础服务等等。

# 代码

Github : https://github.com/we7coreteam/w7-rangine-empty.git

Gitee : https://gitee.com/we7coreteam/w7swoole_empty.git

# 安装

composer install 前更改 composer 源，防止报错。

```
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```

## 初始化骨架项目

```
composer create-project w7/rangine-demo ./project-name
```

## 初始化示例项目

示例项目中包含文档上的一些演示代码，仅供了解使用。

```
git clone https://github.com/we7coreteam/w7-rangine-empty.git ./rangine-demo-test
cd rangine-demo-test
sudo composer install
```


# 文档

https://wiki.w7.cc/chapter/1?id=1175#

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
- Provider 扩展机制
- Session 机制






