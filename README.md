#### ENV

```sh
# ibge-gov-br, brasil-api
CITIES_API_PROVIDER='brasil-api'
```

#### ROUTES

----
- > **GET /api/city/show/{uf?}/{cityName?}/{provider?}**

```sh
/api/city/show/?uf=rn&city=montanhas&provider=brasil-api
# [or]
/api/city/show/rn/montanhas/brasil-api

/api/city/show/?uf=rn&city=montanhas&provider=ibge-gov-br
# [or]
/api/city/show/rn/montanhas/ibge-gov-br
```


----
- > **GET /api/city/list/{uf}/{provider?}**

```sh
/api/city/list/{uf}/brasil-api [or] list/{uf}/?provider='brasil-api'

# [or]

/api/city/list/{uf}/ibge-gov-br [or] list/{uf}/?provider='ibge-gov-br'
```


----
- > **GET /api/city/by-ibge-id**


```sh
# Params
# uf -> required
# ibge_id -> required
```


----
- > **GET /api/city/search**


```sh
# Params
# uf -> required
# city -> required
# ibge_id
```
