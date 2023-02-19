# Rates from [cbr.ru](http://cbr.ru)

In this repository you can find an example database snapshot (rates.sql)
and Postman collection.

Be sure to use a makefile for an easy start.

Подразумевается, что в момент деплоя на production сервер история курсов
уже загружена в хранилище  (заполняем тестовое хранилище с помощью команды 
``App\Infrastructure\Command\GetCbrRatesHistoryCommand`` и в момент деплоя переливаем). 

Раз в сутки запускается команда ``App\Infrastructure\Command\GetCurrentCbrRatesCommand``
и кэширует данные с cbr.ru в наше хранилище.

Можно дополнительно сверху закешировать в каком-нибудь in-memory хранилище
(например, в Memcached) и добавить HTTP кэширование (история курсов не изменяется).