# phpcfdi/sat-pys-scraper dockerfile helper

```shell script
# get the project repository on folder "sat-pys-scraper"
git clone https://github.com/phpcfdi/sat-pys-scraper.git sat-pys-scraper

# build the image "sat-pys-scraper" from folder "sat-pys-scraper/"
docker build --tag sat-pys-scraper sat-pys-scraper/

# remove image sat-pys-scraper
docker rmi sat-pys-scraper
```

## Run command

The project installed on `/opt/sat-pys-scraper/` and the entry point is the command
`/opt/sat-pys-scraper/bin/sat-pys-scraper`.

```shell
# show help
docker run -it --rm --user="$(id -u):$(id -g)" \
  sat-pys-scraper --help

# create output using volume
docker run -it --rm --user="$(id -u):$(id -g)" --volume="${PWD}:/local" \
  sat-pys-scraper --xml /local/output.xml

# pipe output to file (xml, sorted by key)
docker run -it --rm --user="$(id -u):$(id -g)" \
  sat-pys-scraper --xml - > output.xml

# pipe output to file (json, sorted by name)
docker run -it --rm --user="$(id -u):$(id -g)" \
  sat-pys-scraper --json - --sort name > output.json
```
