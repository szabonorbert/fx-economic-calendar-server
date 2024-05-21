# FX economic calendar server

Create your own PHP-based economic calendar server with minimal features. You can use it for quant data source, or for indicators for example in MetaTrader that looks into the past or into the near future.

### Docker usage

Run with ```docker-compose up```, and you can make your first request like this:
```
http://localhost:8000/2024/05/20
```

### Shared hosting usage

You can run it on the most basic shared hosting servers, even with the free ones. Make sure that you install the dependencies with ```composer install```, and the ```index.php``` needs write permission to the ```data``` folder, because it downloads the data from [DailyFX](https://www.dailyfx.com/economic-calendar), then persist to the ```data``` folder.

### Valid routes

* ```2024/05/20``` downloads only one day
* ```2024/05``` downloads the whole month
* ```2024``` downloads the whole year

It also works if you put ```.json``` at the end of the URL, like ```2024/05/20.json```. It's useful if you are using a CDN with default settings, like catch only the static files.

### Env variables

* ```min_importance```: importance level 1 to 3. Default is 3.