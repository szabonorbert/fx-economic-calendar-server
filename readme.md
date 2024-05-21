# FX economic calendar server

Lightweight PHP-based economic calendar server with minimal features. It can be used as the simplest quant data source or as indicators in MetaTrader, for instance, that look into the recent or distant past.

#### Why?

I was unable to locate any indicator that displays this type of historical basic economic data in order to calculate the influence on prices. Furthermore, I couldn't identify an easy and trustworthy source for this kind of information as an API. Thus, I created one.

### Docker usage

Run with ```docker-compose up``` and you can make your first request like this:
```
http://localhost:8000/2024/05/20
```

### Shared hosting usage

You can run it on the most basic shared hosting servers, even with free ones. Make sure that you install the dependencies with ```composer install```, and the ```index.php``` needs write permission to the ```data``` folder because it downloads the data from [DailyFX](https://www.dailyfx.com/economic-calendar), then persist to the ```data``` folder.

### Valid routes

* ```2024/05/20``` downloads only one day
* ```2024/05``` downloads the whole month
* ```2024``` downloads the whole year

It also works if you put ```.json``` at the end of the URL, like ```2024/05/20.json```. It's useful if you are using a CDN with default settings, like catch only the static files.

### Env variables

* ```dailyfx_url```: url to fetch
* ```url_prefix```: install folder of this script
* ```min_importance```: importance level 1 to 3 (default is 3)